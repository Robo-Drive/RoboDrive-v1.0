const listaDenuncias = document.getElementById("listaDenuncias");
const semResultado = document.getElementById("denunciasSemResultado");
const filtrosDenuncia = document.querySelectorAll("[data-filtro]");

// Mais denúncias primeiro; em caso de empate, a denúncia mais antiga vem antes
function ordenarDenuncias()
{
    const itens = [...listaDenuncias.querySelectorAll(".denuncia-item")];

    itens.sort((a, b) =>
        b.dataset.quantidade - a.dataset.quantidade ||
        a.dataset.primeira - b.dataset.primeira
    );

    itens.forEach((item, indice) => {
        item.querySelector(".denuncia-posicao").textContent = `#${indice + 1}`;
        listaDenuncias.appendChild(item);
    });
}

function filtrarDenuncias(tipo)
{
    let visiveis = 0;

    listaDenuncias.querySelectorAll(".denuncia-item").forEach(item => {
        const mostrar = tipo === "todos" || item.dataset.tipo === tipo;
        item.classList.toggle("hidden", !mostrar);
        if (mostrar)
        {
            visiveis++;
        }
    });

    filtrosDenuncia.forEach(filtro => {
        filtro.setAttribute("aria-pressed", filtro.dataset.filtro === tipo);
    });

    semResultado.classList.toggle("hidden", visiveis > 0);
}

if (listaDenuncias)
{
    ordenarDenuncias();

    filtrosDenuncia.forEach(filtro => {
        filtro.addEventListener("click", () => filtrarDenuncias(filtro.dataset.filtro));
    });

    listaDenuncias.querySelectorAll("form[data-confirmar]").forEach(form => {
        form.addEventListener("submit", evento => {
            if (!confirm(form.dataset.confirmar))
            {
                evento.preventDefault();
            }
        });
    });
}
