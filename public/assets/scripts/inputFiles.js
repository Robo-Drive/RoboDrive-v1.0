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
            // Inicializa a descrição vazia no objeto do arquivo
            arquivo.descricao = "";
            arquivos.push(arquivo);
        }
    }

    input.value = "";
    atualizarLista();
});

function atualizarLista() {
    lista.innerHTML = "";

    arquivos.forEach((arquivo, indice) => {
        // Container do item (agora estilizado como um card para caber a descrição)
        const li = document.createElement("li");
        li.className = "w-full bg-black/80 border border-white p-3 text-white hover:border-[#00F5F5] transition-all flex flex-col gap-2";

        // Linha superior: Informações do arquivo + Botão de remover
        const topo = document.createElement("div");
        topo.className = "flex justify-between items-center";

        const span = document.createElement("span");
        span.className = "text-sm font-semibold truncate";
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

        topo.appendChild(span);
        topo.appendChild(botao);

        // Linha inferior: Input para a descrição do código
        const inputDescricao = document.createElement("input");
        inputDescricao.type = "text";
        inputDescricao.name = "codigos_descricao[]";
        inputDescricao.placeholder = "Descrição deste código (ex: Script de movimentação dos motores)";
        inputDescricao.value = arquivo.descricao || "";
        inputDescricao.className = "w-full h-10 bg-black border border-white/40 px-3 text-white text-sm outline-none focus:border-[#00F5F5] transition-all";

        // Salva no objeto para não perder o valor caso adicione/remova outros arquivos
        inputDescricao.addEventListener("input", (e) => {
            arquivo.descricao = e.target.value;
        });

        li.appendChild(topo);
        li.appendChild(inputDescricao);

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
