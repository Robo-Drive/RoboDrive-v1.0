const botaoMenu = document.getElementById("botaoMenu");
const sidebar = document.getElementById("sidebar");
const fundoSidebar = document.getElementById("fundoSidebar");
const telaGrande = window.matchMedia("(min-width: 1024px)");

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

telaGrande.addEventListener("change", () => {
    if (telaGrande.matches)
    {
        alternarMenu(false);
    }
});
