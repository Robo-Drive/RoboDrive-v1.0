(function () {
    const INTERVALO_MS = 5000;

    const areas = Array.from(document.querySelectorAll("[data-atualizar][id]"));

    if (areas.length === 0) {
        return;
    }

    let buscando = false;

    function podeAtualizar() {
        if (document.hidden) return false;
        if (document.querySelector("dialog[open]")) return false;
        return true;
    }

    function areaEmUso(area) {
        const ativo = document.activeElement;
        return !!(ativo && area.contains(ativo) && ativo.matches("textarea, input, select"));
    }

    function temSobreposicaoAberta(area) {
        return !!area.querySelector(".fixed:not(.hidden)");
    }

    function trocarPreservandoValores(area, novoHtml) {
        const listas = () => area.tagName === "SELECT" ? [area] : Array.from(area.querySelectorAll("select[id]"));
        const valores = new Map(listas().map(function (s) { return [s.id, s.value]; }));

        area.innerHTML = novoHtml;

        listas().forEach(function (s) {
            const valor = valores.get(s.id);
            if (valor !== undefined && Array.from(s.options).some(function (o) { return o.value === valor; })) {
                s.value = valor;
            }
        });
    }

    async function atualizar() {
        if (buscando || !podeAtualizar()) return;

        buscando = true;

        try {
            const paginaAtual = window.location.href;
            const respostas = new Map();

            for (const area of areas) {
                if (areaEmUso(area) || temSobreposicaoAberta(area)) continue;

                const fonte = area.dataset.fonte || paginaAtual;

                if (!respostas.has(fonte)) {
                    respostas.set(fonte, fetch(fonte, {
                        headers: { "Accept": "text/html" },
                        cache: "no-store",
                        credentials: "same-origin"
                    }).then(async function (resposta) {
                        return {
                            ok: resposta.ok,
                            redirecionou: resposta.redirected,
                            url: resposta.url,
                            texto: resposta.ok ? await resposta.text() : null
                        };
                    }));
                }

                const resultado = await respostas.get(fonte);

                if (resultado.redirecionou && fonte === paginaAtual) {
                    window.location.href = resultado.url;
                    return;
                }

                if (!resultado.ok) continue;

                let novoHtml;

                if (area.dataset.fonte) {
                    novoHtml = resultado.texto;
                } else {
                    const documento = new DOMParser().parseFromString(resultado.texto, "text/html");
                    const areaNova = documento.getElementById(area.id);

                    if (!areaNova) continue;

                    novoHtml = areaNova.innerHTML;
                }

                if (novoHtml !== area.innerHTML) {
                    trocarPreservandoValores(area, novoHtml);
                }
            }
        } catch (erro) {
            console.error("Falha na atualização automática:", erro);
        } finally {
            buscando = false;
        }
    }

    setInterval(atualizar, INTERVALO_MS);
})();
