<?php

namespace app\controllers;

use app\core\Controller;
use app\repositories\DenunciaRepositorySql;

class DenunciaController extends Controller
{
    private DenunciaRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new DenunciaRepositorySql();
    }

    public function listar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $data["denuncias"] = $this->repositorySql->listarAgrupadas();
        $this->view("denuncia/list", $data);
    }

    public function salvar()
    {
        $this->loginRequired();

        $tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
        $alvoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $motivo = trim(filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
        $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

        if ($tipo && $alvoId && $motivo) {
            $usuarioLogadoId = $_SESSION["usuario_logado"]->getId();
            $this->repositorySql->salvar($tipo, $alvoId, $usuarioLogadoId, $motivo, $descricao);
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/');
    }

    public function excluir()
    {
        $this->loginRequired();
        $this->adminRequired();

        $tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
        $alvoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($tipo && $alvoId) {
            $this->repositorySql->dispensar($tipo, $alvoId);
        }

        $this->redirect(URL_BASE . '/denuncia');
    }
}