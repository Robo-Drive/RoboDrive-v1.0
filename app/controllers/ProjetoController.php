<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use app\services\ProjetoService;

class ProjetoController extends Controller
{
    private ProjetoService $service;
    private ProjetoRepositorySql $repositorySql;

    public function __construct()
    {
        $this->service = new ProjetoService();
        $this->repositorySql = new ProjetoRepositorySql();
    }

    public function listar()
    {
        $this->loginRequired();
        $data['projetos'] = $this->repositorySql->listarTodos();
    
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
        
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('descricao',  $_POST["visibilidade"]);
        $validador->obrigatorio('visibilidade',  $_POST["visibilidade"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["descricao"]   = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"]  = filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $projeto = Projeto::map([$posts])[0];
        if($validador->temErros())
        {
            $data["projeto"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('projeto/create',$data);
        }
        else
        {
            if ($this->service->salvarProjeto($projeto))
            {
                $this->redirect(URL_BASE . '/projeto/listar');
            } 
            else
            {
                $data["projeto"] = $projeto;
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
        $projeto->setId($_POST["id"]);
        $data["projeto"] = $this->repositorySql->buscarId($projeto);
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