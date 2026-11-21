// --- MANIPULAÇÃO DE CÓDIGOS ---
const inputCodigos = document.getElementById("codigos");
const listaCodigos = document.getElementById("listaCodigos");
const arquivosCodigos = [];

function adicionarArquivos() {
    if (inputCodigos) inputCodigos.click();
}

if (inputCodigos) {
    inputCodigos.addEventListener("change", () => {
        for (const arquivo of inputCodigos.files) {
            const existe = arquivosCodigos.some(item =>
                item.name === arquivo.name &&
                item.size === arquivo.size &&
                item.lastModified === arquivo.lastModified
            );

            if (!existe) {
                arquivo.descricao = "";
                arquivosCodigos.push(arquivo);
            }
        }

        inputCodigos.value = "";
        atualizarListaCodigos();
    });
}

function atualizarListaCodigos() {
    if (!listaCodigos) return;
    listaCodigos.innerHTML = "";

    arquivosCodigos.forEach((arquivo, indice) => {
        const li = document.createElement("li");
        li.className = "w-full bg-[#06141C] border border-[#07556A] p-3 text-white hover:border-[#13F3F7] transition-all flex flex-col gap-2";

        const topo = document.createElement("div");
        topo.className = "flex justify-between items-center";

        const span = document.createElement("span");
        span.className = "text-xs font-semibold text-[#F2FEFE] truncate";
        span.textContent = `${arquivo.name} (${(arquivo.size / 1024).toFixed(0)} KB)`;

        const botao = document.createElement("button");
        botao.type = "button";
        botao.className = "cursor-pointer text-red-400 hover:text-red-300 font-bold px-2";
        botao.textContent = "✕";
        botao.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            removerCodigo(indice);
        });

        topo.appendChild(span);
        topo.appendChild(botao);

        const inputDescricao = document.createElement("input");
        inputDescricao.type = "text";
        inputDescricao.name = "codigos_descricao[]";
        inputDescricao.id = `codigos_descricao_${indice}`;
        inputDescricao.placeholder = "Descrição deste código (ex: Script de movimentação dos motores)...";
        inputDescricao.value = arquivo.descricao || "";
        inputDescricao.className = "w-full bg-[#000505] border border-[#07556A] p-2 text-white text-xs outline-none focus:border-[#13F3F7] transition-all";

        inputDescricao.addEventListener("input", (e) => {
            arquivo.descricao = e.target.value;
        });

        li.appendChild(topo);
        li.appendChild(inputDescricao);
        listaCodigos.appendChild(li);
    });

    atualizarInputCodigos();
}

function atualizarInputCodigos() {
    if (!inputCodigos) return;
    const dataTransfer = new DataTransfer();
    arquivosCodigos.forEach(arquivo => {
        dataTransfer.items.add(arquivo);
    });
    inputCodigos.files = dataTransfer.files;
}

function removerCodigo(indice) {
    arquivosCodigos.splice(indice, 1);
    atualizarListaCodigos();
}


// --- MANIPULAÇÃO DE EXTRAS ---
const inputExtras = document.getElementById("extras");
const listaExtras = document.getElementById("listaExtras");
const arquivosExtras = [];

function adicionarArquivosExtras() {
    if (inputExtras) inputExtras.click();
}

if (inputExtras) {
    inputExtras.addEventListener("change", () => {
        for (const arquivo of inputExtras.files) {
            const existe = arquivosExtras.some(item =>
                item.name === arquivo.name &&
                item.size === arquivo.size &&
                item.lastModified === arquivo.lastModified
            );

            if (!existe) {
                arquivo.descricao = "";
                arquivosExtras.push(arquivo);
            }
        }

        inputExtras.value = "";
        atualizarListaExtras();
    });
}

function atualizarListaExtras() {
    if (!listaExtras) return;
    listaExtras.innerHTML = "";

    arquivosExtras.forEach((arquivo, indice) => {
        const li = document.createElement("li");
        li.className = "w-full bg-[#06141C] border border-[#07556A] p-3 text-white hover:border-[#13F3F7] transition-all flex flex-col gap-2";

        const topo = document.createElement("div");
        topo.className = "flex justify-between items-center";

        const span = document.createElement("span");
        span.className = "text-xs font-semibold text-[#F2FEFE] truncate";
        span.textContent = `${arquivo.name} (${(arquivo.size / 1024).toFixed(0)} KB)`;

        const botao = document.createElement("button");
        botao.type = "button";
        botao.className = "cursor-pointer text-red-400 hover:text-red-300 font-bold px-2";
        botao.textContent = "✕";
        botao.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            removerExtra(indice);
        });

        topo.appendChild(span);
        topo.appendChild(botao);

        const inputDesc = document.createElement("input");
        inputDesc.type = "text";
        inputDesc.name = "extras_descricao[]";
        inputDesc.id = `extras_descricao_${indice}`;
        inputDesc.value = arquivo.descricao || "";
        inputDesc.placeholder = "Descrição ou finalidade deste arquivo (ex: Manual em PDF, Peça 3D STL)...";
        inputDesc.className = "w-full bg-[#000505] border border-[#07556A] p-2 text-xs text-white outline-none focus:border-[#13F3F7] transition-all";

        inputDesc.addEventListener("input", (e) => {
            arquivo.descricao = e.target.value;
        });

        li.appendChild(topo);
        li.appendChild(inputDesc);
        listaExtras.appendChild(li);
    });

    atualizarInputExtras();
}

function atualizarInputExtras() {
    if (!inputExtras) return;
    const dataTransfer = new DataTransfer();
    arquivosExtras.forEach(arquivo => {
        dataTransfer.items.add(arquivo);
    });
    inputExtras.files = dataTransfer.files;
}

function removerExtra(indice) {
    arquivosExtras.splice(indice, 1);
    atualizarListaExtras();
}