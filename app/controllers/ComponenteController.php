<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Componente;
use app\repositories\ComponenteRepositorySql;
use app\services\ComponenteService;

class ComponenteController extends Controller
{
    private ComponenteRepositorySql $repositorySql;
    private ComponenteService $service;
    public function __construct()
    {
        $this->service = new ComponenteService();
        $this->repositorySql = new ComponenteRepositorySql();
    }
    public function listar()
    {
        $this->loginRequired();
        $data['componentes'] = $this->repositorySql->listarTodos();
        $this->view("componente/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("componente/create");
    }
    public function salvar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',  trim($_POST["descricao"]));
        $validador->obrigatorio('imagem',  trim($_POST["imagem"]));
        $validador->tamanho('nome', trim($_POST["nome"]), 3,100);
        $validador->tamanho('descricao', trim($_POST["descricao"]), 3,100);
        
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]   = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["imagem"] = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);
    
        $componente = Componente::map([$posts])[0];
        if($validador->temErros())
        {
            $data["componente"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('componente/create',$data);
        }
        else
        {
            if ($this->service->salvarComponente($componente))
            {
                $this->redirect(URL_BASE . '/componente/listar');
            } 
            else
            {
                $data["componente"] = $componente;
                $data["erros"]["componente"] = "Erro: Este componente já está cadastrado!";
                $this->view('componente/create',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $componente = new Componente();
        $componente->setId($_POST["id"]);
        $data["componente"] = $this->repositorySql->buscarId($componente);
        if($data["componente"]->getNome() != null)
        {
            $this->view("componente/edit",$data);
        }
        else
        {
            $this->view("componente/list");    
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',   trim($_POST["descricao"]));
        $validador->obrigatorio('imagem',   trim($_POST["imagem"]));
        $validador->tamanho('nome', trim($_POST["nome"]), 3,100);
        
        $posts["id"]  = $_POST["id"];
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]  =  trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["imagem"] = filter_input(INPUT_POST, 'imagem', FILTER_SANITIZE_URL);
        
        $componente = Componente::map([$posts])[0];
        if($validador->temErros())
        {
            $data["componente"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('componente/edit',$data);
        }
        else
        {
            if ($this->service->editarComponente($componente))
            {
                $this->redirect(URL_BASE . '/componente/listar');
            } 
            else
            {
                $data["componente"] = $componente;
                $data["erros"]["componente"] = "Erro: Este componente já está cadastrado!";
                $this->view('componente/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();
        $componente = new Componente();
        $componente->setId($_GET["id"]);
        $data["componente"] = $this->repositorySql->buscarId($componente);
        if($data["componente"]->getNome() != null)
        {
            $this->view("componente/perfil",$data);
        }
        else
        {
            $this->view("componente/list");    
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $componente = new Componente();
        $componente->setId($_POST["id"]);
        $this->repositorySql->deletar($componente);
        $this->listar();
    }
}