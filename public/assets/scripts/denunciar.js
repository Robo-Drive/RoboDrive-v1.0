const modalDenunciar = document.getElementById("modalDenunciar");
const descricaoDenuncia = document.getElementById("denunciaDescricao");
const contadorDenuncia = modalDenunciar.querySelector("[data-contador]");

const rotulosDenuncia = {
    usuario: "Usuário",
    projeto: "Projeto",
    forum: "Postagem",
    comentario: "Comentário",
    componente: "Componente",
    equipe: "Equipe"
};

function abrirDenuncia(dados)
{
    const campos = {
        tipo: dados.tipo,
        id: dados.id,
        rotulo: rotulosDenuncia[dados.tipo],
        nome: dados.nome
    };

    for (const [campo, valor] of Object.entries(campos))
    {
        modalDenunciar.querySelectorAll(`[data-campo="${campo}"]`).forEach(elemento => {
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
    modalDenunciar.showModal();
    document.getElementById("denunciaMotivo").focus();
}

document.addEventListener("click", evento => {
    const gatilho = evento.target.closest("[data-denunciar]");
    if (gatilho)
    {
        abrirDenuncia(gatilho.dataset);
    }
});

// Fecha pelo botão de fechar/cancelar ou ao clicar fora da caixa (no backdrop)
modalDenunciar.addEventListener("click", evento => {
    if (evento.target === modalDenunciar || evento.target.closest("[data-fechar-modal]"))
    {
        modalDenunciar.close();
    }
});

modalDenunciar.addEventListener("close", () => {
    modalDenunciar.querySelector("form").reset();
    contadorDenuncia.textContent = 0;
});

descricaoDenuncia.addEventListener("input", () => {
    contadorDenuncia.textContent = descricaoDenuncia.value.length;
});
