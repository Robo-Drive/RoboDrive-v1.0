<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;
use app\services\UsuarioService;

class AutenticacaoController extends Controller
{
    private UsuarioService $service;
    private UsuarioRepositorySql $repositorySql;

    public function __construct()
    {
        $this->service = new UsuarioService();
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
        $validador->email($_POST["email"]);

        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $posts["senha"]  = $_POST["senha"];
        
        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/create',$data);
        }
        else
        {
            $resposta = $this->service->logarUsuario($usuario);
            if($resposta)
            {
                $this->redirect(URL_BASE . '/usuario/listar');
            } 
            else
            {
                $data["erros"] = "Usuário ou senha incorretos";
                $this->view('login/login',$data);
            }
        }
        

    }
}