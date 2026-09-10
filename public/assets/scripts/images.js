const inputImagens = document.getElementById("imagens");
const listaImagens = document.getElementById("listaImagens");

const imagens = [];

function adicionarImagens()
{
    inputImagens.click();
}

inputImagens.addEventListener("change", () => {

    if (!inputImagens.multiple)
    {
        imagens.length = 0;
    }
    for (const imagem of inputImagens.files)
    {

        if (!imagem.type.startsWith("image/"))
        {
            continue;
        }

        const existe = imagens.some(item =>
            item.name === imagem.name &&
            item.size === imagem.size &&
            item.lastModified === imagem.lastModified
        );

        if (!existe)
        {
            imagens.push(imagem);
        }
    }

    inputImagens.value = "";
    atualizarListaImagens();

});

function atualizarListaImagens()
{

    listaImagens.innerHTML = "";

    imagens.forEach((imagem, indice) => {

        const li = document.createElement("li");
        li.className = "rd-file-item";

        const esquerda = document.createElement("div");
        esquerda.className = "flex items-center gap-3";

        const preview = document.createElement("img");
        preview.src = URL.createObjectURL(imagem);
        preview.className = "rd-file-thumb";
        preview.alt = imagem.name;

        const info = document.createElement("div");

        const nome = document.createElement("p");
        nome.textContent = imagem.name;
        nome.className = "text-sm text-[#F2FEFE]";

        const tamanho = document.createElement("p");
        tamanho.textContent = `${(imagem.size / 1024).toFixed(0)} KB`;
        tamanho.className = "text-xs text-[#4E6B72]";

        info.appendChild(nome);
        info.appendChild(tamanho);

        esquerda.appendChild(preview);
        esquerda.appendChild(info);

        const botao = document.createElement("button");
        botao.type = "button";
        botao.className = "cursor-pointer shrink-0";

        const icone = document.createElement("img");
        icone.src = `${IMG_URL_BASE}/close-icon.png`;
        icone.className = "h-4 w-4 opacity-70";
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

function atualizarInputImagens()
{

    const dataTransfer = new DataTransfer();

    imagens.forEach(imagem => {
        dataTransfer.items.add(imagem);
    });

    inputImagens.files = dataTransfer.files;
}

function removerImagem(indice)
{

    imagens.splice(indice, 1);

    atualizarListaImagens();
}
