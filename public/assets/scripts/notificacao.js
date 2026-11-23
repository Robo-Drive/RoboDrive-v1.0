(function() {
    const CHAVE_PENDENTE = "rdNotificacaoPendente";
    const DURACAO_MS = 5000;

    const TIPOS = {
        sucesso: { titulo: "Sucesso", borda: "border-[#13F3F7]", barra: "bg-[#13F3F7]", papel: "status" },
        erro: { titulo: "Atenção", borda: "border-[#F2A058]", barra: "bg-[#F2A058]", papel: "alert" }
    };

    function obterContainer() {
        let container = document.getElementById("rdNotificacoes");

        if (!container) {
            container = document.createElement("div");
            container.id = "rdNotificacoes";
            container.className = "fixed right-4 top-[calc(10dvh+1rem)] z-[100] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3";
            document.body.appendChild(container);
        }

        return container;
    }

    function fechar(caixa) {
        caixa.classList.add("opacity-0", "translate-x-4");
        setTimeout(function() { caixa.remove(); }, 200);
    }

    window.rdNotificar = function(mensagem, tipo) {
        const config = TIPOS[tipo] || TIPOS.sucesso;

        const caixa = document.createElement("div");
        caixa.setAttribute("role", config.papel);
        caixa.className = "border-[3px] " + config.borda + " bg-[#06141C] text-[#F2FEFE] shadow-[6px_6px_0_#07556A] transition duration-200 opacity-0 translate-x-4";

        const barra = document.createElement("div");
        barra.className = "h-[3px] w-full " + config.barra;

        const corpo = document.createElement("div");
        corpo.className = "flex items-start gap-3 p-4";

        const textos = document.createElement("div");
        textos.className = "min-w-0 flex-1";

        const titulo = document.createElement("p");
        titulo.className = "rd-eyebrow !mb-1";
        titulo.textContent = config.titulo;

        const texto = document.createElement("p");
        texto.className = "break-words text-sm leading-relaxed";
        texto.textContent = mensagem;

        const botao = document.createElement("button");
        botao.type = "button";
        botao.setAttribute("aria-label", "Fechar notificação");
        botao.className = "rd-btn-icon";
        botao.addEventListener("click", function() { fechar(caixa); });

        if (typeof IMG_URL_BASE !== "undefined") {
            const icone = document.createElement("img");
            icone.src = IMG_URL_BASE + "/close-icon.png";
            icone.alt = "";
            icone.className = "h-3 w-3";
            botao.appendChild(icone);
        } else {
            botao.textContent = "×";
        }

        textos.appendChild(titulo);
        textos.appendChild(texto);
        corpo.appendChild(textos);
        corpo.appendChild(botao);
        caixa.appendChild(barra);
        caixa.appendChild(corpo);
        obterContainer().appendChild(caixa);

        // Entrada suave
        requestAnimationFrame(function() {
            caixa.classList.remove("opacity-0", "translate-x-4");
        });

        setTimeout(function() { fechar(caixa); }, DURACAO_MS);
    };

    window.rdNotificarDepois = function(mensagem, tipo) {
        try {
            sessionStorage.setItem(CHAVE_PENDENTE, JSON.stringify({ mensagem: mensagem, tipo: tipo || "sucesso" }));
        } catch (_) {
            // Sem sessionStorage (modo privado, por exemplo): a ação já foi feita, só não há aviso
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        try {
            const pendente = sessionStorage.getItem(CHAVE_PENDENTE);

            if (pendente) {
                sessionStorage.removeItem(CHAVE_PENDENTE);
                const dados = JSON.parse(pendente);
                window.rdNotificar(dados.mensagem, dados.tipo);
            }
        } catch (_) {}
    });
})();
