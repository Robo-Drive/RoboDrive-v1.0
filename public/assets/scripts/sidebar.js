const botaoMenu = document.getElementById("botaoMenu");
const sidebar = document.getElementById("sidebar");
const fundoSidebar = document.getElementById("fundoSidebar");
const telaGrande = window.matchMedia("(min-width: 1024px)");

// O botão sanduíche só aparece nas páginas que têm sidebar
botaoMenu.classList.replace("hidden", "flex");

function alternarMenu(abrir)
{
    sidebar.classList.toggle("is-open", abrir);
    fundoSidebar.classList.toggle("hidden", !abrir);
    botaoMenu.setAttribute("aria-expanded", abrir);
    botaoMenu.setAttribute("aria-label", abrir ? "Fechar menu" : "Abrir menu");

    if (abrir)
    {
        sidebar.querySelector("a, button").focus();
    }
}

botaoMenu.addEventListener("click", () => {
    alternarMenu(!sidebar.classList.contains("is-open"));
});

fundoSidebar.addEventListener("click", () => alternarMenu(false));

document.addEventListener("keydown", evento => {
    if (evento.key === "Escape" && sidebar.classList.contains("is-open"))
    {
        alternarMenu(false);
        botaoMenu.focus();
    }
});

// Ao aumentar a tela para o tamanho grande, a sidebar volta a ser fixa
telaGrande.addEventListener("change", () => {
    if (telaGrande.matches)
    {
        alternarMenu(false);
    }
});
