<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\repositories\ComentarioRepositorySql;

class ComentarioController extends Controller
{
    private ComentarioRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new ComentarioRepositorySql();
    }
    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $validador->obrigatorio("conteudo", trim($_POST["conteudo"] ?? ""));
        $validador->tipoComentario("tipo", trim($_POST["tipo"] ?? ""));
        $validador->idPositivo("origem_id", $_POST["origem_id"] ?? "");

        if ($validador->temErros())
        {
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE);
            return;
        }

        $tipo = trim($_POST["tipo"]);
        $origemId = filter_var($_POST["origem_id"], FILTER_VALIDATE_INT);
        $conteudo = trim($_POST["conteudo"]);
        $usuarioId = $_SESSION["usuario_logado"]->getId();

        try
        {
            if ($tipo === "forum")
            {
                $this->repositorySql->salvarForum($origemId, $usuarioId, $conteudo);

                $this->redirect(URL_BASE . "/forum/perfil?id=" . $origemId);
                return;
            }

            if ($tipo === "projeto")
            {
                $this->repositorySql->salvarProjeto($origemId, $usuarioId, $conteudo);

                $this->redirect(URL_BASE . "/projeto/perfil?id=" . $origemId);
                return;
            }
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao salvar comentário: " . $e->getMessage());

            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE);
            return;
        }
    }
    public function desativar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $validador->idPositivo("id", $_POST["id"] ?? "");
        $validador->tipoComentario("tipo", trim($_POST["tipo"] ?? ""));

        if ($validador->temErros())
        {
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE);
            return;
        }

        $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
        $tipo = trim($_POST["tipo"]);
        $usuarioLogado = $_SESSION["usuario_logado"];

        try
        {
            $comentario = $this->repositorySql->buscarPorId($tipo, $id);

            if (!$comentario)
            {
                $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE);
                return;
            }

            if ($comentario->getUsuario()->getId() == $usuarioLogado->getId() || $usuarioLogado->getRegra() === "admin")
            {
                $this->repositorySql->desativar($id, $tipo);
            }
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao desativar comentário: " . $e->getMessage());
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE);
    }
}
