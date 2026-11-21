<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\repositories\ComentarioRepositorySql;

class ComentarioController extends Controller
{
    private ComentarioRepositorySql $repositorySql;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->repositorySql = new ComentarioRepositorySql();
        $this->validador = new ValidadorHelper();
    }

    public function validar()
    {
        $conteudo = trim($_POST["conteudo"] ?? "");

        $this->validador->obrigatorio("conteudo", $conteudo);

        if (!isset($this->validador->getErros()["conteudo"]))
        {
            $this->validador->tamanho("conteudo", $conteudo, 3, 2000);
        }

        $this->validador->tipoComentario("tipo", trim($_POST["tipo"] ?? ""));
        $this->validador->idPositivo("origem_id", $_POST["origem_id"] ?? "");
    }

    // Desativar: id do comentário e tipo
    public function validarDesativar()
    {
        $this->validador->idPositivo("id", $_POST["id"] ?? "");
        $this->validador->tipoComentario("tipo", trim($_POST["tipo"] ?? ""));
    }

    public function salvar()
    {
        $this->loginRequired();
        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $conteudo  = trim(filter_input(INPUT_POST, "conteudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS));;
        $tipo      = trim($_POST["tipo"]);
        $origemId  = (int)$_POST["origem_id"];
        $usuarioId = $_SESSION["usuario_logado"]->getId();

        // RN20: só comenta quem pode ver o conteúdo
        $erroContexto = (new \app\services\ComentarioService())->validarContexto($tipo, $origemId);

        if ($erroContexto)
        {
            $this->responderAcao(false, $erroContexto["erro"], $erroContexto["status"]);
        }

        try
        {
            if ($tipo === "forum")
            {
                $this->repositorySql->salvarForum($origemId, $usuarioId, $conteudo);
            }
            else
            {
                $this->repositorySql->salvarProjeto($origemId, $usuarioId, $conteudo);
            }
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao salvar comentário: " . $e->getMessage());

            $this->responderAcao(false, "Não foi possível publicar o comentário. Tente novamente.", 500);
        }

        $this->responderJson(["sucesso" => true]);
    }

    public function desativar()
    {
        $this->loginRequired();

        $this->validarDesativar();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $tipo          = trim($_POST["tipo"]);
        $id            = (int)$_POST["id"];
        $usuarioLogado = $_SESSION["usuario_logado"];

        try
        {
            $comentario = $this->repositorySql->buscarPorId($tipo, $id);

            if (!$comentario)
            {
                $this->responderAcao(false, "Comentário não encontrado.", 404);
            }

            $podeDesativar = $comentario->getUsuario()->getId() == $usuarioLogado->getId() || $usuarioLogado->getRegra() === "admin";

            if (!$podeDesativar)
            {
                $this->responderAcao(false, "Você não tem permissão para desativar este comentário.", 403);
            }
            
            if ($this->exigeJustificativa((int)$comentario->getUsuario()->getId()))
            {
                $erroJustificativa = $this->erroJustificativa();

                if ($erroJustificativa)
                {
                    $this->responderErroJustificativa($erroJustificativa);
                }
            }

            $this->repositorySql->desativar($id, $tipo);
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao desativar comentário: " . $e->getMessage());

            $this->responderAcao(false, "Não foi possível desativar o comentário.", 500);
        }

        $this->responderJson(["sucesso" => true]);
    }
}
