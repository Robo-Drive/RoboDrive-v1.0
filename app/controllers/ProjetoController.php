<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use app\repositories\ComponenteRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\ProjetoService;

class ProjetoController extends Controller
{
    private ProjetoService $service;
    private ProjetoRepositorySql $repositorySql;
    private ComponenteRepositorySql $componenteRepositorySql;
    private UsuarioRepositorySql $usuarioRepositorySql;

    public function __construct()
    {
        $this->service = new ProjetoService();
        $this->repositorySql = new ProjetoRepositorySql();
        $this->componenteRepositorySql = new ComponenteRepositorySql();
        $this->usuarioRepositorySql = new UsuarioRepositorySql();
    }

    public function listar()
    {
        $this->loginRequired();
        $data['projetos'] = $this->repositorySql->listarPublico();
    
        $this->view("projeto/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("projeto/create");
    }
    public function salvar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',  trim($_POST["visibilidade"]));
        $validador->obrigatorio('visibilidade',  trim($_POST["visibilidade"]));
        $validador->tamanho('nome', trim($_POST["nome"]), 3,100);
        
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]   = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["visibilidade"]  = trim(filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["codigos"] = $_FILES["codigos"];
        $posts["imagens"] = $_FILES["imagens"];
        if($validador->temErros())
        {
            $data["projeto"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('projeto/create',$data);
        }
        else
        {
            if ($this->service->salvarProjeto($posts))
            {
                $this->redirect(URL_BASE . '/projeto/listar');
            } 
            else
            {
                $data["projeto"] = $posts;
                $this->view('projeto/create',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_POST["id"]);
        $data["projeto"] = $this->repositorySql->buscarId($projeto);
        if($data["projeto"]->getNome() != null)
        {
            $this->view("projeto/edit",$data);
        }
        else
        {
            $this->view("projeto/list");    
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('id',   trim($_POST["id"]));
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',  trim($_POST["descricao"]));
        $validador->obrigatorio('visibilidade',  trim($_POST["visibilidade"]));
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        $posts["id"] = $_POST["id"];
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]  = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["visibilidade"]  = trim(filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $projeto = Projeto::map([$posts])[0];
        if($validador->temErros())
        {
            $data["projeto"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/edit',$data);
        }
        else
        {
            if ($this->service->editarProjeto($projeto))
            {
                $this->redirect(URL_BASE . '/projeto/listar');
            } 
            else
            {
                $data["projeto"] = $projeto;
                $this->view('projeto/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_GET["id"]);
        $data["projeto"] = $this->repositorySql->buscarId($projeto);
        $componentes = $this->componenteRepositorySql->buscarProjeto($projeto->getId());
        $data["projeto"]->setComponentes($componentes);
        $data["usuarios"] = $this->usuarioRepositorySql->buscarProjeto($projeto->getId());
        if($data["projeto"]->getNome() != null)
        {
            $this->view("projeto/perfil",$data);
        }
        else
        {
            $this->view("projeto/list");    
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_POST["id"]);
        $this->repositorySql->deletar($projeto);
        $this->listar();
    }
}