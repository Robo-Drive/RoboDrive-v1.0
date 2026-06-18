function passowrdChange()
{
    let password = document.querySelector(".password");
    let passwordButton = document.querySelector(".passwordButton");

    passwordButton.src = passwordButton.src === "http://127.0.0.1:8080/assets/images/visibility.png"
            ? "http://127.0.0.1:8080/assets/images/visibility-off.png"
            : "http://127.0.0.1:8080/assets/images/visibility.png";

    password.type =
        password.type === "password"
            ? "text"
            : "password";
}
function confirmarPassowrdChange()
{
    let password = document.querySelector(".confirmarPassword");
    let confirmarPasswordButton = document.querySelector(".confirmarPasswordButton");

    confirmarPasswordButton.src = confirmarPasswordButton.src === "http://127.0.0.1:8080/assets/images/visibility.png"
            ? "http://127.0.0.1:8080/assets/images/visibility-off.png"
            : "http://127.0.0.1:8080/assets/images/visibility.png";

    password.type =
        password.type === "password"
            ? "text"
            : "password";
}
let confirmarPassowrd =
    document.querySelector(
        ".confirmarPassword"
    );

let timeout;

function verificarSenha()
{
    let password =
        document.querySelector(
            ".password"
        );

    let erro =
        document.querySelector(
            ".confirmarErro"
        );

    if(confirmarPassowrd.value != password.value)
    {
        erro.textContent =
            "A senha digitada é diferente.";
    }
    else
    {
        erro.textContent =
            "";
    }
}

confirmarPassowrd.addEventListener(
    "input",
    function()
    {
        clearTimeout(timeout);

        timeout = setTimeout(() =>
        {
            verificarSenha();
        }, 500);
    }
);