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

    public function __construct()
    {
        $this->service = new AutenticacaoService();
        $this->repositorySql = new UsuarioRepositorySql();
    }
    public function login()
    {
        $this->view("login/login");
    }
    public function logar()
    {
        $validador = new ValidadorHelper();

        $validador->obrigatorio('email',  $_POST["email"]);
        $validador->obrigatorio('senha',  $_POST["senha"]);
        if(!isset($validador->getErros()["email"]))
        {
            $validador->email($_POST["email"]);
        }

        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $posts["senha"]  = $_POST["senha"];
        
        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('login/login',$data);
        }
        else
        {
            $resposta = $this->service->logar($usuario->getEmail(),$usuario->getSenha());
            if(is_bool($resposta))
            {
                $this->redirect(URL_BASE . '/usuario/perfil');
            } 
            else
            {
                $data["usuario"] = $posts;
                $data["erros"]["login"] = $resposta;
                $this->view('login/login',$data);
            }
        }
    }
    public function cadastro()
    {
        $this->view("cadastro/cadastro");
    }
    public function logout()
    {
        $this->service->logout();
        $this->redirect(URL_BASE);
    }
        public function formularioRecuperarSenha()
    {
        $this->view("login/recuperarSenha");
    }

    public function enviarLinkRecuperacao()
    {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $validador = new ValidadorHelper();
        $validador->obrigatorio('email', $email, 'Informe seu e-mail');
        if (!isset($validador->getErros()['email'])) {
            $validador->email($email);
        }

        if ($validador->temErros()) {
            $this->view('login/recuperarSenha', ['erros' => $validador->getErros(), 'email' => $email]);
            return;
        }

        $usuarioModel = new Usuario();
        $usuarioModel->setEmail($email);
        $usuarioEncontrado = $this->repositorySql->buscarEmail($usuarioModel);

        if ($usuarioEncontrado) {
            $token = bin2hex(random_bytes(32));
            $expiraEm = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->repositorySql->salvarTokenRecuperacao($usuarioEncontrado->getId(), $token, $expiraEm);

            $link = URL_BASE . "/redefinir-senha?token=" . $token;

            $mensagem = "
                <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                    <h2 style='color: #13F3F7;'>RoboDrive - Recuperação de Senha</h2>
                    <p>Olá, <strong>" . htmlspecialchars($usuarioEncontrado->getNome()) . "</strong>,</p>
                    <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                    <p>Clique no botão abaixo para cadastrar uma nova senha (link válido por 1 hora):</p>
                    <p style='margin: 24px 0;'>
                        <a href='{$link}' style='background-color: #13F3F7; color: #000505; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px; text-transform: uppercase;'>Redefinir Minha Senha</a>
                    </p>
                    <p style='font-size: 12px; color: #91B5BD;'>Se você não solicitou a alteração, ignore este e-mail.</p>
                </div>
            ";

            $mailHelper = new \app\helpers\MailHelper();
            $mailHelper->enviarEmail($email, null, '', '[RoboDrive] Recuperação de Senha', $mensagem);
        }

        $this->view('login/recuperarSenha', [
            'sucesso' => 'Se o e-mail informado estiver cadastrado, um link de recuperação foi enviado para ele.'
        ]);
    }

    public function formularioRedefinirSenha()
    {
        $token = trim($_GET['token'] ?? '');
        $tokenValido = $this->repositorySql->buscarTokenValido($token);

        if (!$tokenValido) {
            $this->view('login/recuperarSenha', ['erro_geral' => 'Este link de recuperação é inválido ou já expirou.']);
            return;
        }

        $this->view('login/redefinirSenha', ['token' => $token]);
    }

    public function atualizarSenhaRecuperada()
    {
        $token = trim($_POST['token'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        $validador = new ValidadorHelper();
        $validador->obrigatorio('senha', $senha, 'Digite a nova senha');
        $validador->tamanho('senha', $senha, 8, 100, 'Senha');

        if ($senha !== $confirmarSenha) {
            $validador->obrigatorio('confirmar_senha', '', 'As senhas não conferem');
        }

        if ($validador->temErros()) {
            $this->view('login/redefinirSenha', ['token' => $token, 'erros' => $validador->getErros()]);
            return;
        }

        if ($this->repositorySql->redefinirSenhaComToken($token, $senha)) {
            $this->view('login/login', ['sucesso' => 'Senha alterada com sucesso! Faça login com a nova senha.']);
        } else {
            $this->view('login/recuperarSenha', ['erro_geral' => 'Token expirado ou inválido. Solicite novamente.']);
        }
    }
}