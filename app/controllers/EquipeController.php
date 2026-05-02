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
        
        $validador->obrigatorio('nome',   $_POST["nome"]);
        $validador->obrigatorio('descricao',  $_POST["visibilidade"]);
        $validador->obrigatorio('visibilidade',  $_POST["visibilidade"]);
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        
        $posts["nome"]   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["descricao"]   = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"]  = filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
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
}