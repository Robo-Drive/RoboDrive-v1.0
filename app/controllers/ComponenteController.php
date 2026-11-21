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
    private ValidadorHelper $validador;
    private array $posts;
    public function __construct()
    {
        $this->service = new ComponenteService();
        $this->uploadService = new UploadService("/components"); 
        $this->repositorySql = new ComponenteRepositorySql();
        $this->validador = new ValidadorHelper();
    }
    public function listar()
    {
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");
        $data["busca_atual"] = $busca;
        $data["componentes"] = $this->repositorySql->listarTodos($busca);
        $this->view("componente/list",$data);
    }
    public function listarAdmin()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . "/componente/listar");
        }

        $status = (string)($_GET["status"] ?? "1");
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");

        $data["statusFiltro"] = $status;
        $data["busca_atual"] = $busca;
        $data["componentes"] = ($status === "todos") ? $this->repositorySql->listarAdminTodos($busca) : $this->repositorySql->listarPorStatus($status === "1", $busca);

        $this->view("componente/listAdmin",$data);
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

    public function validar(bool $editar=true)
    {
        $this->validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome do componente é obrigatório.");
        if(!isset($this->validador->getErros()["nome"]))
        {
            $this->validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100);
        }
        $this->validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição é obrigatória.");
        if(!isset($this->validador->getErros()["descricao"]))
        {
            $this->validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "descrição");
        }
        $respostaImagem = $this->validador->temImagem($_FILES,$editar);
        if ($respostaImagem["status"] && !$this->validador->temErros())
        {
            $resposta = $this->uploadService->upload($_FILES["imagem"]);
            if ($resposta["status"])
            {
                $this->posts["imagem"] = $resposta["mensagem"];
            }
            else
            {
                $this->validador->setErroImagem($resposta["mensagem"]);
            }
        }
        
    }

    public function salvar()
    {
        $this->loginRequired();
        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $this->posts["nome"] = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $this->posts["descricao"] = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $componente = Componente::map([$this->posts])[0];

        try
        {
            $resultado = $this->service->salvarComponente($componente);

            if ($resultado["resposta"])
            {
                $this->responderAcao(true, "Componente cadastrado com sucesso!", 200, URL_BASE . "/componente/listar");
            }

            $this->responderAcao(false, $resultado["erro"], 422, null, ["nome" => $resultado["erro"]]);
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao cadastrar componente: " . $e->getMessage());

            $this->responderAcao(false, "Erro interno ao cadastrar componente.", 500);
        }
    }

    public function atualizar()
    {
        $this->loginRequired();

        $this->validar(false);

        $this->posts["id"] = $_POST["id"] ?? null;
        $this->posts["nome"] = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $this->posts["descricao"] = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $componente = Componente::map([$this->posts])[0];

        try
        {
            $resultado = $this->service->editarComponente($componente);

            if ($resultado["resposta"])
            {
                $this->responderAcao(true, "Componente atualizado com sucesso!", 200, URL_BASE . "/componente/perfil?id=" . $this->posts["id"]);
            }

            $this->responderAcao(false, $resultado["erro"], 422, null, ["nome" => $resultado["erro"]]);
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao atualizar componente: " . $e->getMessage());

            $this->responderAcao(false, "Erro interno ao atualizar o componente.", 500);
        }
    }
    public function perfil()
    {
        $componente = new Componente();
        $componente->setId($_GET["id"] ?? null);
        $data["componente"] = $this->repositorySql->buscarId($componente, $this->ehAdmin());
        if($data["componente"] && $data["componente"]->getNome() != null)
        {
            $this->view("componente/perfil",$data);
        }
        else
        {
            $this->redirect(URL_BASE . "/componente/listar");
        }
    }
    public function desativar()
    {
        $this->loginRequired();

        $voltar = URL_BASE . "/componente/listar";
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id)
        {
            $this->responderAcao(false, "Componente inválido.", 422);
        }

        $componente = new Componente();
        $componente->setId($id);
        $componenteAtual = $this->repositorySql->buscarId($componente);

        if (!$componenteAtual)
        {
            $this->responderAcao(false, "Componente não encontrado.", 404);
        }

        $donoId = (int)($componenteAtual->getUsuario()?->getId() ?? 0);
        $logado = $_SESSION["usuario_logado"];

        if ($logado->getRegra() !== "admin" && $donoId !== (int)$logado->getId())
        {
            $this->responderAcao(false, "Você não tem permissão para desativar este componente.", 403);
        }

        if ($this->exigeJustificativa($donoId))
        {
            $erro = $this->erroJustificativa();

            if ($erro)
            {
                $this->responderErroJustificativa($erro);
            }
        }

        // Quem participa de projetos que usam o componente é avisado por e-mail
        $integrantes = $this->repositorySql->buscarIntegrantesDeProjetosComComponente($id);

        $this->repositorySql->desativar($componente);

        $this->avisarIntegrantes($integrantes, $componenteAtual->getNome());

        $this->responderAcao(true, "Componente desativado com sucesso.", 200, $voltar);
    }

    public function ativar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->responderAcao(false, "Acesso negado.", 403);
        }

        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id)
        {
            $this->responderAcao(false, "Componente inválido.", 422);
        }

        $componente = new Componente();
        $componente->setId($id);
        $item = $this->repositorySql->buscarId($componente, true);

        if (!$item)
        {
            $this->responderAcao(false, "Componente não encontrado.", 404);
        }

        if ($item->isStatus() !== false)
        {
            $this->responderAcao(false, "Componente já está ativo.", 422);
        }

        $this->repositorySql->ativar($componente);

        $this->responderAcao(true, "Componente reativado com sucesso.");
    }

    // Um e-mail por integrante, listando os projetos dele que usam o componente
    private function avisarIntegrantes(array $integrantes, string $nomeComponente): void
    {
        $porUsuario = [];

        foreach ($integrantes as $linha)
        {
            $porUsuario[$linha["usuario_id"]]["nome"] = $linha["nome"];
            $porUsuario[$linha["usuario_id"]]["email"] = $linha["email"];
            $porUsuario[$linha["usuario_id"]]["projetos"][] = $linha["projeto_nome"];
        }

        $mail = new MailController();

        foreach ($porUsuario as $usuario)
        {
            $mail->enviarComponenteDesativado($usuario["email"], $usuario["nome"], $nomeComponente, $usuario["projetos"]);
        }
    }
}
