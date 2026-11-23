document.addEventListener("DOMContentLoaded", function() {
    const ESPERA_MS = 300;

    document.querySelectorAll("form[data-busca]").forEach(function(form) {
        const input = form.querySelector("input[type='text']");
        const seletorAlvo = form.dataset.alvo || "[data-resultados]";
        const resultados = document.querySelector(seletorAlvo);

        if (!input || !resultados) {
            return;
        }

        let temporizador = null;
        let controlador = null;

        function montarUrl() {
            const url = new URL(form.getAttribute("action"), window.location.href);
            const params = new URLSearchParams(window.location.search);

            url.searchParams.forEach(function(valor, chave) {
                if (!params.has(chave)) params.set(chave, valor);
            });

            new FormData(form).forEach(function(valor, chave) {
                params.set(chave, valor);
            });

            url.search = params.toString();
            return url.toString();
        }

        async function buscar() {
            const url = montarUrl();

            if (controlador) {
                controlador.abort();
            }
            controlador = new AbortController();

            try {
                const response = await fetch(url, {
                    signal: controlador.signal,
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                const html = await response.text();
                const pagina = new DOMParser().parseFromString(html, "text/html");
                const novosResultados = pagina.querySelector(seletorAlvo);

                if (novosResultados) {
                    resultados.innerHTML = novosResultados.innerHTML;
                    history.replaceState(null, "", url);
                }
            } catch (erro) {
                if (erro.name !== "AbortError") {
                    console.error("Erro na busca:", erro);
                }
            }
        }

        input.addEventListener("input", function() {
            clearTimeout(temporizador);
            temporizador = setTimeout(buscar, ESPERA_MS);
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            clearTimeout(temporizador);
            buscar();
        });
    });
});
