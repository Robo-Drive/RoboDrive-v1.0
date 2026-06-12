<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Equipe;
use app\repositories\EquipeRepositorySql;
use app\services\EquipeService;

class EquipeController extends Controller
{
    private EquipeRepositorySql $repositorySql;
    private EquipeService $service;
    public function __construct()
    {
        $this->service = new EquipeService();
        $this->repositorySql = new EquipeRepositorySql();
    }
    public function listar()
    {
        $this->loginRequired();
        $data['equipes'] = $this->repositorySql->listarTodos();
        $this->view("equipe/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("equipe/create");
    }
    public function salvar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('senha',  trim($_POST["senha"]));
        $validador->tamanho('nome', trim($_POST["nome"]), 3,100);
        $validador->tamanho('senha',trim($_POST["senha"]),8,100);
        
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
        $equipe = Equipe::map([$posts])[0];
        if($validador->temErros())
        {
            $data["equipe"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('equipe/create',$data);
        }
        else
        {
            if ($this->service->salvarEquipe($equipe))
            {
                $this->redirect(URL_BASE . '/equipe/listar');
            } 
            else
            {
                $data["equipe"] = $equipe;
                $data["erros"]["equipe"] = "Erro: Este equipe já está cadastrado!";
                $this->view('equipe/create',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $equipe = new Equipe();
        $equipe->setId($_POST["id"]);
        $data["equipe"] = $this->repositorySql->buscarId($equipe);
        if($data["equipe"]->getNome() != null)
        {
            $this->view("equipe/edit",$data);
        }
        else
        {
            $this->view("equipe/list");    
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        if(isset($_POST["senha"]) && ($_POST["senha"] != "" && $_POST["senha"] != null))
        {
            $validador->tamanho('senha',$_POST["senha"],8,100);
        }
        $posts["id"]  = $_POST["id"];
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["senha"]  = isset($_POST["senha"]) ? ($_POST["senha"] == "" ? null : trim($_POST["senha"]) ): null;
        
        $equipe = Equipe::map([$posts])[0];
        if($validador->temErros())
        {
            $data["equipe"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('equipe/edit',$data);
        }
        else
        {
            if ($this->service->editarEquipe($equipe))
            {
                $this->redirect(URL_BASE . '/equipe/listar');
            } 
            else
            {
                $data["equipe"] = $equipe;
                $data["erros"]["equipe"] = "Erro: Este equipe já está cadastrado!";
                $this->view('equipe/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();
        $equipe = new Equipe();
        $equipe->setId($_GET["id"]);
        $data["equipe"] = $this->repositorySql->buscarId($equipe);
        if($data["equipe"]->getNome() != null)
        {
            $this->view("equipe/perfil",$data);
        }
        else
        {
            $this->view("equipe/list");    
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $equipe = new Equipe();
        $equipe->setId($_POST["id"]);
        $this->repositorySql->deletar($equipe);
        $this->listar();
    }
}