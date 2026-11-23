// Aviso padrão do sistema (substitui alert). Uso: rdAviso(mensagem, "sucesso" | "erro" | "info", persistir)
// persistir = true guarda o aviso para a próxima página (use antes de recarregar ou redirecionar).
(function () {
    const CHAVE = "rdAvisoPendente";
    const TEMPO = 6000;

    function criar(mensagem, tipo) {
        let pilha = document.getElementById("rdAvisos");
        if (!pilha) {
            pilha = document.createElement("div");
            pilha.id = "rdAvisos";
            pilha.className = "rd-avisos";
            document.body.appendChild(pilha);
        }

        const aviso = document.createElement("div");
        aviso.className = "rd-aviso rd-aviso-" + tipo;
        aviso.setAttribute("role", tipo === "erro" ? "alert" : "status");

        const texto = document.createElement("p");
        texto.className = "rd-aviso-texto";
        texto.textContent = mensagem;

        const fechar = document.createElement("button");
        fechar.type = "button";
        fechar.className = "rd-btn-icon rd-aviso-fechar";
        fechar.setAttribute("aria-label", "Fechar aviso");
        fechar.textContent = "\u00D7";
        fechar.addEventListener("click", () => aviso.remove());

        aviso.append(texto, fechar);
        pilha.appendChild(aviso);
        setTimeout(() => aviso.remove(), TEMPO);
    }

    window.rdAviso = function (mensagem, tipo = "info", persistir = false) {
        if (!mensagem) return;
        if (persistir) {
            try { sessionStorage.setItem(CHAVE, JSON.stringify({ mensagem, tipo })); } catch (_) { criar(mensagem, tipo); }
            return;
        }
        criar(mensagem, tipo);
    };

    // Confirmação padrão (substitui confirm). Uso: rdConfirmar(mensagem).then(ok => ...)
    // Formulários com data-confirmar="mensagem" passam por ela automaticamente.
    window.rdConfirmar = function (mensagem) {
        return new Promise(resolve => {
            const dialogo = document.createElement("dialog");
            dialogo.className = "rd-confirmar";
            dialogo.setAttribute("aria-labelledby", "rdConfirmarTitulo");
            dialogo.setAttribute("aria-describedby", "rdConfirmarTexto");

            const topo = document.createElement("div");
            topo.className = "rd-confirmar-topo";

            const titulo = document.createElement("h2");
            titulo.id = "rdConfirmarTitulo";
            titulo.className = "rd-confirmar-titulo";
            titulo.textContent = "CONFIRMAR AÇÃO";

            const texto = document.createElement("p");
            texto.id = "rdConfirmarTexto";
            texto.className = "rd-confirmar-texto";
            texto.textContent = mensagem;

            const acoes = document.createElement("div");
            acoes.className = "rd-confirmar-acoes";

            const cancelar = document.createElement("button");
            cancelar.type = "button";
            cancelar.className = "rd-btn rd-btn-ghost";
            cancelar.textContent = "Cancelar";

            const confirmar = document.createElement("button");
            confirmar.type = "button";
            confirmar.className = "rd-btn rd-btn-primary";
            confirmar.textContent = "Confirmar";

            let resultado = false;
            cancelar.addEventListener("click", () => dialogo.close());
            confirmar.addEventListener("click", () => { resultado = true; dialogo.close(); });
            dialogo.addEventListener("click", e => { if (e.target === dialogo) dialogo.close(); });
            dialogo.addEventListener("close", () => { dialogo.remove(); resolve(resultado); });

            acoes.append(cancelar, confirmar);
            dialogo.append(topo, titulo, texto, acoes);
            document.body.appendChild(dialogo);
            dialogo.showModal();
            cancelar.focus();
        });
    };

    document.addEventListener("submit", evento => {
        const form = evento.target;
        if (!form.dataset || !form.dataset.confirmar) return;
        if (form.dataset.confirmado) {
            delete form.dataset.confirmado;
            return;
        }
        evento.preventDefault();
        evento.stopImmediatePropagation();
        rdConfirmar(form.dataset.confirmar).then(ok => {
            if (!ok) return;
            form.dataset.confirmado = "1";
            form.requestSubmit(evento.submitter || undefined);
        });
    }, true);

    document.addEventListener("DOMContentLoaded", () => {
        try {
            const salvo = sessionStorage.getItem(CHAVE);
            if (salvo) {
                sessionStorage.removeItem(CHAVE);
                const { mensagem, tipo } = JSON.parse(salvo);
                criar(mensagem, tipo);
            }
        } catch (_) {}
    });
})();
