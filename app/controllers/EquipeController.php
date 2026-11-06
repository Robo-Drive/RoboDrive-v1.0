<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Equipe;
use app\repositories\EquipeRepositorySql;
use app\repositories\ForumRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\EquipeService;

class EquipeController extends Controller
{
    private EquipeRepositorySql $repositorySql;
    private ForumRepositorySql $forumRepositorySql;
    private ProjetoRepositorySql $projetoRepositorySql;
    private EquipeService $service;

    public function __construct()
    {
        $this->service = new EquipeService();
        $this->repositorySql = new EquipeRepositorySql();
        $this->forumRepositorySql = new ForumRepositorySql();
        $this->projetoRepositorySql = new ProjetoRepositorySql();
    }

    // Verificar se o usuário é administrador
    private function ehAdmin(): bool
    {
        return isset($_SESSION['usuario_logado']) &&
            $_SESSION['usuario_logado']->getRegra() === 'admin';
    }

    // Verificar se o usuário pode gerenciar uma equipe
    private function podeGerenciarEquipe(int $equipeId): bool
    {
        if ($this->ehAdmin()) {
            return true;
        }

        $usuarioId = $_SESSION['usuario_logado']->getId();

        return $this->repositorySql->usuarioECoordenador(
            $equipeId,
            $usuarioId
        );
    }

    // Redirecionar para o perfil da equipe
    private function voltarPerfil(int $equipeId): void
    {
        $this->redirect(
            URL_BASE . '/equipe/perfil?id=' . $equipeId
        );
    }

    // Listar equipes
    public function listar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $data['equipes'] = $this->repositorySql->listarTodos();

        $this->view("equipe/list", $data);
    }

    // Formulário de cadastro
    public function cadastrar()
    {
        $this->loginRequired();

        $this->view("equipe/create");
    }

    // Salvar equipe
    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $nome = trim($_POST["nome"] ?? '');
        $descricao = trim($_POST["descricao"] ?? '');
        $senha = $_POST["senha"] ?? '';
        $confirmarSenha = $_POST["confirmar_senha"] ?? '';

        $validador->obrigatorio(
            'nome',
            $nome,
            'O nome da equipe é obrigatório'
        );

        $validador->tamanho(
            'nome',
            $nome,
            3,
            100,
            'Nome da equipe'
        );

        $validador->obrigatorio(
            'descricao',
            $descricao,
            'A descrição da equipe é obrigatória'
        );

        $validador->tamanho(
            'descricao',
            $descricao,
            3,
            2000,
            'Descrição da equipe'
        );

        $validador->obrigatorio(
            'senha',
            $senha,
            'A senha da equipe é obrigatória'
        );

        $validador->tamanho(
            'senha',
            $senha,
            6,
            100,
            'Senha da equipe'
        );

        if ($senha !== $confirmarSenha) {
            $validador->obrigatorio(
                'confirmar_senha',
                '',
                'As senhas não conferem'
            );
        }

        $posts = [
            "nome" => $nome,
            "descricao" => $descricao,
            "senha" => $senha
        ];

        if ($validador->temErros()) {

            $data["equipe"] = $posts;
            $data["erros"] = $validador->getErros();

            $this->view('equipe/create', $data);
            return;
        }

        $equipe = Equipe::map([$posts])[0];

        if ($this->service->salvarEquipe($equipe)) {

            $this->redirect(URL_BASE . '/usuario/perfil');
            return;
        }

        $data["equipe"] = $posts;
        $data["erros"]["equipe"] =
            "Não foi possível cadastrar a equipe. Verifique os dados e tente novamente.";

        $this->view('equipe/create', $data);
    }

    // Alterar papel de um membro
    public function alterarPapel()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        );

        $usuarioId = filter_input(
            INPUT_POST,
            'usuario_id',
            FILTER_VALIDATE_INT
        );

        $papel = trim($_POST['papel'] ?? '');

        if (!$equipeId || !$usuarioId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!in_array(
            $papel,
            ['coordenador', 'participante'],
            true
        )) {
            $this->voltarPerfil($equipeId);
            return;
        }

        if (!$this->podeGerenciarEquipe($equipeId)) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $usuarioLogadoId = $_SESSION['usuario_logado']->getId();

        // Coordenador não pode alterar o próprio papel.
        if (
            $usuarioId === $usuarioLogadoId &&
            !$this->ehAdmin()
        ) {
            $this->voltarPerfil($equipeId);
            return;
        }

        // Impedir que a equipe fique sem coordenador.
        $membros = $this->repositorySql->listarMembros($equipeId);

        $coordenadores = array_filter(
            $membros,
            fn($membro) => $membro['papel'] === 'coordenador'
        );

        $membroAlvo = null;

        foreach ($membros as $membro) {
            if ((int)$membro['id'] === $usuarioId) {
                $membroAlvo = $membro;
                break;
            }
        }

        if (!$membroAlvo) {
            $this->voltarPerfil($equipeId);
            return;
        }

        if (
            $membroAlvo['papel'] === 'coordenador' &&
            $papel === 'participante' &&
            count($coordenadores) <= 1
        ) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $this->repositorySql->atualizarPapelMembro(
            $equipeId,
            $usuarioId,
            $papel
        );

        $this->voltarPerfil($equipeId);
    }

    // Formulário de edição
    public function editar()
    {
        $this->loginRequired();

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!$this->podeGerenciarEquipe($id)) {
            $this->voltarPerfil($id);
            return;
        }

        $equipe = new Equipe();
        $equipe->setId($id);

        $equipeEncontrada = $this->repositorySql->buscarId($equipe);

        if (!$equipeEncontrada) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        $data["equipe"] = $equipeEncontrada;

        $this->view("equipe/edit", $data);
    }

    // Atualizar equipe
    public function atualizar()
    {
        $this->loginRequired();

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!$this->podeGerenciarEquipe($id)) {
            $this->voltarPerfil($id);
            return;
        }

        $nome = trim($_POST["nome"] ?? '');
        $senha = $_POST["senha"] ?? '';
        $descricao = trim($_POST["descricao"] ?? '');

        $validador = new ValidadorHelper();

        $validador->obrigatorio(
            'nome',
            $nome,
            'O nome da equipe é obrigatório'
        );

        $validador->tamanho(
            'nome',
            $nome,
            3,
            100,
            'Nome da equipe'
        );

        if ($senha !== '') {
            $validador->tamanho(
                'senha',
                $senha,
                6,
                100,
                'Senha da equipe'
            );
        }

        $posts = [
            "id" => $id,
            "nome" => htmlspecialchars(
                $nome,
                ENT_QUOTES,
                'UTF-8'
            ),
            "descricao" => htmlspecialchars(
                $descricao,
                ENT_QUOTES,
                'UTF-8'
            ),
            "senha" => $senha !== '' ? $senha : null
        ];

        if ($validador->temErros()) {

            $data["equipe"] = $posts;
            $data["erros"] = $validador->getErros();

            $this->view('equipe/edit', $data);
            return;
        }

        $equipe = Equipe::map([$posts])[0];

        if ($this->service->editarEquipe($equipe)) {

            $this->voltarPerfil($id);
            return;
        }

        $data["equipe"] = $posts;
        $data["erros"]["equipe"] =
            "Não foi possível atualizar a equipe.";

        $this->view('equipe/edit', $data);
    }

    // Perfil da equipe
    public function perfil()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$equipeId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        $equipe = new Equipe();
        $equipe->setId($equipeId);

        $equipeEncontrada = $this->repositorySql->buscarId($equipe);

        if (!$equipeEncontrada) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        $usuarioLogadoId = $_SESSION['usuario_logado']->getId();

        $papelUsuario = $this->repositorySql->buscarPapelUsuario(
            $equipeId,
            $usuarioLogadoId
        );

        $isAdmin = $this->ehAdmin();

        $data["equipe"] = $equipeEncontrada;

        $data["papelUsuario"] = $papelUsuario;

        $data["isAdmin"] = $isAdmin;

        $data["isCoordenador"] =
            $papelUsuario === 'coordenador';

        $data["isMembro"] =
            $papelUsuario !== null;

        $data["podeGerenciar"] =
            $isAdmin || $papelUsuario === 'coordenador';

        $data["usuarios"] =
            $this->repositorySql->buscarUsuariosEquipe($equipeId);

        $data["todosUsuarios"] =
            (new UsuarioRepositorySql())->listarTodos();

        $data["solicitacoesPendentes"] =
            $data["podeGerenciar"]
                ? $this->repositorySql->listarSolicitacoesPendentes($equipeId)
                : [];

        $data["membros"] =
            $this->repositorySql->listarMembros($equipeId);

        $data["projetos"] =
            $this->projetoRepositorySql->buscarEquipe($equipeId);

        $data["postsForum"] =
            $this->forumRepositorySql->buscarEquipe($equipeId);

        $this->view("equipe/perfil", $data);
    }

    // Excluir equipe
    public function excluir()
    {
        $this->loginRequired();
        $this->adminRequired();

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        $equipe = new Equipe();
        $equipe->setId($id);

        $this->repositorySql->deletar($equipe);

        $this->redirect(URL_BASE . '/equipe/listar');
    }

    // Desativar equipe
    public function desativar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        $equipe = new Equipe();
        $equipe->setId($id);

        $this->repositorySql->deletar($equipe);

        $this->redirect(URL_BASE . '/equipe/listar');
    }

    // Adicionar membro
    public function adicionarMembro()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        );

        $usuarioId = filter_input(
            INPUT_POST,
            'usuario_id',
            FILTER_VALIDATE_INT
        );

        $papel = trim($_POST['papel'] ?? 'participante');

        if (!$equipeId || !$usuarioId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!in_array(
            $papel,
            ['coordenador', 'participante'],
            true
        )) {
            $papel = 'participante';
        }

        if (!$this->podeGerenciarEquipe($equipeId)) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $this->repositorySql->adicionarMembro(
            $equipeId,
            $usuarioId,
            $papel
        );

        $this->voltarPerfil($equipeId);
    }

    // Remover membro
    public function removerMembro()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        );

        $usuarioId = filter_input(
            INPUT_POST,
            'usuario_id',
            FILTER_VALIDATE_INT
        );

        if (!$equipeId || !$usuarioId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!$this->podeGerenciarEquipe($equipeId)) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $usuarioLogadoId = $_SESSION['usuario_logado']->getId();

        // Impedir que o coordenador remova a si mesmo.
        if (
            $usuarioId === $usuarioLogadoId &&
            !$this->ehAdmin()
        ) {
            $this->voltarPerfil($equipeId);
            return;
        }

        // Verificar se o usuário alvo é coordenador.
        $membros = $this->repositorySql->listarMembros($equipeId);

        foreach ($membros as $membro) {

            if ((int)$membro['id'] === $usuarioId) {

                // Não permitir remover coordenadores por este método.
                // O rebaixamento deve ser feito por alterarPapel.

                if ($membro['papel'] === 'coordenador') {
                    $this->voltarPerfil($equipeId);
                    return;
                }

                break;
            }
        }

        $this->repositorySql->removerMembro(
            $equipeId,
            $usuarioId
        );

        $this->voltarPerfil($equipeId);
    }

    // Formulário para entrar em equipe
    public function formularioEntrar()
    {
        $this->loginRequired();

        $this->view('equipe/entrar');
    }

    // Solicitar entrada em equipe
    public function entrar()
    {
        $this->loginRequired();

        $hash = trim($_POST['hash'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $erros = [];

        if ($hash === '') {
            $erros['hash'] = 'O código Hash é obrigatório.';
        }

        if ($senha === '') {
            $erros['senha'] = 'A senha da equipe é obrigatória.';
        }

        if (!empty($erros)) {

            $this->view('equipe/entrar', [
                'erros' => $erros,
                'hash' => $hash
            ]);

            return;
        }

        $equipe = $this->repositorySql->buscarPorHash($hash);

        if (!$equipe) {

            $erros['geral'] = 'Código Hash não encontrado.';

            $this->view('equipe/entrar', [
                'erros' => $erros,
                'hash' => $hash
            ]);

            return;
        }

        if (!password_verify($senha, $equipe->getSenha())) {

            $erros['senha'] =
                'Senha incorreta para esta equipe.';

            $this->view('equipe/entrar', [
                'erros' => $erros,
                'hash' => $hash
            ]);

            return;
        }

        $usuarioLogadoId = $_SESSION['usuario_logado']->getId();

        $solicitou = $this->repositorySql->solicitarEntrada(
            $equipe->getId(),
            $usuarioLogadoId
        );

        if (!$solicitou) {

            $erros['geral'] =
                'Você já é um membro ativo desta equipe ou não foi possível enviar a solicitação.';

            $this->view('equipe/entrar', [
                'erros' => $erros,
                'hash' => $hash
            ]);

            return;
        }

        $this->view('equipe/entrar', [
            'sucesso' =>
                'Solicitação enviada com sucesso! Aguarde a aprovação do coordenador da equipe.'
        ]);
    }

    // Aprovar solicitação
    public function aprovarSolicitacao()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        );

        $usuarioId = filter_input(
            INPUT_POST,
            'usuario_id',
            FILTER_VALIDATE_INT
        );

        if (!$equipeId || !$usuarioId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!$this->podeGerenciarEquipe($equipeId)) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $this->repositorySql->aprovarSolicitacao(
            $equipeId,
            $usuarioId
        );

        $this->voltarPerfil($equipeId);
    }

    // Rejeitar solicitação
    public function rejeitarSolicitacao()
    {
        $this->loginRequired();

        $equipeId = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        );

        $usuarioId = filter_input(
            INPUT_POST,
            'usuario_id',
            FILTER_VALIDATE_INT
        );

        if (!$equipeId || !$usuarioId) {
            $this->redirect(URL_BASE . '/equipe/listar');
            return;
        }

        if (!$this->podeGerenciarEquipe($equipeId)) {
            $this->voltarPerfil($equipeId);
            return;
        }

        $this->repositorySql->rejeitarSolicitacao(
            $equipeId,
            $usuarioId
        );

        $this->voltarPerfil($equipeId);
    }
}
