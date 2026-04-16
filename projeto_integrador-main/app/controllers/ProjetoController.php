<?php

namespace app\controllers;

use DateTimeImmutable;
use app\core\Controller;
use app\models\Projeto;
use app\services\ProjetoService;

class ProjetoController extends Controller
{
    private ProjetoService $service;

    public function __construct()
    {
        $this->service = new ProjetoService();
    }

    public function listarTodos()
    {

        $data['lista'] = $this->service->getProjetos();
        $this->view('projetos/projetos_list', $data);
    }

    public function verProjeto()
    {

        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/projetos');
        }

        $id = $_GET['id'];

        $data['projeto'] = $this->service->getProjeto($id);

        $this->view('projetos/projetos_show', $data);
    }

    public function criar()
    {
        $this->view('projetos/projetos_create', []);
    }

    public function salvar()
    {
        $nome = $_POST['nome'];
        $visibilidade = $_POST['visibilidade'];

        $erros = [];

        $erros = $this->validacao();

        if (!empty($erros)) {
            $projeto = [
                'nome' => $nome,
                'visibilidade' => $visibilidade,
            ];

            $this->view('projetos/projetos_create', [
                'projeto' => $projeto,
                'erros' => $erros,
            ]);
            return;
        }

        $projeto = new Projeto();

        $projeto->setNome($nome);
        $projeto->setVisibilidade($visibilidade);

        $this->service->saveProjeto($projeto);

        $this->redirect(URL_BASE . '/projetos');
    }

    public function editar()
    {
        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/projetos');
        }

        $id = $_GET['id'];

        $data['projeto'] = $this->service->getProjeto($id);

        $this->view('projetos/projetos_edit', $data);
    }

    public function atualizar()
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $visibilidade = $_POST['visibilidade'];

        $erros = $this->validacao();

        if (!empty($erros)) {
            $projeto = [
                'id' => $id,
                'nome' => $nome,
                'visibilidade' => $visibilidade,
            ];

            $this->view('projetos/projetos_edit', [
                'projeto' => $projeto,
                'erros' => $erros,
            ]);
            return;
        }

        $projeto = new Projeto();

        $projeto->setNome($nome);
        $projeto->setVisibilidade($visibilidade);

        $this->service->updateProjeto($id, $projeto);

        $this->redirect(URL_BASE . '/projetos');
    }

    public function validacao()
    {
        $nome = $_POST['nome'];
        $erros = [];

        if (trim($nome) === '') {
            $erros['nome'] = 'O nome do projeto é obrigatório.';
        }

        if (mb_strlen($nome) > 100) {
            $erros['nome'] = 'O nome do projeto deve ter no máximo 100 caracteres.';
        }

        if ($this->service->nomeJaExiste($nome)) {
            $erros['nome'] = 'Já existe um projeto com este nome.';
        }

        return $erros;
    }

    public function excluir()
    {
        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/projetos');
        }

        $id = $_GET['id'];

        $this->service->deleteProjeto($id);

        $this->redirect(URL_BASE . '/projetos');
    }
}