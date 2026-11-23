(() => {
    const campo = document.getElementById("biografia");
    const contador = document.getElementById("contador-biografia");
    if (!campo || !contador) return;

    const atualizar = () => {
        contador.textContent = `${campo.value.length}/${campo.maxLength}`;
    };

    campo.addEventListener("input", atualizar);
    atualizar();
})();
