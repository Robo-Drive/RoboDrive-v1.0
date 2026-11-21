// Busca em tempo real: consulta o servidor assim que o usuário para de digitar
// e troca apenas a área de resultados ([data-resultados]) da página.
document.addEventListener("DOMContentLoaded", function() {
    const ESPERA_MS = 300;

    document.querySelectorAll("form[data-busca]").forEach(function(form) {
        const input = form.querySelector("input[name='busca']");
        const resultados = document.querySelector("[data-resultados]");

        if (!input || !resultados) {
            return;
        }

        let temporizador = null;
        let controlador = null;

        async function buscar() {
            const url = form.action + "?" + new URLSearchParams(new FormData(form)).toString();

            // Cancela a consulta anterior se o usuário continuou digitando
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
                const novosResultados = pagina.querySelector("[data-resultados]");

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

        // Enter ou botão Buscar consultam na hora, sem recarregar a página
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            clearTimeout(temporizador);
            buscar();
        });
    });
});
