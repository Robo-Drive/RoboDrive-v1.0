<?php

namespace app\services;

use app\models\Forum;
use app\repositories\EquipeRepositorySql;
use app\repositories\ForumRepositorySql;


class ForumService
{
    private ForumRepositorySql $repositorySql;
    
    public function __construct()
    {
        $this->repositorySql = new ForumRepositorySql();
    }
    public function podeVisualizar(Forum $postagem): bool
    {
        $visibilidade = $postagem->getVisibilidade();

        if ($visibilidade === "publico" || $visibilidade === null)
        {
            return true;
        }

        $logado = $_SESSION["usuario_logado"] ?? null;

        if (!$logado)
        {
            return false;
        }

        if ($logado->getRegra() === "admin")
        {
            return true;
        }

        if ($visibilidade === "equipe")
        {
            $equipeId = (int)($postagem->getEquipe()?->getId() ?? 0);

            return $equipeId > 0 && (new EquipeRepositorySql())->usuarioPertenceAEquipe($equipeId, (int)$logado->getId());
        }

        return (int)$postagem->getUsuario()?->getId() === (int)$logado->getId();
    }

    public function coordenaEquipeDaPostagem(Forum $postagem): bool
    {
        $logado = $_SESSION["usuario_logado"] ?? null;
        $equipeId = (int)($postagem->getEquipe()?->getId() ?? 0);

        return $logado && $equipeId > 0
            && (new EquipeRepositorySql())->usuarioECoordenador($equipeId, (int)$logado->getId());
    }

    public function validarEquipe(int $equipeId, ?int $equipeAtualId = null): ?string
    {
        if ($equipeId === $equipeAtualId)
        {
            return null;
        }

        $logado = $_SESSION["usuario_logado"] ?? null;

        if ($logado && ($logado->getRegra() === "admin" || (new EquipeRepositorySql())->usuarioPertenceAEquipe($equipeId, (int)$logado->getId())))
        {
            return null;
        }

        return "Você não participa da equipe selecionada.";
    }

    public function buscarPostagem(int $id): ?Forum
    {
        $busca = new Forum();
        $busca->setId($id);

        return $this->repositorySql->buscarId($busca);
    }

    public function salvarForum(Forum $forum): bool
    {
        $this->repositorySql->cadastrar($forum);
        return true;
    }
    public function editarForum(Forum $forum): bool
    {
        try
        {
            $this->repositorySql->editar($forum);
            return true;
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());
            return false;
        }
    }
}