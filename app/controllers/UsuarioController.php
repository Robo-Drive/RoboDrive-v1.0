<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Usuario;
use app\repositories\EquipeRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\UsuarioService;
class UsuarioController extends Controller
{
    private UsuarioService $service;
    private UsuarioRepositorySql $repositorySql;
    private EquipeRepositorySql $equipeRepositorySql;
    private ProjetoRepositorySql $projetoRepositorySql;

    public function __construct()
    {
        $this->service = new UsuarioService();
        $this->repositorySql = new UsuarioRepositorySql();
        $this->equipeRepositorySql = new EquipeRepositorySql();
        $this->projetoRepositorySql = new ProjetoRepositorySql();
    }

    public function listar()
    {
        $this->loginRequired();
        $data['usuarios'] = $this->repositorySql->listarTodos();
        $this->view("usuario/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("usuario/create");
    }
    public function salvar()
    {
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('nome_usuario', $_POST["nome_usuario"], "O campo nome de usuario é obrigatório");
        $validador->obrigatorio('email',  $_POST["email"]);
        $validador->obrigatorio('senha',  $_POST["senha"]);
        $validador->obrigatorio('confirmarSenha',  $_POST["confirmarSenha"],"O campo de confirmação de senha é obrigatório");
        if(!isset($validador->getErros()["nome"]))
        {
            $validador->tamanho('nome', $_POST["nome"], 3,100);
        }
        if(!isset($validador->getErros()["nome_usuario"]))
        {
            $validador->tamanho('nome_usuario', $_POST["nome_usuario"], 3,100);
        }
        if(!isset($validador->getErros()["senha"]))
        {
            $validador->tamanho('senha',$_POST["senha"],8,100);
        }
        if(!isset($validador->getErros()["senha"]) && !isset($validador->getErros()["confirmarSenha"]))
        {
            $validador->confirmarValor($_POST["senha"],$_POST["confirmarSenha"],"confirmarSenha","A senha digitada é diferente da do campo senha");
        }
        if(!isset($validador->getErros()["email"]))
        {
            $validador->email($_POST["email"]);
        }

        
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["nome_usuario"] = filter_input(INPUT_POST, 'nome_usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $posts["senha"]  = $_POST["senha"];
        
        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('cadastro/cadastro',$data);
        }
        else
        {
            if ($this->service->salvarUsuario($usuario))
            {
                (new AutenticacaoController())->logar();
            } 
            else
            {
                $data["usuario"] = $posts;
                $data["erros"]["email"] = "Erro: Este e-mail já está cadastrado!";
                $this->view('cadastro/cadastro',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
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
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('id',   $_POST["id"]);
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('email',  $_POST["email"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        if(isset($_POST["senha"]) && ($_POST["senha"] != "" && $_POST["senha"] != null))
        {
            $validador->tamanho('senha',$_POST["senha"],8,100);
        }
        $validador->email($_POST["email"]);
        $posts["id"] = $_POST["id"];
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"]  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if(isset($_POST["regra"]))
        {
            $posts["regra"]  = filter_input(INPUT_POST, 'regra', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        $posts["senha"]  = isset($_POST["senha"]) ? ($_POST["senha"] == "" ? null : $_POST["senha"] ): null;
        if(isset($_POST["imagem"]))    
        {
            $posts["imagem"] = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);
        }

        $usuario = Usuario::map([$posts])[0];
        if($validador->temErros())
        {
            $data["usuario"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/edit',$data);
        }
        else
        {
            $resposta = $this->service->editarUsuario($usuario);
            if($resposta)
            {
                $_SESSION["usuario_logado"] = $this->repositorySql->buscarId($_SESSION["usuario_logado"]);
                $this->redirect(URL_BASE . '/usuario/perfil');
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
        $this->loginRequired();
        if(isset($_GET["id"]))
        {
            $usuario = new Usuario();
            $usuario->setId($_GET["id"]);
            $data["usuario"] = $this->repositorySql->buscarId($usuario);
            $data["equipes"] = $this->equipeRepositorySql->buscarUsuario($usuario);
            $data["projetos"] = $this->projetoRepositorySql->buscarUsuario($usuario);
            
            if($data["usuario"]->getNome() != null)
            {
                $this->view("usuario/perfil",$data);
            }
            else
            {
                $this->view("usuario/list");
            }
        }
        else
        {
            $usuario = new Usuario();
            $usuario->setId($_SESSION["usuario_logado"]->getId());
            $data["usuario"] = $this->repositorySql->buscarId($usuario);
            $data["projetos"] = $this->projetoRepositorySql->buscarUsuario($data["usuario"]);
            $data["equipes"] = $this->equipeRepositorySql->buscarUsuario($data["usuario"]);
            $this->view("usuario/perfil",$data);
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $usuario = new Usuario();
        $usuario->setId($_POST["id"]);
        $this->repositorySql->deletar($usuario);
        $this->listar();
    }
}