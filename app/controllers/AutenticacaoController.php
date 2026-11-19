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

    public function __construct()
    {
        $this->service = new AutenticacaoService();
        $this->repositorySql = new UsuarioRepositorySql();
        $this->mailController = new MailController();
    }

    public function login()
    {
        $this->view("login/login");
    }

    public function logar()
    {
        $validador = new ValidadorHelper();

        $validador->obrigatorio("email", trim($_POST["email"] ?? ""));
        $validador->obrigatorio("senha", $_POST["senha"] ?? "");

        if (!isset($validador->getErros()["email"]))
        {
            $validador->email(trim($_POST["email"]));
        }

        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["usuario"] = [
                "email" => $_POST["email"] ?? "",
                "senha" => $_POST["senha"] ?? ""
            ];

            $data["erros"] = $validador->getErros();

            $this->view("login/login", $data);
            return;
        }

        $posts["email"] = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $posts["senha"] = $_POST["senha"];

        $usuario = Usuario::map([$posts])[0];

        $resposta = $this->service->logar($usuario->getEmail(),$usuario->getSenha());

        if (is_bool($resposta) && $resposta)
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => true,
                    "mensagem" => "Login realizado com sucesso!",
                    "redirect" => URL_BASE . "/usuario/perfil"
                ]);
            }

            $this->redirect(URL_BASE . "/usuario/perfil");
            return;
        }

        if ($this->requisicaoAjax())
        {
            $this->responderJson([
                "sucesso" => false,
                "mensagem" => $resposta,
                "erros" => [
                    "email" => $resposta
                ]
            ], 422);
        }

        $data["usuario"] = $posts;
        $data["erros"]["login"] = $resposta;

        $this->view("login/login", $data);
    }


    public function cadastro()
    {
        $this->view("cadastro/cadastro");
    }


    public function logout()
    {
        $this->service->logout();

        if ($this->requisicaoAjax())
        {
            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Sessão encerrada com sucesso!",
                "redirect" => URL_BASE
            ]);
        }

        $this->redirect(URL_BASE);
    }


    public function formularioRecuperarSenha()
    {
        $this->view("login/recuperarSenha");
    }

    public function enviarLinkRecuperacao()
    {
        $validador = new ValidadorHelper();

        $validador->obrigatorio("email",trim($_POST["email"] ?? ""),"Informe seu e-mail");

        if (!isset($validador->getErros()["email"]))
        {
            $validador->email(trim($_POST["email"]));
        }

        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["erros"] = $validador->getErros();
            $data["email"] = $_POST["email"] ?? "";

            $this->view("login/recuperarSenha", $data);
            return;
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

        // Mensagem genérica para não revelar se o e-mail existe
        $mensagem = "Se o e-mail informado estiver cadastrado, um link de recuperação foi enviado para ele.";

        if ($this->requisicaoAjax())
        {
            $this->responderJson([
                "sucesso" => true,
                "mensagem" => $mensagem
            ]);
        }

        $data["sucesso"] = $mensagem;

        $this->view("login/recuperarSenha", $data);
    }

    // =========================================================
    // REDEFINIÇÃO DE SENHA
    // =========================================================

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
        // Validação
        $token = trim($_POST["token"] ?? "");
        $senha = $_POST["senha"] ?? "";
        $confirmarSenha = $_POST["confirmar_senha"] ?? "";

        $validador = new ValidadorHelper();

        $validador->obrigatorio("senha",$senha,"Digite a nova senha");

        $validador->obrigatorio("confirmar_senha",$confirmarSenha,"Confirme a nova senha");

        if (!isset($validador->getErros()["senha"]))
        {
            $validador->tamanho("senha",$senha,8,100,"senha"
            );
        }

        $validador->confirmarValor($senha,$confirmarSenha,"confirmar_senha","As senhas não são iguais");

        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $this->view("login/redefinirSenha", [
                "token" => $token,
                "erros" => $validador->getErros()
            ]);

            return;
        }

        if ($this->repositorySql->redefinirSenhaComToken($token, $senha))
        {
            $mensagem = "Senha alterada com sucesso! Faça login com a nova senha.";

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => true,
                    "mensagem" => $mensagem,
                    "redirect" => URL_BASE
                ]);
            }

            $data["sucesso"] = $mensagem;

            $this->view("login/login", $data);
            return;
        }

        $mensagem = "Token expirado ou inválido. Solicite novamente.";

        if ($this->requisicaoAjax())
        {
            $this->responderJson([
                "sucesso" => false,
                "mensagem" => $mensagem
            ], 400);
        }

        $data["erro_geral"] = $mensagem;

        $this->view("login/recuperarSenha", $data);
    }
}
