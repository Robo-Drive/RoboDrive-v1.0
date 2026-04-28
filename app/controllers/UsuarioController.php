<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;
use app\services\UsuarioService;
class UsuarioController extends Controller
{
    private UsuarioService $service;
    private UsuarioRepositorySql $repositorySql;

    public function __construct()
    {
        $this->service = new UsuarioService();
        $this->repositorySql = new UsuarioRepositorySql();
    }

    public function listar()
    {
        $data['usuarios'] = $this->repositorySql->listarTodos();
        $this->view("usuario/list",$data);
    }
    public function cadastrar()
    {
        $this->view("usuario/create");
    }
    public function salvar()
    {
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('email',  $_POST["email"]);
        $validador->obrigatorio('senha',  $_POST["senha"]);
        $validador->obrigatorio('imagem', $_POST["imagem"]);
        $validador->obrigatorio('regra',  $_POST["regra"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        $validador->tamanho('senha',$_POST["senha"],8,100);
        $validador->email($_POST["email"]);
        
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $posts["regra"]  = filter_input(INPUT_POST, 'regra', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["senha"]  = $_POST["senha"];
        $posts["imagem"] = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);

        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/create',$data);
        }
        else
        {
            if ($this->service->salvarUsuario($usuario))
            {
                $this->redirect(URL_BASE . '/usuario/listar');
            } 
            else
            {
                $data["usuario"] = $usuario;
                $data["erros"]["email"] = "Erro: Este e-mail já está cadastrado!";
                $this->view('usuario/create',$data);
            }
        }
    }
    public function editar()
    {
        $usuario = new Usuario();
        $usuario->setId($_POST["id"]);
        $data["usuario"] = $this->repositorySql->buscarId($usuario);
        if($data["usuario"]->getNome() != null)
        {
            $this->view("usuario/edit",$data);
        }
        else
        {
            $this->view("usuario/list");    
        }
    }
    public function atualizar()
    {
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('id',   $_POST["id"]);
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('email',  $_POST["email"]);
        $validador->obrigatorio('imagem', $_POST["imagem"]);
        $validador->obrigatorio('regra',  $_POST["regra"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        if(isset($_POST["senha"]) && ($_POST["senha"] != "" && $_POST["senha"] != null))
        {
            $validador->tamanho('senha',$_POST["senha"],8,100);
        }
        $validador->email($_POST["email"]);
        $posts["id"] = $_POST["id"];
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $posts["regra"]  = filter_input(INPUT_POST, 'regra', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["senha"]  = isset($_POST["senha"]) ? ($_POST["senha"] == "" ? null : $_POST["senha"] ): null;
        $posts["imagem"] = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);

        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/edit',$data);
        }
        else
        {
            if ($this->service->editarUsuario($usuario))
            {
                $this->redirect(URL_BASE . '/usuario/listar');
            } 
            else
            {
                $data["usuario"] = $usuario;
                $this->view('usuario/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $usuario = new Usuario();
        $usuario->setId($_POST["id"]);
        $data["usuario"] = $this->repositorySql->buscarId($usuario);
        if($data["usuario"]->getNome() != null)
        {
            $this->view("usuario/perfil",$data);
        }
        else
        {
            $this->view("usuario/list");    
        }

    }
    public function excluir()
    {
        $usuario = new Usuario();
        $usuario->setId($_POST["id"]);
        $this->repositorySql->deletar($usuario);
        $this->listar();
    }
}