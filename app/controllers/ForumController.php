<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Forum;
use app\repositories\ForumRepositorySql;
use app\services\ForumService;

class ForumController extends Controller
{
    private ForumRepositorySql $repositorySql;
    private ForumService $service;
    public function __construct()
    {
        $this->service = new ForumService();
        $this->repositorySql = new ForumRepositorySql();
    }
    public function listar()
    {
        $this->loginRequired();
        $data['foruns'] = $this->repositorySql->listarTodos();
        $this->view("forum/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("forum/create");
    }
    public function salvar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('conteudo',   trim($_POST["conteudo"]));
        $validador->obrigatorio('visibilidade',   trim($_POST["visibilidade"]));
        $validador->tamanho('conteudo', $_POST["conteudo"], 3,100);
        
        $posts["conteudo"]   = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"]   = filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $forum = Forum::map([$posts])[0];
        if($validador->temErros())
        {
            $data["forum"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('forum/create',$data);
        }
        else
        {
            if ($this->service->salvarForum($forum))
            {
                $this->redirect(URL_BASE . '/forum/listar');
            } 
            else
            {
                $data["forum"] = $forum;
                $data["erros"]["forum"] = "Erro: Este forum já está cadastrado!";
                $this->view('forum/create',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $forum = new Forum();
        $forum->setId($_POST["id"]);
        $data["forum"] = $this->repositorySql->buscarId($forum);
        if($data["forum"]->getConteudo() != null)
        {
            $this->view("forum/edit",$data);
        }
        else
        {
            $this->view("forum/list");    
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('conteudo',   $_POST["conteudo"]);
        $validador->tamanho('conteudo', $_POST["conteudo"], 3,100);
        
        $posts["id"]  = $_POST["id"];
        $posts["conteudo"]   = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"]   = filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $forum = Forum::map([$posts])[0];
        if($validador->temErros())
        {
            $data["forum"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('forum/edit',$data);
        }
        else
        {
            if ($this->service->editarForum($forum))
            {
                $this->redirect(URL_BASE . '/forum/listar');
            } 
            else
            {
                $data["forum"] = $forum;
                $data["erros"]["forum"] = "Erro: Este forum já está cadastrado!";
                $this->view('forum/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();
        $forum = new Forum();
        $forum->setId($_POST["id"]);
        $data["forum"] = $this->repositorySql->buscarId($forum);
        if($data["forum"]->getConteudo() != null)
        {
            $this->view("forum/perfil",$data);
        }
        else
        {
            $this->view("forum/list");    
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $forum = new Forum();
        $forum->setId($_POST["id"]);
        $this->repositorySql->deletar($forum);
        $this->listar();
    }
}