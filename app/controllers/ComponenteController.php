<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Componente;
use app\repositories\ComponenteRepositorySql;
use app\services\ComponenteService;
use app\services\UploadService;

class ComponenteController extends Controller
{
    private ComponenteRepositorySql $repositorySql;
    private ComponenteService $service;
    private UploadService $uploadService;
    public function __construct()
    {
        $this->service = new ComponenteService();
        $this->uploadService = new UploadService("/components"); 
        $this->repositorySql = new ComponenteRepositorySql();
    }
    public function listar()
    {
        $data["componentes"] = $this->repositorySql->listarTodos();
        $this->view("componente/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("componente/create");
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
    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        $validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome do componente é obrigatório.");
        $validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "Nome");
        $validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição é obrigatória.");
        $validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "Descrição");
        
        $posts["nome"] = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"] = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $respostaImagem = $validador->temImagem($_FILES);

        if ($respostaImagem["status"])
        {
            $resposta = $this->uploadService->upload($_FILES["imagem"]);
            if ($resposta["status"])
            {
                $posts["imagem"] = $resposta["mensagem"];
            }
            else
            {
                $validador->setErroImagem($resposta["mensagem"]);
            }
        }

        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["componente"] = $_POST;
            $data["erros"] = $validador->getErros();
            $this->view("componente/create", $data);
            return;
        }

        $componente = Componente::map([$posts])[0];

        try
        {
            if ($this->service->salvarComponente($componente))
            {
                if ($this->requisicaoAjax())
                {
                    $this->responderJson([
                        "sucesso" => true,
                        "mensagem" => "Componente cadastrado com sucesso!",
                        "redirect" => URL_BASE . "/componente/listar"
                    ]);
                }

                $this->redirect(URL_BASE . "/componente/listar");
                return;
            }

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Erro: Este componente já está cadastrado!"
                ], 422);
            }

            $data["componente"] = $componente;
            $data["erros"]["componente"] = "Erro: Este componente já está cadastrado!";
            $this->view("componente/create", $data);
        }
        catch (\Throwable $e)
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Erro interno ao cadastrar componente."
                ], 500);
            }

            $data["componente"] = $componente;
            $data["erros"]["componente"] = "Erro interno ao cadastrar componente.";
            $this->view("componente/create", $data);
        }
    }

    public function atualizar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        $validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome do componente é obrigatório.");
        $validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "Nome");
        $validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição é obrigatória.");
        $validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "Descrição");
        
        $posts["id"] = $_POST["id"] ?? null;
        $posts["nome"] = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"] = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        
        $respostaImagem = $validador->temImagem($_FILES, false);
        
        if ($respostaImagem["status"])
        {
            $resposta = $this->uploadService->upload($_FILES["imagem"]);
            if ($resposta["status"])
            {
                $posts["imagem"] = $resposta["mensagem"];
            }
            else
            {
                $validador->setErroImagem($resposta["mensagem"]);
            }  
        }
        
        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["componente"] = $_POST;
            $data["erros"] = $validador->getErros();
            $this->view("componente/edit", $data);
            return;
        }

        $componente = Componente::map([$posts])[0];

        try
        {
            if ($this->service->editarComponente($componente))
            {
                if ($this->requisicaoAjax())
                {
                    $this->responderJson([
                        "sucesso" => true,
                        "mensagem" => "Componente atualizado com sucesso!",
                        "redirect" => URL_BASE . "/componente/perfil?id=" . $posts["id"]
                    ]);
                }

                $this->redirect(URL_BASE . "/componente/listar");
                return;
            }

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Não foi possível atualizar o componente."
                ], 500);
            }

            $data["componente"] = $componente;
            $data["erros"]["componente"] = "Erro ao atualizar o componente.";
            $this->view("componente/edit", $data);
        }
        catch (\Throwable $e)
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Erro interno ao atualizar o componente."
                ], 500);
            }

            $data["componente"] = $componente;
            $data["erros"]["componente"] = "Erro interno ao atualizar o componente.";
            $this->view("componente/edit", $data);
        }
    }
    public function perfil()
    {
        $componente = new Componente();
        $componente->setId($_GET["id"] ?? null);
        $data["componente"] = $this->repositorySql->buscarId($componente);
        if($data["componente"] && $data["componente"]->getNome() != null)
        {
            $this->view("componente/perfil",$data);
        }
        else
        {
            $this->view("componente/list");    
        }
    }
    public function desativar()
    {
        $this->loginRequired();
        $componente = new Componente();
        $componente->setId($_POST["id"]);
        $this->repositorySql->desativar($componente);
        $this->listar();
    }
    
}