const input = document.getElementById("codigos");
const lista = document.getElementById("listaCodigos");

const arquivos = [];

function adicionarArquivos() {
    input.click();
}

input.addEventListener("change", () => {

    for (const arquivo of input.files) {

        const existe = arquivos.some(item =>
            item.name === arquivo.name &&
            item.size === arquivo.size &&
            item.lastModified === arquivo.lastModified
        );

        if (!existe) {
            arquivos.push(arquivo);
        }
    }

    input.value = "";
    atualizarLista();

});

function atualizarLista() {

    lista.innerHTML = "";

    arquivos.forEach((arquivo, indice) => {

        const li = document.createElement("li");
        li.className = "w-full h-12 bg-black/80 border border-white px-4 text-white hover:border-[#00F5F5] transition-all flex justify-between items-center";

        const span = document.createElement("span");
        span.textContent = `${arquivo.name} (${(arquivo.size / 1024).toFixed(0)} KB)`;

        const botao = document.createElement("button");
        botao.type = "button";
        botao.className = "cursor-pointer";

        const img = document.createElement("img");
        img.src = `${IMG_URL_BASE}/close-icon.png`;
        img.alt = "Remover";
        img.className = "w-5 h-5";

        botao.appendChild(img);

        botao.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            removerArquivo(indice);
        });

        li.appendChild(span);
        li.appendChild(botao);

        lista.appendChild(li);
    });

    atualizarInput();
}

function atualizarInput() {

    const dataTransfer = new DataTransfer();

    arquivos.forEach(arquivo => {
        dataTransfer.items.add(arquivo);
    });

    input.files = dataTransfer.files;
}

function removerArquivo(indice) {

    arquivos.splice(indice, 1);

    atualizarLista();
}