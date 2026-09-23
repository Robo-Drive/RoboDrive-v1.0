const categoria = document.getElementById("categoria");
const pesquisaCategoria = document.getElementById("pesquisaCategoria");
const listaCategorias = document.getElementById("listaCategorias");
const novaCategoriaContainer = document.getElementById("novaCategoriaContainer");
const novaCategoria = document.getElementById("novaCategoria");

const opcoesCategoria = document.querySelectorAll(".categoria-option");


// Abrir lista ao clicar no campo
pesquisaCategoria.addEventListener("focus", () => {
    listaCategorias.classList.remove("hidden");
});


// Pesquisar categoria
pesquisaCategoria.addEventListener("input", () => {

    const pesquisa = pesquisaCategoria.value.toLowerCase();

    listaCategorias.classList.remove("hidden");

    opcoesCategoria.forEach(opcao => {

        const nome = opcao.dataset.nome;

        if (nome.includes(pesquisa)) {
            opcao.classList.remove("hidden");
        } else {
            opcao.classList.add("hidden");
        }

    });
});


// Selecionar categoria
opcoesCategoria.forEach(opcao => {

    opcao.addEventListener("click", () => {

        const valor = opcao.dataset.value;
        const nome = opcao.textContent.trim();

        // Define o valor do select real
        categoria.value = valor;

        // Mostra o nome escolhido no campo de pesquisa
        pesquisaCategoria.value = nome;

        // Fecha a lista
        listaCategorias.classList.add("hidden");


        // Se for criar categoria
        if (valor === "criar") {

            novaCategoriaContainer.classList.remove("hidden");

            novaCategoria.focus();

        } else {

            novaCategoriaContainer.classList.add("hidden");

            novaCategoria.value = "";

        }

    });

});


// Fechar lista quando clicar fora
document.addEventListener("click", (event) => {

    if (!event.target.closest(".divCategoria")) {
        listaCategorias.classList.add("hidden");
    }

});
