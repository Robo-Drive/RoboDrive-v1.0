const modalComunicado = document.getElementById("modalComunicado");
const modalDesativar = document.getElementById("modalDesativar");

const alvosDesativacao = {
    usuario:    { rotulo: "Usuário",    assunto: "Conta desativada",       descricao: () => "sua conta foi desativada" },
    projeto:    { rotulo: "Projeto",    assunto: "Projeto desativado",     descricao: nome => `o projeto "${nome}" foi desativado` },
    forum:      { rotulo: "Postagem",   assunto: "Postagem desativada",    descricao: () => "sua postagem no fórum foi desativada" },
    comentario: { rotulo: "Comentário", assunto: "Comentário desativado",  descricao: () => "seu comentário foi desativado" },
    componente: { rotulo: "Componente", assunto: "Componente desativado",  descricao: nome => `o componente "${nome}" foi desativado` },
    equipe:     { rotulo: "Equipe",     assunto: "Equipe desativada",      descricao: nome => `a equipe "${nome}" foi desativada` }
};

function preencherCampos(modal, campos)
{
    for (const [campo, valor] of Object.entries(campos))
    {
        modal.querySelectorAll(`[data-campo="${campo}"]`).forEach(elemento => {
            if (elemento.tagName === "INPUT")
            {
                elemento.value = valor;
            }
            else
            {
                elemento.textContent = valor;
            }
        });
    }
}

function atualizarTextarea(textarea)
{
    const contador = textarea.closest(".rd-field").querySelector("[data-contador]");
    const preview = document.getElementById(textarea.dataset.preview);
    const texto = textarea.value.trim();

    contador.textContent = textarea.value.length;
    preview.textContent = texto || preview.dataset.vazio;
    preview.classList.toggle("italic", !texto);
    preview.classList.toggle("opacity-50", !texto);
}

function abrirComunicado(dados)
{
    preencherCampos(modalComunicado, {
        id: dados.id,
        nome: dados.nome,
        email: dados.email
    });
    modalComunicado.showModal();
    modalComunicado.querySelector("textarea").focus();
}

function abrirDesativar(dados)
{
    const alvo = alvosDesativacao[dados.tipo];
    const form = modalDesativar.querySelector("form");

    form.action = `${form.dataset.rotaBase}/${dados.tipo}/desativar`;
    preencherCampos(modalDesativar, {
        id: dados.id,
        rotulo: alvo.rotulo,
        nome: dados.nome,
        destinatario: dados.responsavel || "coordenadores responsáveis",
        assunto: `[RoboDrive] ${alvo.assunto}`,
        saudacao: dados.responsavel ? `, ${dados.responsavel}` : "",
        descricao: alvo.descricao(dados.nome)
    });
    modalDesativar.showModal();
    modalDesativar.querySelector("textarea").focus();
}

document.addEventListener("click", evento => {
    const gatilhoComunicado = evento.target.closest("[data-comunicado]");
    const gatilhoDesativar = evento.target.closest("[data-desativar]");
    const botaoFechar = evento.target.closest("[data-fechar-modal]");

    if (gatilhoComunicado)
    {
        abrirComunicado(gatilhoComunicado.dataset);
    }
    else if (gatilhoDesativar)
    {
        abrirDesativar(gatilhoDesativar.dataset);
    }
    else if (botaoFechar)
    {
        botaoFechar.closest("dialog").close();
    }
});

[modalComunicado, modalDesativar].forEach(modal => {
    const textarea = modal.querySelector("textarea");

    textarea.addEventListener("input", () => atualizarTextarea(textarea));

    // Clique fora da caixa (no backdrop) fecha o modal
    modal.addEventListener("click", evento => {
        if (evento.target === modal)
        {
            modal.close();
        }
    });

    modal.addEventListener("close", () => {
        modal.querySelector("form").reset();
        atualizarTextarea(textarea);
    });

    atualizarTextarea(textarea);
});
// --- VALIDAÇÃO AJAX DE DESATIVAÇÃO COM JUSTIFICATIVA OBRIGATÓRIA ---
if (modalDesativar) {
    const formDesativar = modalDesativar.querySelector("form");

    formDesativar.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Remove erro anterior se houver
        const erroAntigo = formDesativar.querySelector(".rd-error-ajax");
        if (erroAntigo) erroAntigo.remove();

        const textarea = formDesativar.querySelector("textarea[name='justificativa']");
        const justificativa = textarea.value.trim();

        // Validação no frontend (mínimo 10 caracteres)
        if (justificativa.length < 10) {
            exibirErroDesativar(textarea, "A justificativa é obrigatória e deve ter pelo menos 10 caracteres.");
            return;
        }

        const submitBtn = formDesativar.querySelector("button[type='submit']");
        const textoOriginal = submitBtn ? submitBtn.innerText : "Desativar";
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerText = "Processando...";
        }

        try {
            const formData = new FormData(formDesativar);
            const response = await fetch(formDesativar.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            const data = await response.json();

            if (response.ok && data.sucesso) {
                modalDesativar.close();
                // Redireciona ou recarrega a página para atualizar as listas
                window.location.href = data.redirect || window.location.href;
            } else {
                exibirErroDesativar(textarea, data.mensagem || "Não foi possível desativar. Verifique os dados.");
            }
        } catch (erro) {
            console.error("Erro na requisição Ajax:", erro);
            // Se a rota redirecionou HTML em vez de JSON, recarrega a página
            window.location.reload();
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerText = textoOriginal;
            }
        }
    });

    function exibirErroDesativar(elemento, mensagem) {
        elemento.classList.add("border-red-500");
        const p = document.createElement("p");
        p.className = "rd-error rd-error-ajax mt-1.5 text-xs text-red-400 font-bold";
        p.textContent = mensagem;
        elemento.insertAdjacentElement("afterend", p);
        elemento.focus();
    }
}
