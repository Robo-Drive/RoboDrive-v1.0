// Recorte generico de imagens (Cropper.js 1.6.x). Nao altera images.js: o arquivo recortado
// e entregue a ele pelo proprio input. Cada input configura o recorte por data-recorte-*:
// largura, altura, tipo (image/png | image/jpeg), qualidade, nome, titulo e dica.
(() => {
    const modal = document.getElementById("modalRecorte");
    if (!modal) return;

    const LIMITE = 5 * 1024 * 1024; // limite do servidor por arquivo
    const dialog = document.getElementById("dialogRecorte");
    const titulo = document.getElementById("tituloRecorte");
    const dica = document.getElementById("dicaRecorte");
    const img = document.getElementById("imgRecorte");
    const zoom = document.getElementById("zoomRecorte");
    const btnUsar = document.getElementById("btnUsarRecorte");
    const btnDescartar = document.getElementById("btnDescartarRecorte");
    const btnTodas = document.getElementById("btnDescartarTodasRecorte");

    let ativo = null; // controlador do input em uso

    function iniciar(input) {
        const largura = parseFloat(input.dataset.recorteLargura);
        const altura = parseFloat(input.dataset.recorteAltura);
        const tipo = input.dataset.recorteTipo || "image/png";
        const qualidade = parseFloat(input.dataset.recorteQualidade || "0.9");
        const nome = input.dataset.recorteNome || "imagem";
        const tituloPadrao = input.dataset.recorteTitulo || "Recortar imagem";
        const dicaPadrao = input.dataset.recorteDica || "";
        const avatar = document.getElementById("avatarPerfil");
        const lista = document.getElementById("listaImagens");
        const avatarOriginal = avatar ? avatar.src : "";

        let cropper = null;
        let urlOrigem = null;
        let urlAvatar = null;
        let bypass = false;
        let ocupado = false;
        let confirmado = null; // modo single: arquivo que sera enviado (espelha a lista do images.js)
        let fila = [];
        let total = 0;
        let indice = 0;
        let foco = null;
        let erros = [];
        let contador = 0;

        const extensao = (t) => (t === "image/jpeg" ? "jpg" : "png");

        function entregar(arquivo) {
            bypass = true;
            if (arquivo) {
                const dt = new DataTransfer();
                dt.items.add(arquivo);
                input.files = dt.files;
            } else {
                input.value = "";
            }
            input.dispatchEvent(new Event("change"));
            bypass = false;
        }

        function sincronizarAvatar() {
            if (!avatar) return;
            if (urlAvatar) URL.revokeObjectURL(urlAvatar);
            urlAvatar = confirmado ? URL.createObjectURL(confirmado) : null;
            avatar.src = urlAvatar || avatarOriginal;
        }

        // Modo single: a lista do images.js e a fonte da verdade (inclusive o botao de remover)
        if (!input.multiple && lista) {
            new MutationObserver(() => {
                confirmado = input.files[0] || null;
                sincronizarAvatar();
            }).observe(lista, { childList: true });
        }

        function mostrarErros() {
            let p = document.getElementById("erroRecorte");
            if (!erros.length) {
                if (p) p.remove();
                return;
            }
            if (!p) {
                p = document.createElement("p");
                p.id = "erroRecorte";
                p.setAttribute("role", "alert");
                p.className = "rd-error";
                (lista || input).before(p);
            }
            p.textContent = erros.join(" ");
        }

        function focaveis() {
            return [...dialog.querySelectorAll("input, button")].filter(el => !el.disabled && el.offsetParent !== null);
        }

        function aoTeclar(e) {
            if (e.key === "Escape") {
                e.preventDefault();
                descartarAtual();
                return;
            }
            if (e.key !== "Tab") return;
            const itens = focaveis();
            const primeiro = itens[0];
            const ultimo = itens[itens.length - 1];
            const atualFoco = document.activeElement;
            if (!dialog.contains(atualFoco) || atualFoco === dialog) {
                e.preventDefault();
                (e.shiftKey ? ultimo : primeiro).focus();
            } else if (e.shiftKey && atualFoco === primeiro) {
                e.preventDefault();
                ultimo.focus();
            } else if (!e.shiftKey && atualFoco === ultimo) {
                e.preventDefault();
                primeiro.focus();
            }
        }

        function limparCropper() {
            img.onload = null;
            img.onerror = null;
            if (cropper) cropper.destroy();
            cropper = null;
            img.removeAttribute("src");
            if (urlOrigem) URL.revokeObjectURL(urlOrigem);
            urlOrigem = null;
        }

        function fecharModal() {
            limparCropper();
            document.removeEventListener("keydown", aoTeclar);
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            const alvo = foco && document.contains(foco) && foco !== document.body
                ? foco
                : document.querySelector('[onclick*="adicionarImagens"]');
            if (alvo) alvo.focus();
            foco = null;
            ativo = null;
        }

        function abrirAtual(arquivo) {
            limparCropper();
            titulo.textContent = total > 1 ? `Recortar imagem ${indice} de ${total}` : tituloPadrao;
            dica.textContent = dicaPadrao;
            dica.style.display = dicaPadrao ? "" : "none";
            btnTodas.style.display = fila.length > 0 ? "" : "none";

            urlOrigem = URL.createObjectURL(arquivo);
            img.onerror = () => falhar("Não foi possível abrir a imagem selecionada.");
            img.onload = () => {
                if (!img.naturalWidth) {
                    falhar("Não foi possível abrir a imagem selecionada.");
                    return;
                }
                cropper = new Cropper(img, {
                    aspectRatio: largura / altura,
                    viewMode: 1,
                    dragMode: "move",
                    autoCropArea: 0.9,
                    background: false,
                    ready() {
                        const base = cropper.getCanvasData().width / cropper.getImageData().naturalWidth;
                        zoom.min = base;
                        zoom.max = base * 4;
                        zoom.step = base / 100;
                        zoom.value = base;
                    },
                    zoom(e) {
                        zoom.value = e.detail.ratio;
                    },
                });
            };
            img.src = urlOrigem;
        }

        function proximo() {
            if (!fila.length) {
                fecharModal();
                mostrarErros();
                return;
            }
            const arquivo = fila.shift();
            indice++;
            if (!modal.classList.contains("flex")) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                document.addEventListener("keydown", aoTeclar);
                dialog.focus();
            }
            abrirAtual(arquivo);
        }

        // Descarta so o arquivo atual; no modo single restaura o estado anterior pelo mesmo caminho
        function descartarAtual() {
            if (!input.multiple) entregar(confirmado);
            proximo();
        }

        function falhar(mensagem) {
            erros.push(mensagem);
            descartarAtual();
        }

        function gerar(tipoSaida, q) {
            const opcoes = { maxWidth: largura, maxHeight: altura, imageSmoothingQuality: "high" };
            if (tipoSaida === "image/jpeg") opcoes.fillColor = "#000505";
            const canvas = cropper.getCroppedCanvas(opcoes);
            if (!canvas) return Promise.resolve(null);
            return new Promise(resolve => canvas.toBlob(resolve, tipoSaida, q));
        }

        async function usar() {
            if (!cropper || ocupado) return;
            ocupado = true;
            try {
                let tipoSaida = tipo;
                let blob = await gerar(tipoSaida, qualidade);
                if (blob && blob.size > LIMITE) {
                    tipoSaida = "image/jpeg";
                    blob = await gerar(tipoSaida, 0.85);
                }
                if (!blob) {
                    falhar("Não foi possível gerar a imagem recortada.");
                    return;
                }
                if (blob.size > LIMITE) {
                    falhar("A imagem recortada passa de 5 MB. Escolha uma imagem menor.");
                    return;
                }
                contador++;
                const arquivo = new File(
                    [blob],
                    `${nome}-${Date.now()}-${contador}.${extensao(tipoSaida)}`,
                    { type: tipoSaida, lastModified: Date.now() }
                );
                entregar(arquivo);
                proximo();
            } finally {
                ocupado = false;
            }
        }

        function descartarTodas() {
            fila = [];
            descartarAtual();
        }

        // Captura antes do images.js; sem Cropper o fluxo antigo segue intacto
        document.addEventListener("change", (e) => {
            if (bypass || e.target !== input || !window.Cropper || ativo) return;
            const arquivos = [...input.files].filter(f => f.type.startsWith("image/"));
            if (!arquivos.length) return;
            e.stopImmediatePropagation();

            ativo = {
                usar, descartarAtual, descartarTodas,
                zoomPara: (v) => { if (cropper) cropper.zoomTo(v); },
            };
            erros = [];
            mostrarErros();
            foco = document.activeElement;

            if (input.multiple) {
                fila = arquivos;
                entregar(null); // images.js reconstroi input.files so com o que ja foi aceito
            } else {
                fila = [arquivos[0]];
            }
            total = fila.length;
            indice = 0;
            proximo();
        }, true);
    }

    btnUsar.addEventListener("click", () => ativo && ativo.usar());
    btnDescartar.addEventListener("click", () => ativo && ativo.descartarAtual());
    btnTodas.addEventListener("click", () => ativo && ativo.descartarTodas());
    zoom.addEventListener("input", () => ativo && ativo.zoomPara(parseFloat(zoom.value)));

    document.querySelectorAll("input[type=file][data-recorte-largura]").forEach(iniciar);
})();
