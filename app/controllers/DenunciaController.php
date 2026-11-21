<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\repositories\DenunciaRepositorySql;

class DenunciaController extends Controller
{
    private DenunciaRepositorySql $repositorySql;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->repositorySql = new DenunciaRepositorySql();
        $this->validador = new ValidadorHelper();
    }

    public function validar()
    {
        $this->validador->tipoDenuncia("tipo", trim($_POST["tipo"] ?? ""));
        $this->validador->idPositivo("id", $_POST["id"] ?? "");
        $this->validador->obrigatorio("motivo", trim($_POST["motivo"] ?? ""), "Selecione o motivo da denúncia.");

        $descricao = trim($_POST["descricao"] ?? "");

        if ($descricao !== "")
        {
            $this->validador->tamanho("descricao", $descricao, 0, 2000, "detalhes");
        }
    }

    // Descartar denúncias: só tipo e id do conteúdo
    public function validarAlvo()
    {
        $this->validador->tipoDenuncia("tipo", trim($_POST["tipo"] ?? ""));
        $this->validador->idPositivo("id", $_POST["id"] ?? "");
    }

    public function listar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
        }

        $data["denuncias"] = $this->repositorySql->listarAgrupadas();
        $this->view("denuncia/list", $data);
    }

    public function salvar()
    {
        $this->loginRequired();

        $this->validar();

        if ($this->validador->temErros())
        {
            $erros = $this->validador->getErros();

            $this->responderAcao(false, reset($erros), 422, null, $erros);
        }

        $tipo = trim($_POST["tipo"]);
        $alvoId = (int)$_POST["id"];
        $motivo = trim(filter_input(INPUT_POST, "motivo", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
        $descricao = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");

        $usuarioLogadoId = $_SESSION["usuario_logado"]->getId();
        $this->repositorySql->salvar($tipo, $alvoId, $usuarioLogadoId, $motivo, $descricao);

        $this->responderAcao(true, "Denúncia enviada com sucesso. Obrigado por ajudar a comunidade!");
    }

    public function desativar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->responderAcao(false, "Acesso negado.", 403);
        }

        $this->validarAlvo();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Denúncia inválida.", 422, null, $this->validador->getErros());
        }

        $tipo = trim($_POST["tipo"]);
        $alvoId = (int)$_POST["id"];

        $this->repositorySql->dispensar($tipo, $alvoId);

        $this->responderAcao(true, "Denúncias descartadas com sucesso.");
    }
}
