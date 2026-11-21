const categoria = document.getElementById("categoria");
const pesquisaCategoria = document.getElementById("pesquisaCategoria");
const listaCategorias = document.getElementById("listaCategorias");
const novaCategoriaContainer = document.getElementById("novaCategoriaContainer");
const novaCategoria = document.getElementById("novaCategoria");

const opcoesCategoria = document.querySelectorAll(".categoria-option");


pesquisaCategoria.addEventListener("focus", () => {
    listaCategorias.classList.remove("hidden");
});


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


opcoesCategoria.forEach(opcao => {

    opcao.addEventListener("click", () => {

        const valor = opcao.dataset.value;
        const nome = opcao.textContent.trim();

        categoria.value = valor;

        pesquisaCategoria.value = nome;

        listaCategorias.classList.add("hidden");


        if (valor === "criar") {

            novaCategoriaContainer.classList.remove("hidden");

            novaCategoria.focus();

        } else {

            novaCategoriaContainer.classList.add("hidden");

            novaCategoria.value = "";

        }

    });

});


document.addEventListener("click", (event) => {

    if (!event.target.closest(".divCategoria")) {
        listaCategorias.classList.add("hidden");
    }

});
