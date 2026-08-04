const inputImagens = document.getElementById("imagens");
const listaImagens = document.getElementById("listaImagens");

const imagens = [];

function adicionarImagens() {
    inputImagens.click();
}

inputImagens.addEventListener("change", () => {

    for (const imagem of inputImagens.files) {

        // Aceita apenas imagens
        if (!imagem.type.startsWith("image/")) {
            continue;
        }

        const existe = imagens.some(item =>
            item.name === imagem.name &&
            item.size === imagem.size &&
            item.lastModified === imagem.lastModified
        );

        if (!existe) {
            imagens.push(imagem);
        }
    }

    inputImagens.value = "";
    atualizarListaImagens();

});

function atualizarListaImagens() {

    listaImagens.innerHTML = "";

    imagens.forEach((imagem, indice) => {

        const li = document.createElement("li");
        li.className = "w-full bg-black/80 border border-white p-3 text-white hover:border-[#FF1A1A] transition-all flex justify-between items-center";

        const esquerda = document.createElement("div");
        esquerda.className = "flex items-center gap-3";

        const preview = document.createElement("img");
        preview.src = URL.createObjectURL(imagem);
        preview.className = "w-12 h-12 object-cover rounded border border-white";
        preview.alt = imagem.name;

        const info = document.createElement("div");

        const nome = document.createElement("p");
        nome.textContent = imagem.name;
        nome.className = "text-sm";

        const tamanho = document.createElement("p");
        tamanho.textContent = `${(imagem.size / 1024).toFixed(0)} KB`;
        tamanho.className = "text-xs text-gray-400";

        info.appendChild(nome);
        info.appendChild(tamanho);

        esquerda.appendChild(preview);
        esquerda.appendChild(info);

        const botao = document.createElement("button");
        botao.type = "button";
        botao.className = "cursor-pointer";

        const icone = document.createElement("img");
        icone.src = `${IMG_URL_BASE}/close-icon.png`;
        icone.className = "w-5 h-5";
        icone.alt = "Remover";

        botao.appendChild(icone);

        botao.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            removerImagem(indice);
        });

        li.appendChild(esquerda);
        li.appendChild(botao);

        listaImagens.appendChild(li);
    });

    atualizarInputImagens();
}

function atualizarInputImagens() {

    const dataTransfer = new DataTransfer();

    imagens.forEach(imagem => {
        dataTransfer.items.add(imagem);
    });

    inputImagens.files = dataTransfer.files;
}

function removerImagem(indice) {

    imagens.splice(indice, 1);

    atualizarListaImagens();
}