// Recorte circular da foto de perfil (Cropper.js 1.6.x). Nao altera images.js:
// o arquivo recortado e entregue a ele pelo proprio input#imagens.
(() => {
    const input = document.getElementById("imagens");
    const modal = document.getElementById("modalRecorte");
    if (!input || !modal) return;

    const TIPOS = ["image/jpeg", "image/png", "image/webp", "image/gif"];
    const dialog = document.getElementById("dialogRecorte");
    const img = document.getElementById("imgRecorte");
    const zoom = document.getElementById("zoomRecorte");
    const btnUsar = document.getElementById("btnUsarRecorte");
    const btnDescartar = document.getElementById("btnDescartarRecorte");
    const btnSelecionar = document.getElementById("btnSelecionarImagem");
    const avatar = document.getElementById("avatarPerfil");
    const lista = document.getElementById("listaImagens");
    const avatarOriginal = avatar ? avatar.src : "";

    let cropper = null;
    let urlOrigem = null;
    let urlAvatar = null;
    let bypass = false;
    let confirmado = null; // arquivo que sera enviado (espelha a lista do images.js)

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

    // A lista do images.js e a fonte da verdade: qualquer mudanca nela (inclusive o botao de remover)
    new MutationObserver(() => {
        confirmado = input.files[0] || null;
        sincronizarAvatar();
    }).observe(lista, { childList: true });

    function focaveis() {
        return [...dialog.querySelectorAll("input, button")].filter(el => !el.disabled);
    }

    function aoTeclar(e) {
        if (e.key === "Escape") {
            e.preventDefault();
            descartar();
            return;
        }
        if (e.key !== "Tab") return;
        const itens = focaveis();
        const primeiro = itens[0];
        const ultimo = itens[itens.length - 1];
        const ativo = document.activeElement;
        if (!dialog.contains(ativo) || ativo === dialog) {
            e.preventDefault();
            (e.shiftKey ? ultimo : primeiro).focus();
        } else if (e.shiftKey && ativo === primeiro) {
            e.preventDefault();
            ultimo.focus();
        } else if (!e.shiftKey && ativo === ultimo) {
            e.preventDefault();
            primeiro.focus();
        }
    }

    function abrir(arquivo) {
        urlOrigem = URL.createObjectURL(arquivo);
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.addEventListener("keydown", aoTeclar);
        dialog.focus();

        img.addEventListener("load", () => {
            cropper = new Cropper(img, {
                aspectRatio: 1,
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
        }, { once: true });
        img.src = urlOrigem;
    }

    function fechar() {
        document.removeEventListener("keydown", aoTeclar);
        if (cropper) cropper.destroy();
        cropper = null;
        img.removeAttribute("src");
        if (urlOrigem) URL.revokeObjectURL(urlOrigem);
        urlOrigem = null;
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        btnSelecionar.focus();
    }

    function descartar() {
        fechar();
        entregar(confirmado); // restaura o estado anterior pelo mesmo caminho
    }

    function usar() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 512, height: 512, imageSmoothingQuality: "high" });
        if (!canvas) return;
        canvas.toBlob((blob) => {
            if (!blob) return;
            fechar();
            entregar(new File([blob], "perfil.png", { type: "image/png" }));
        }, "image/png");
    }

    zoom.addEventListener("input", () => {
        if (cropper) cropper.zoomTo(parseFloat(zoom.value));
    });
    btnUsar.addEventListener("click", usar);
    btnDescartar.addEventListener("click", descartar);

    // Captura antes do images.js; sem Cropper o fluxo antigo segue intacto
    document.addEventListener("change", (e) => {
        if (bypass || e.target !== input || !window.Cropper) return;
        const arquivo = input.files[0];
        if (!arquivo || !TIPOS.includes(arquivo.type)) return;
        e.stopImmediatePropagation();
        abrir(arquivo);
    }, true);
})();
