<?php

namespace app\controllers;

use app\core\Controller;
use app\repositories\ComentarioRepositorySql;
use app\repositories\DenunciaRepositorySql;

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

        $tipo      = trim($_POST['tipo'] ?? '');
        $origemId  = $_POST['origem_id'] ?? 0;
        $conteudo  = trim($_POST['conteudo'] ?? '');
        $usuarioId = $_SESSION['usuario_logado']->getId();

        if (empty($conteudo) || $origemId <= 0 || !in_array($tipo, ['forum', 'projeto'], true))
        {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
            return;
        }

        if ($tipo === 'forum')
        {
            $this->repositorySql->salvarForum($origemId, $usuarioId, $conteudo);
            $this->redirect(URL_BASE . '/forum/perfil?id=' . $origemId);
            return;
        }

        if ($tipo === 'projeto')
        {
            $this->repositorySql->salvarProjeto($origemId, $usuarioId, $conteudo);
            $this->redirect(URL_BASE . '/projeto/perfil?id=' . $origemId);
            return;
        }
    }

    public function desativar()
    {
        $this->loginRequired();

        $id        = (int)($_POST['id'] ?? 0);
        $tipo      = trim($_POST['tipo'] ?? '');
        $usuarioLogado = $_SESSION['usuario_logado'];

        if ($id <= 0)
        {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
            return;
        }

        $comentario = $this->repositorySql->buscarPorId($tipo, $id);
        if (!$comentario)
        {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
            return;
        }

        // Permite desativar se for o autor do comentário OU admin
        if ($comentario['usuario_id'] == $usuarioLogado->getId() || $usuarioLogado->getRegra() === 'admin')
        {
            $this->repositorySql->desativar($id, $tipo);
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
    }

    /* public function desativar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $id = $_POST['id'] ?? 0;
        if ($id > 0)
        {
            $this->repositorySql->desativar($id);
            (new DenunciaRepositorySql())->dispensar('comentario', $id);
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE . '/denuncia');
    } */
    public function excluir()
    {
        $this->loginRequired();

        $id   = (int)($_POST['id'] ?? 0);
        $tipo = trim($_POST['tipo'] ?? '');
        $usuarioLogado = $_SESSION['usuario_logado'];

        if ($id <= 0) {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
            return;
        }

        $comentario = $this->repositorySql->buscarPorId($tipo, $id);
        if (!$comentario) {
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
            return;
        }

        if ($comentario['usuario_id'] == $usuarioLogado->getId() || $usuarioLogado->getRegra() === 'admin') {
            $this->repositorySql->desativar($id, $tipo);
        }

        // Volta exatamente para o Fórum ou Projeto de origem
        if ($tipo === 'forum' && !empty($comentario['postagem_forum_id'])) {
            $this->redirect(URL_BASE . '/forum/perfil?id=' . $comentario['postagem_forum_id']);
            return;
        }

        if ($tipo === 'projeto' && !empty($comentario['projeto_id'])) {
            $this->redirect(URL_BASE . '/projeto/perfil?id=' . $comentario['projeto_id']);
            return;
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE);
    }
}