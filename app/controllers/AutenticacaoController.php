<?php

namespace app\controllers;

use app\core\Controller;
use app\services\AutenticacaoService;
use app\services\UsuarioService;

class AutenticacaoController extends Controller
{
    private AutenticacaoService $service;
    private UsuarioService $usuarioService;

    public function __construct()
    {
        $this->service = new AutenticacaoService();
        $this->usuarioService = new UsuarioService();
    }

    public function login()
    {
        if (isset($_SESSION['user_logado'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $this->view('auth/login', []);
    }

    public function cadastro()
    {
        if (isset($_SESSION['user_logado'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $this->view('auth/cadastro', []);
    }

    public function logar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(URL_BASE . '/login');
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->service->logar($email, $senha);

        if ($resultado) {
            $this->redirect(URL_BASE . '/equipes');
            return;
        }

        $this->view('auth/login', [
            'error' => 'Email ou senha inválidos',
            'email' => $email,
        ]);
    }

    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(URL_BASE . '/cadastro');
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $perfil = $_POST['perfil'] ?? 'aluno';

        $resultado = $this->usuarioService->registrar($nome, $email, $senha, $perfil);

        if ($resultado['sucesso']) {
            $this->redirect(URL_BASE . '/login');
            return;
        }

        $this->view('auth/cadastro', [
            'erros' => $resultado['erros'] ?? [],
            'mensagem' => $resultado['mensagem'] ?? null,
            'nome' => $nome,
            'email' => $email,
            'perfil' => $perfil,
        ]);
    }

    public function logout()
    {
        $this->service->logout();
        $this->redirect(URL_BASE . '/login');
    }
}
