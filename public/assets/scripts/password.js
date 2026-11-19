function passowrdChange() {
    const password = document.querySelector(".password");
    const passwordButton = document.querySelector(".passwordButton");

    if (!password || !passwordButton) return;

    if (password.type === "password") {
        password.type = "text";
        passwordButton.src = passwordButton.src.replace("visibility.png", "visibility-off.png");
    } else {
        password.type = "password";
        passwordButton.src = passwordButton.src.replace("visibility-off.png", "visibility.png");
    }
}

function confirmarPassowrdChange() {
    const password = document.querySelector(".confirmarPassword");
    const confirmarPasswordButton = document.querySelector(".confirmarPasswordButton");

    if (!password || !confirmarPasswordButton) return;

    if (password.type === "password") {
        password.type = "text";
        confirmarPasswordButton.src = confirmarPasswordButton.src.replace("visibility.png", "visibility-off.png");
    } else {
        password.type = "password";
        confirmarPasswordButton.src = confirmarPasswordButton.src.replace("visibility-off.png", "visibility.png");
    }
}

let timeout;

function verificarSenha() {
    const password = document.querySelector(".password");
    const confirmarPassowrd = document.querySelector(".confirmarPassword");
    const erro = document.querySelector(".confirmarErro");

    if (!confirmarPassowrd || !password || !erro) return;

    if (confirmarPassowrd.value !== password.value) {
        erro.textContent = "A senha digitada é diferente.";
    } else {
        erro.textContent = "";
    }
}

const campoConfirmar = document.querySelector(".confirmarPassword");
if (campoConfirmar) {
    campoConfirmar.addEventListener("input", function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            verificarSenha();
        }, 500);
    });
}