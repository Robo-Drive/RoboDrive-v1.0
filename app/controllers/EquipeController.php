<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Equipe;
use app\repositories\EquipeRepositorySql;
use app\repositories\ForumRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\services\EquipeService;

class EquipeController extends Controller
{
    private EquipeRepositorySql $repositorySql;
    private ForumRepositorySql $forumRepositorySql;
    private ProjetoRepositorySql $projetoRepositorySql;
    private EquipeService $service;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->service = new EquipeService();
        $this->repositorySql = new EquipeRepositorySql();
        $this->forumRepositorySql = new ForumRepositorySql();
        $this->projetoRepositorySql = new ProjetoRepositorySql();
        $this->validador = new ValidadorHelper();
    }

    private function usuarioEhAdmin(): bool
    {
        return isset($_SESSION["usuario_logado"]) && $_SESSION["usuario_logado"]->getRegra() === "admin";
    }

    private function usuarioEhCoordenadorOuAdmin(int $equipeId): bool
    {
        if ($this->usuarioEhAdmin())
        {
            return true;
        }

        if (!isset($_SESSION["usuario_logado"]))
        {
            return false;
        }

        return $this->repositorySql->usuarioECoordenador($equipeId, $_SESSION["usuario_logado"]->getId());
    }

    private function usuarioEhMembroOuAdmin(int $equipeId): bool
    {
        if ($this->usuarioEhAdmin())
        {
            return true;
        }

        if (!isset($_SESSION["usuario_logado"]))
        {
            return false;
        }

        return $this->repositorySql->usuarioPertenceAEquipe($equipeId, $_SESSION["usuario_logado"]->getId());
    }

    public function listar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
        }

        $status = $_GET["status"] ?? "1";
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");

        $data["statusFiltro"] = $status;
        $data["busca_atual"] = $busca;
        $data["equipes"] = ($status === "todos")
            ? $this->repositorySql->listarAdminTodos($busca)
            : $this->repositorySql->listarPorStatus($status === "1", $busca);

        $this->view("equipe/list", $data);
    }

    public function cadastrar()
    {
        $this->loginRequired();

        $this->view("equipe/create");
    }
    public function validar(bool $editar = false)
    {
        if ($editar)
        {
            $this->validador->idPositivo("id", $_POST["id"] ?? "");
        }

        $this->validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome da equipe é obrigatório.");
        if (!isset($this->validador->getErros()["nome"]))
        {
            $this->validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "nome da equipe");
        }

        $this->validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição da equipe é obrigatória.");
        if (!isset($this->validador->getErros()["descricao"]))
        {
            $this->validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "descrição");
        }

        $senha = trim($_POST["senha"] ?? "");

        if (!$editar)
        {
            $this->validador->obrigatorio("senha", $senha, "A senha de entrada é obrigatória.");
        }

        if ($senha !== "")
        {
            $this->validador->tamanho("senha", $senha, 8, 100, "senha");
            $this->validador->confirmarValor($senha, trim($_POST["confirmar_senha"] ?? ""), "confirmar_senha", "As senhas não são iguais.");
        }
    }

    public function validarMembro(bool $comPapel = false)
    {
        $this->validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $this->validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");

        if ($comPapel)
        {
            $this->validador->papel(trim($_POST["papel"] ?? "participante"));
        }
    }

    public function validarAcesso(string $hash)
    {
        $this->validador->obrigatorio("hash", $hash, "Informe o Hash de acesso da equipe.");
        $this->validador->obrigatorio("senha", trim($_POST["senha"] ?? ""), "Informe a senha da equipe.");
    }

    public function salvar()
    {
        $this->loginRequired();
        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        try
        {
            $equipe = new Equipe();
            $equipe->setNome(trim($_POST["nome"]));
            $equipe->setDescricao(trim($_POST["descricao"]));
            $equipe->setSenha(trim($_POST["senha"]));

            $equipeCriada = $this->service->salvarEquipe($equipe);

            if (!$equipeCriada)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível criar a equipe."], 500);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Equipe criada com sucesso!",
                "redirect" => URL_BASE . "/equipe/perfil?id=" . $equipeCriada->getId()
            ]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao cadastrar equipe."], 500);
        }
    }

    public function alterarPapel()
    {
        $this->loginRequired();
        $this->validarMembro(true);

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["equipe_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem alterar papéis."], 403);
        }

        $resultado = $this->service->alterarPapel($equipeId, $usuarioId, trim($_POST["papel"]));

        if (!$resultado["resposta"])
        {
            $this->responderAcao(false, $resultado["erro"], $resultado["status"]);
        }

        $this->responderAcao(true, "Papel atualizado com sucesso.");
    }

    public function editar()
    {
        $this->loginRequired();

        $equipeId = $_GET["id"] ?? $_POST["id"] ?? "";

        $this->validador->idPositivo("id", $equipeId);

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . "/admin/equipe/listar");
        }

        $equipeId = (int)$equipeId;

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->redirect(URL_BASE . "/equipe/perfil?id=" . $equipeId);
        }

        $equipe = new Equipe();
        $equipe->setId($equipeId);

        $equipeExistente = $this->repositorySql->buscarId($equipe);

        if (!$equipeExistente)
        {
            $this->redirect(URL_BASE . "/admin/equipe/listar");
        }

        $data["equipe"] = $equipeExistente;

        $this->view("equipe/edit", $data);
    }

    public function atualizar()
    {
        $this->loginRequired();
        $this->validar(true);

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["id"];

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Você não tem permissão para editar esta equipe."], 403);
        }

        try
        {
            $equipe = new Equipe();
            $equipe->setId($equipeId);
            $equipe->setNome(trim($_POST["nome"]));
            $equipe->setDescricao(trim($_POST["descricao"]));

            if (trim($_POST["senha"] ?? "") !== "")
            {
                $equipe->setSenha(trim($_POST["senha"]));
            }

            $atualizada = $this->service->editarEquipe($equipe);

            if (!$atualizada)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível atualizar a equipe."], 500);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Equipe atualizada com sucesso!",
                "redirect" => URL_BASE . "/equipe/perfil?id=" . $equipeId
            ]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao atualizar equipe."], 500);
        }
    }

    public function perfil()
    {
        $this->loginRequired();

        $equipeId = $_GET["id"] ?? "";

        $this->validador->idPositivo("id", $equipeId);

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . "/admin/equipe/listar");
        }

        $equipeId = (int)$equipeId;

        $equipe = new Equipe();
        $equipe->setId($equipeId);

        $equipeExistente = $this->repositorySql->buscarId($equipe, $this->usuarioEhAdmin());

        if (!$equipeExistente)
        {
            $this->redirect(URL_BASE . "/admin/equipe/listar");
        }

        if (!$this->usuarioEhMembroOuAdmin($equipeId))
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
            return;
        }

        $termoBuscaProjeto = $_GET["busca_projeto"] ?? null;

        $data["equipe"] = $equipeExistente;
        $data["usuarios"] = $this->repositorySql->buscarUsuariosEquipe($equipeId);
        $data["membros"] = $this->repositorySql->listarMembros($equipeId);
        $data["projetos"] = $this->projetoRepositorySql->buscarEquipe($equipeId, $termoBuscaProjeto);
        $data["foruns"] = $this->forumRepositorySql->buscarEquipe($equipeId);
        $solicitacoesPendentes = $this->usuarioEhCoordenadorOuAdmin($equipeId)
            ? $this->repositorySql->listarSolicitacoesPendentes($equipeId)
            : [];
        $data["solicitacoes"] = $solicitacoesPendentes;
        $data["solicitacoesPendentes"] = $solicitacoesPendentes;

        if ($this->requisicaoAjax() && isset($_GET["busca_projeto"]))
        {
            $this->responderJson(["sucesso" => true, "projetos" => $data["projetos"]]);
        }

        $this->view("equipe/perfil", $data);
    }

    public function desativar()
    {
        $this->loginRequired();

        $this->validador->idPositivo("id", $_POST["id"] ?? "");

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["id"];

        $ehCoordenador = $this->repositorySql->usuarioECoordenador($equipeId, $_SESSION["usuario_logado"]->getId());

        if (!$this->usuarioEhAdmin() && !$ehCoordenador)
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Você não tem permissão para desativar esta equipe."], 403);
        }

        // Justificativa só quando um admin (que não coordena a equipe) desativa a equipe de outros usuários
        if ($this->usuarioEhAdmin() && !$ehCoordenador)
        {
            $erroJustificativa = $this->erroJustificativa();

            if ($erroJustificativa)
            {
                $this->responderErroJustificativa($erroJustificativa);
            }
        }

        try
        {
            $sucesso = $this->repositorySql->desativar($equipeId);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível desativar a equipe."], 500);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Equipe desativada com sucesso.",
                "redirect" => URL_BASE . "/admin/equipe/listar"
            ]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao desativar equipe."], 500);
        }
    }

    public function adicionarMembro()
    {
        $this->loginRequired();
        $this->validarMembro(true);

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["equipe_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem adicionar membros."], 403);
        }

        $resultado = $this->service->adicionarMembro($equipeId, $usuarioId, trim($_POST["papel"] ?? "participante"));

        if (!$resultado["resposta"])
        {
            $this->responderAcao(false, $resultado["erro"], $resultado["status"]);
        }

        $this->responderAcao(true, "Membro adicionado com sucesso.");
    }

    public function removerMembro()
    {
        $this->loginRequired();
        $this->validarMembro();

        $novoCoordenador = $_POST["novo_coordenador_id"] ?? "";

        if ($novoCoordenador !== "")
        {
            $this->validador->idPositivo("novo_coordenador_id", $novoCoordenador);
        }

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["equipe_id"];
        $usuarioAlvoId = (int)$_POST["usuario_id"];
        $ehProprioUsuario = ((int)$_SESSION["usuario_logado"]->getId() === $usuarioAlvoId);

        if (!$ehProprioUsuario && !$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Você não tem permissão para remover este membro."], 403);
        }

        $resultado = $this->service->removerMembro($equipeId, $usuarioAlvoId, $novoCoordenador !== "" ? (int)$novoCoordenador : null);

        if (!$resultado["resposta"])
        {
            $this->responderAcao(false, $resultado["erro"], $resultado["status"]);
        }

        $this->responderAcao(
            true,
            $ehProprioUsuario ? "Você saiu da equipe." : "Membro removido com sucesso.",
            200,
            $ehProprioUsuario ? URL_BASE . "/usuario/perfil" : null
        );
    }

    public function formularioEntrar()
    {
        $this->loginRequired();

        $this->view("equipe/entrar");
    }

    public function entrar()
    {
        $this->loginRequired();

        $hash = trim($_POST["hash"] ?? ($_POST["hash_acesso"] ?? ""));

        $this->validarAcesso($hash);

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        try
        {
            $equipe = $this->repositorySql->buscarPorHash($hash);

            if (!$equipe)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Equipe não encontrada para o Hash informado."], 404);
            }

            if (!$this->service->validarSenhaEquipe($equipe, trim($_POST["senha"])))
            {
                $this->responderJson(["sucesso" => false, "erros" => ["senha" => "Senha incorreta para esta equipe."]], 422);
            }

            $sucesso = $this->service->solicitarEntradaEquipe($equipe->getId(), $_SESSION["usuario_logado"]->getId());

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Você já possui solicitação ou já faz parte da equipe."], 422);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Solicitação de entrada enviada com sucesso! Aguarde a aprovação do coordenador.",
                "redirect" => URL_BASE . "/usuario/perfil"
            ]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao solicitar entrada na equipe."], 500);
        }
    }

    public function aprovarSolicitacao()
    {
        $this->loginRequired();

        $this->validarMembro();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["equipe_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem aprovar solicitações."], 403);
        }

        try
        {
            $sucesso = $this->service->aprovarSolicitacao($equipeId, $usuarioId);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível aprovar a solicitação."], 500);
            }

            $this->responderJson(["sucesso" => true, "mensagem" => "Solicitação aprovada com sucesso."]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao aprovar solicitação."], 500);
        }
    }

    public function rejeitarSolicitacao()
    {
        $this->loginRequired();

        $this->validarMembro();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $equipeId = (int)$_POST["equipe_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem rejeitar solicitações."], 403);
        }

        try
        {
            $sucesso = $this->service->rejeitarSolicitacao($equipeId, $usuarioId);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível rejeitar a solicitação."], 500);
            }

            $this->responderJson(["sucesso" => true, "mensagem" => "Solicitação rejeitada com sucesso."]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao rejeitar solicitação."], 500);
        }
    }

    public function ativar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->responderAcao(false, "Acesso negado.", 403);
        }

        $this->validador->idPositivo("id", $_POST["id"] ?? "");

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Equipe inválida.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];

        $equipeBusca = new Equipe();
        $equipeBusca->setId($id);
        $item = $this->repositorySql->buscarId($equipeBusca, true);

        if (!$item)
        {
            $this->responderAcao(false, "Equipe não encontrada.", 404);
        }

        if ($item->isStatus() !== false)
        {
            $this->responderAcao(false, "Equipe já está ativa.", 422);
        }

        $this->repositorySql->ativar($id);

        $this->responderAcao(true, "Equipe reativada com sucesso.");
    }
}
