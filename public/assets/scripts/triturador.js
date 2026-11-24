(function () {
    const VELOCIDADE = 30;

    const area = document.getElementById("trituradorFaixa");
    const botao = document.getElementById("trituradorPausa");
    const faixa = area ? area.querySelector(".rd-trit-faixa") : null;

    if (!faixa) {
        return;
    }

    const originais = Array.from(faixa.children);
    const reduzido = window.matchMedia("(prefers-reduced-motion: reduce)");

    let cards = [];
    let ultimos = [];
    let larguraCard = 0;
    let passo = 0;
    let linhaX = 0;
    let ciclo = 0;
    let offset = 0;
    let anterior = null;
    let quadro = 0;
    let pausado = false;
    let visivel = false;

    function medir() {
        Array.from(faixa.children).forEach(function (card) {
            if (card.dataset.clone) card.remove();
        });

        larguraCard = originais[0].offsetWidth;
        passo = larguraCard + (parseFloat(getComputedStyle(faixa).columnGap) || 0);
        linhaX = area.clientWidth * (parseFloat(getComputedStyle(area).getPropertyValue("--trit-linha")) || 50) / 100;

        const repeticoes = Math.max(1, Math.ceil((area.clientWidth + passo) / (passo * originais.length)));
        ciclo = passo * originais.length * repeticoes;

        for (let i = 0; i < repeticoes * 2 - 1; i++) {
            originais.forEach(function (original) {
                const copia = original.cloneNode(true);
                copia.dataset.clone = "1";
                faixa.appendChild(copia);
            });
        }

        cards = Array.from(faixa.children);
        ultimos = cards.map(function () { return null; });
        offset = offset % ciclo;
    }

    function desenhar() {
        faixa.style.transform = "translate3d(" + (-offset) + "px, 0, 0)";
        for (let j = 0; j < cards.length; j++) {
            const esq = j * passo - offset;
            const corte = Math.min(1, Math.max(0, (linhaX - esq) / larguraCard)) * 100;
            if (corte !== ultimos[j]) {
                ultimos[j] = corte;
                cards[j].style.setProperty("--corte", corte + "%");
            }
        }
    }

    function passar(agora) {
        const dt = anterior === null ? 0 : Math.min((agora - anterior) / 1000, 0.1);
        anterior = agora;
        offset = (offset + VELOCIDADE * dt) % ciclo;
        desenhar();
        quadro = requestAnimationFrame(passar);
    }

    function quadroEstatico() {
        offset = passo - linhaX + larguraCard / 2;
        if (offset < 0) offset += ciclo;
        desenhar();
    }

    function atualizar() {
        const rodar = !pausado && !document.hidden && visivel && !reduzido.matches;
        if (rodar && !quadro) {
            anterior = null;
            quadro = requestAnimationFrame(passar);
        } else if (!rodar && quadro) {
            cancelAnimationFrame(quadro);
            quadro = 0;
        }
    }

    function aplicarMovimento() {
        if (botao) botao.hidden = reduzido.matches;
        if (reduzido.matches) quadroEstatico();
        atualizar();
    }

    medir();
    desenhar();
    aplicarMovimento();

    if (botao) {
        botao.addEventListener("click", function () {
            pausado = !pausado;
            botao.textContent = pausado ? "RETOMAR" : "PAUSAR";
            area.classList.toggle("rd-trit-pausada", pausado);
            atualizar();
        });
    }

    new IntersectionObserver(function (entradas) {
        visivel = entradas[0].isIntersecting;
        atualizar();
    }).observe(area);

    document.addEventListener("visibilitychange", atualizar);
    reduzido.addEventListener("change", aplicarMovimento);

    window.addEventListener("resize", function () {
        medir();
        if (reduzido.matches) quadroEstatico(); else desenhar();
    });
})();
