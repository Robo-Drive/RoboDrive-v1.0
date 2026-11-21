// Caixa de confirmação no estilo do site. Qualquer formulário com data-confirmar="mensagem"
// pede confirmação antes de ser enviado (substitui o confirm() nativo do navegador).
(function () {
    let caixa = null;

    function criarCaixa() {
        const dialog = document.createElement("dialog");
        dialog.id = "caixaConfirmacao";
        dialog.setAttribute("aria-labelledby", "caixaConfirmacaoTitulo");
        dialog.className = "rd-scroll-hidden m-auto w-[calc(100%-2rem)] max-w-md border-[3px] border-[#07556A] bg-[#06141C] p-0 text-[#F2FEFE] shadow-[8px_8px_0_#07556A] backdrop:bg-[#000505]/85";
        dialog.innerHTML = `
            <div class="h-[3px] w-full bg-[#F2A058]"></div>
            <div class="flex flex-col gap-4 p-5 sm:p-6">
                <p class="rd-eyebrow">Confirmação</p>
                <h2 id="caixaConfirmacaoTitulo" class="rd-heading text-lg uppercase sm:text-xl">Tem certeza?</h2>
                <p data-mensagem class="text-sm leading-relaxed text-[#91B5BD]"></p>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t-[3px] border-[#07556A] p-5 sm:flex-row sm:justify-end sm:p-6">
                <button type="button" data-cancelar class="rd-btn rd-btn-ghost">Cancelar</button>
                <button type="button" data-confirmar-acao class="rd-btn border-[#F2A058] bg-[#F2A058] text-[#000505] hover:border-[#F7BD8A] hover:bg-[#F7BD8A]">Confirmar</button>
            </div>`;
        document.body.appendChild(dialog);
        return dialog;
    }

    function perguntar(mensagem) {
        return new Promise(function (resolver) {
            caixa = caixa || criarCaixa();

            caixa.querySelector("[data-mensagem]").textContent = mensagem;

            const botaoConfirmar = caixa.querySelector("[data-confirmar-acao]");
            const botaoCancelar = caixa.querySelector("[data-cancelar]");

            function responder(valor) {
                caixa.close();
                resolver(valor);
            }

            botaoConfirmar.onclick = function () { responder(true); };
            botaoCancelar.onclick = function () { responder(false); };
            caixa.oncancel = function (evento) {
                evento.preventDefault();
                responder(false);
            };

            caixa.showModal();
            botaoCancelar.focus();
        });
    }

    // Captura o envio antes dos outros scripts, pede confirmação e só então envia de novo
    document.addEventListener("submit", async function (evento) {
        const form = evento.target;

        if (!(form instanceof HTMLFormElement) || !form.dataset.confirmar) {
            return;
        }

        // Envio já confirmado: deixa seguir normalmente
        if (form.dataset.confirmado === "1") {
            return;
        }

        evento.preventDefault();
        evento.stopImmediatePropagation();

        const botaoEnvio = evento.submitter;
        const confirmado = await perguntar(form.dataset.confirmar);

        if (confirmado) {
            form.dataset.confirmado = "1";
            try {
                botaoEnvio ? form.requestSubmit(botaoEnvio) : form.requestSubmit();
            } finally {
                delete form.dataset.confirmado;
            }
        }
    }, true);
})();
