(function () {
    if (!window.matchMedia("(hover: hover) and (pointer: fine)").matches) return;

    let alvo = null;
    let x = 0;
    let y = 0;
    let quadro = 0;

    function pintar() {
        quadro = 0;
        if (!alvo) return;
        const caixa = alvo.getBoundingClientRect();
        alvo.style.setProperty("--mx", (x - caixa.left) + "px");
        alvo.style.setProperty("--my", (y - caixa.top) + "px");
    }

    document.addEventListener("pointermove", function (evento) {
        const card = evento.target.closest && evento.target.closest(".rd-glow");
        if (!card) return;
        alvo = card;
        x = evento.clientX;
        y = evento.clientY;
        if (!quadro) quadro = requestAnimationFrame(pintar);
    }, { passive: true });

    document.addEventListener("pointerout", function (evento) {
        const card = evento.target.closest && evento.target.closest(".rd-glow");
        if (!card || card.contains(evento.relatedTarget)) return;
        if (alvo === card) alvo = null;
        card.style.removeProperty("--mx");
        card.style.removeProperty("--my");
    });
})();
