<?php

namespace app\controllers;

use app\core\Controller;
use app\services\EquipeService;

class EquipeController extends Controller
{
    private EquipeService $service;

    public function __construct()
    {
        $this->service = new EquipeService();
    }

    /**
     * Listar todas as equipes (acesso público)
     */
    public function listAll()
    {
        $data['equipes'] = $this->service->getEquipes();
        $this->view('equipes/equipe_list', $data);
    }

    /**
     * Exibir detalhes de uma equipe (acesso público)
     */
    public function show()
    {
        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $id = (int)$_GET['id'];
        $equipe = $this->service->getEquipeById($id);

        if (!$equipe) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $data['equipe'] = $equipe;
        $this->view('equipes/equipe_show', $data);
    }

    /**
     * Exibir formulário de criação (requer autenticação)
     */
    public function create()
    {
        $this->authRequired();
        $this->professorRequired();
        $this->view('equipes/equipe_create', []);
    }

    /**
     * Salvar nova equipe (requer autenticação de professor)
     */
    public function save()
    {
        $this->authRequired();
        $this->professorRequired();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(URL_BASE . '/equipes');
        }

        $nomeEquipe = trim($_POST['nome_equipe'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $usuario = $this->getLoggedUser();
        $professorId = $usuario->getId();

        $resultado = $this->service->saveEquipe($nomeEquipe, $senha, $professorId);

        if ($resultado['sucesso']) {
            $this->redirect(URL_BASE . '/equipes');
        } else {
            $this->view('equipes/equipe_create', [
                'erros' => $resultado['erros'],
                'nome_equipe' => $nomeEquipe
            ]);
        }
    }

    /**
     * Exibir formulário de edição (requer autenticação)
     */
    public function edit()
    {
        $this->authRequired();
        $this->professorRequired();

        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $id = (int)$_GET['id'];
        $equipe = $this->service->getEquipeById($id);

        if (!$equipe) {
            $this->redirect(URL_BASE . '/equipes');
        }

        // Verificar se o professor é o dono da equipe
        $usuario = $this->getLoggedUser();
        if ($equipe['professor_id'] !== $usuario->getId()) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $data['equipe'] = $equipe;
        $this->view('equipes/equipe_edit', $data);
    }

    /**
     * Atualizar equipe (requer autenticação)
     */
    public function update()
    {
        $this->authRequired();
        $this->professorRequired();

        if (!isset($_POST['id'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $id = (int)$_POST['id'];
        $equipe = $this->service->getEquipeById($id);

        if (!$equipe) {
            $this->redirect(URL_BASE . '/equipes');
        }

        // Verificar se o professor é o dono da equipe
        $usuario = $this->getLoggedUser();
        if ($equipe['professor_id'] !== $usuario->getId()) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $nomeEquipe = trim($_POST['nome_equipe'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $resultado = $this->service->updateEquipe($id, $nomeEquipe, $senha);

        if ($resultado['sucesso']) {
            $this->redirect(URL_BASE . '/equipes/show?id=' . $id);
        } else {
            $this->view('equipes/equipe_edit', [
                'equipe' => $equipe,
                'erros' => $resultado['erros']
            ]);
        }
    }

    /**
     * Deletar equipe (requer autenticação)
     */
    public function delete()
    {
        $this->authRequired();
        $this->professorRequired();

        if (!isset($_GET['id'])) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $id = (int)$_GET['id'];
        $equipe = $this->service->getEquipeById($id);

        if (!$equipe) {
            $this->redirect(URL_BASE . '/equipes');
        }

        // Verificar se o professor é o dono da equipe
        $usuario = $this->getLoggedUser();
        if ($equipe['professor_id'] !== $usuario->getId()) {
            $this->redirect(URL_BASE . '/equipes');
        }

        $resultado = $this->service->deleteEquipe($id);

        $this->redirect(URL_BASE . '/equipes');
    }
}
