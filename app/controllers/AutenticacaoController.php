<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;
use app\services\AutenticacaoService;

class AutenticacaoController extends Controller
{
    private AutenticacaoService $service;
    private UsuarioRepositorySql $repositorySql;
    private MailController $mailController;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->validador = new ValidadorHelper();
        $this->service = new AutenticacaoService();
        $this->repositorySql = new UsuarioRepositorySql();
        $this->mailController = new MailController();
    }

    public function login()
    {
        $this->view("login/login");
    }

    public function validar()
    {
        $this->validador->obrigatorio("email", trim($_POST["email"] ?? ""));
        $this->validador->obrigatorio("senha", $_POST["senha"] ?? "");

        if (!isset($this->validador->getErros()["email"]))
        {
            $this->validador->email(trim($_POST["email"]));
        }
    }

    public function validarEmail()
    {
        $this->validador->obrigatorio("email", trim($_POST["email"] ?? ""), "Informe seu e-mail");

        if (!isset($this->validador->getErros()["email"]))
        {
            $this->validador->email(trim($_POST["email"]));
        }
    }

    public function validarNovaSenha()
    {
        $senha = $_POST["senha"] ?? "";
        $confirmarSenha = $_POST["confirmar_senha"] ?? "";

        $this->validador->obrigatorio("senha", $senha, "Digite a nova senha");
        $this->validador->obrigatorio("confirmar_senha", $confirmarSenha, "Confirme a nova senha");

        if (!isset($this->validador->getErros()["senha"]))
        {
            $this->validador->tamanho("senha", $senha, 8, 100);
        }

        $this->validador->confirmarValor($senha, $confirmarSenha, "confirmar_senha", "As senhas não são iguais");
    }

    public function logar()
    {
        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $posts["email"] = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $posts["senha"] = $_POST["senha"];

        $usuario = Usuario::map([$posts])[0];

        $resposta = $this->service->logar($usuario->getEmail(),$usuario->getSenha());

        if (is_bool($resposta) && $resposta)
        {
            $this->responderAcao(true, "Login realizado com sucesso!", 200, URL_BASE . "/usuario/perfil");
        }

        $this->responderAcao(false, $resposta, 422, null, ["email" => $resposta]);
    }

    public function cadastro()
    {
        $this->view("cadastro/cadastro");
    }

    public function logout()
    {
        $this->service->logout();

        $this->responderAcao(true, "Sessão encerrada com sucesso!", 200, URL_BASE);
    }

    public function formularioRecuperarSenha()
    {
        $this->view("login/recuperarSenha");
    }

    public function enviarLinkRecuperacao()
    {
        $this->validarEmail();

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $posts["email"] = filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);

        $usuario = new Usuario();
        $usuario->setEmail($posts["email"]);

        $usuarioEncontrado = $this->repositorySql->buscarEmail($usuario);

        if ($usuarioEncontrado)
        {
            $token = bin2hex(random_bytes(32));
            $expiraEm = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $this->repositorySql->salvarTokenRecuperacao(
                $usuarioEncontrado->getId(),
                $token,
                $expiraEm
            );

            $link = URL_BASE . "/redefinir-senha?token=" . $token;

            try
            {
                $this->mailController->enviarRecuperacaoSenha(
                    $usuario->getEmail(),
                    $usuarioEncontrado->getNome(),
                    $link
                );
            }
            catch (\Throwable $e)
            {
                error_log(
                    "Erro ao enviar e-mail de recuperação: " . $e->getMessage()
                );
            }
        }

        $this->responderAcao(true, "Se o e-mail informado estiver cadastrado, um link de recuperação foi enviado para ele.");
    }

    public function formularioRedefinirSenha()
    {
        $token = trim($_GET["token"] ?? "");

        $tokenValido = $this->repositorySql->buscarTokenValido($token);

        if (!$tokenValido)
        {
            $data["erro_geral"] = "Este link de recuperação é inválido ou já expirou.";

            $this->view("login/recuperarSenha", $data);
            return;
        }

        $data["token"] = $token;

        $this->view("login/redefinirSenha", $data);
    }

    public function atualizarSenhaRecuperada()
    {
        $token = trim($_POST["token"] ?? "");
        $senha = $_POST["senha"] ?? "";

        $this->validarNovaSenha();

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        if ($this->repositorySql->redefinirSenhaComToken($token, $senha))
        {
            $this->responderAcao(true, "Senha alterada com sucesso! Faça login com a nova senha.", 200, URL_BASE);
        }

        $this->responderAcao(false, "Token expirado ou inválido. Solicite novamente.", 400);
    }
}
