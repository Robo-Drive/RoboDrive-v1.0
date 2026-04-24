<?php

namespace app\services;

use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;

class AutenticacaoService
{
    private UsuarioRepositorySql $usuarioRepositorySql;
    public function __construct()
    {
        $this->usuarioRepositorySql = new UsuarioRepositorySql();
    }
    public function logar(string $email,string $senha): bool
    {
        $usuario = $this->usuarioRepositorySql->buscarEmail((new Usuario)->setEmail($email));
        print_r($usuario);
        die;
        if($usuario != false && password_verify($senha,$usuario->getSenha()))
        {
            $_SESSION["usuario_logado"] = $usuario;
            print_r($_SESSION);
            return true;
        }
        return false;
    }
    public function logout()
    {
        session_destroy();
    }
}