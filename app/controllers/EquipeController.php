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

    public function __construct()
    {
        $this->service = new EquipeService();
        $this->repositorySql = new EquipeRepositorySql();
        $this->forumRepositorySql = new ForumRepositorySql();
        $this->projetoRepositorySql = new ProjetoRepositorySql();
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

    private function validarId(string $campo, mixed $valor): int|false
    {
        $id = filter_var($valor, FILTER_VALIDATE_INT);

        if ($id === false || $id <= 0)
        {
            return false;
        }

        return $id;
    }

    public function listar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $status = $_GET["status"] ?? "1";

        $data["statusFiltro"] = $status;
        $data["equipes"] = ($status === "todos")
            ? $this->repositorySql->listarAdminTodos()
            : $this->repositorySql->listarPorStatus($status === "1");

        $this->view("equipe/list", $data);
    }

    public function cadastrar()
    {
        $this->loginRequired();

        $this->view("equipe/create");
    }

    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome da equipe é obrigatório.");
        $validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "Nome da equipe");

        $validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição da equipe é obrigatória.");
        $validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "Descrição");

        $validador->obrigatorio("senha", trim($_POST["senha"] ?? ""), "A senha de entrada é obrigatória.");
        $validador->tamanho("senha", trim($_POST["senha"] ?? ""), 8, 100, "Senha");

        $validador->confirmarValor(trim($_POST["senha"] ?? ""), trim($_POST["confirmar_senha"] ?? ""), "confirmar_senha", "As senhas não são iguais.");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
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

        $validador = new ValidadorHelper();

        $validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");
        $validador->papelEquipe(trim($_POST["papel"] ?? ""));

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("equipe_id", $_POST["equipe_id"]);
        $usuarioId = $this->validarId("usuario_id", $_POST["usuario_id"]);
        $papel = trim($_POST["papel"]);

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem alterar papéis."], 403);
        }

        try
        {
            $membros = $this->repositorySql->listarMembros($equipeId);
            $totalCoordenadores = 0;
            $papelAtualUsuario = null;

            foreach ($membros as $m)
            {
                if ($m["papel"] === "coordenador")
                {
                    $totalCoordenadores++;
                }

                if ($m["id"] == $usuarioId)
                {
                    $papelAtualUsuario = $m["papel"];
                }
            }

            if ($papelAtualUsuario === null)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Integrante não encontrado na equipe."], 404);
            }

            if ($papelAtualUsuario === "coordenador" && $papel !== "coordenador" && $totalCoordenadores <= 1)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "A equipe não pode ficar sem nenhum coordenador."], 422);
            }

            $sucesso = $this->repositorySql->alterarPapelMembro($equipeId, $usuarioId, $papel);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível alterar o papel do integrante."], 500);
            }

            $this->responderJson(["sucesso" => true, "mensagem" => "Papel atualizado com sucesso."]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao alterar o papel do integrante."], 500);
        }
    }

    public function editar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        $equipeId = $_GET["id"] ?? $_POST["id"] ?? "";

        $validador->idPositivo("id", $equipeId);

        if ($validador->temErros())
        {
            $this->redirect(URL_BASE . "/equipe/listar");
        }

        $equipeId = $this->validarId("id", $equipeId);

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->redirect(URL_BASE . "/equipe/perfil?id=" . $equipeId);
        }

        $equipe = new Equipe();
        $equipe->setId($equipeId);

        $equipeExistente = $this->repositorySql->buscarId($equipe);

        if (!$equipeExistente)
        {
            $this->redirect(URL_BASE . "/equipe/listar");
        }

        $data["equipe"] = $equipeExistente;

        $this->view("equipe/edit", $data);
    }

    public function atualizar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $validador->idPositivo("id", $_POST["id"] ?? "");

        $validador->obrigatorio("nome", trim($_POST["nome"] ?? ""), "O nome da equipe é obrigatório.");
        $validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "Nome da equipe");

        $validador->obrigatorio("descricao", trim($_POST["descricao"] ?? ""), "A descrição da equipe é obrigatória.");
        $validador->tamanho("descricao", trim($_POST["descricao"] ?? ""), 3, 2000, "Descrição");

        if (($_POST["senha"] ?? "") !== "")
        {
            $validador->tamanho("senha", trim($_POST["senha"]), 8, 100, "Senha");
            $validador->confirmarValor(trim($_POST["senha"]), trim($_POST["confirmar_senha"] ?? ""), "confirmar_senha", "As senhas não são iguais.");
        }

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("id", $_POST["id"]);

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

            if (($_POST["senha"] ?? "") !== "")
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

        $validador = new ValidadorHelper();
        $equipeId = $_GET["id"] ?? "";

        $validador->idPositivo("id", $equipeId);

        if ($validador->temErros())
        {
            $this->redirect(URL_BASE . "/equipe/listar");
        }

        $equipeId = $this->validarId("id", $equipeId);

        $equipe = new Equipe();
        $equipe->setId($equipeId);

        $equipeExistente = $this->repositorySql->buscarId($equipe);

        if (!$equipeExistente)
        {
            $this->redirect(URL_BASE . "/equipe/listar");
        }

        // RN: Apenas integrantes ativos (ou admin) podem acessar o perfil da equipe
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
        // Apenas o coordenador da equipe (ou admin) pode visualizar as solicitações pendentes
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
        $this->adminRequired();

        $validador = new ValidadorHelper();
        $validador->idPositivo("id", $_POST["id"] ?? "");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("id", $_POST["id"]);

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
                "redirect" => URL_BASE . "/equipe/listar"
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

        $validador = new ValidadorHelper();

        $validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");
        $validador->papelEquipe(trim($_POST["papel"] ?? "participante"));

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("equipe_id", $_POST["equipe_id"]);
        $usuarioId = $this->validarId("usuario_id", $_POST["usuario_id"]);
        $papel = trim($_POST["papel"] ?? "participante");

        if (!$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Apenas coordenadores podem adicionar membros."], 403);
        }

        try
        {
            $sucesso = $this->repositorySql->adicionarMembroDireto($equipeId, $usuarioId, $papel);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível adicionar o membro."], 500);
            }

            $this->responderJson(["sucesso" => true, "mensagem" => "Membro adicionado com sucesso."]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao adicionar membro."], 500);
        }
    }

    public function removerMembro()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("equipe_id", $_POST["equipe_id"]);
        $usuarioAlvoId = $this->validarId("usuario_id", $_POST["usuario_id"]);
        $usuarioLogadoId = $_SESSION["usuario_logado"]->getId();

        $ehProprioUsuario = ($usuarioLogadoId == $usuarioAlvoId);

        if (!$ehProprioUsuario && !$this->usuarioEhCoordenadorOuAdmin($equipeId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Você não tem permissão para remover este membro."], 403);
        }
        

        try
        {
            $membros = $this->repositorySql->listarMembros($equipeId);
            $totalMembros = count($membros);
            $totalCoordenadores = 0;
            $alvoEraCoordenador = false;
            $alvoEncontrado = false;

            foreach ($membros as $m)
            {
                if ($m["papel"] === "coordenador")
                {
                    $totalCoordenadores++;
                }

                if ($m["id"] == $usuarioAlvoId)
                {
                    $alvoEncontrado = true;

                    if ($m["papel"] === "coordenador")
                    {
                        $alvoEraCoordenador = true;
                    }
                }
            }

            if (!$alvoEncontrado)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Integrante não encontrado na equipe."], 404);
            }

            if ($totalMembros <= 1)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "A equipe deve ter pelo menos um integrante."], 422);
            }

            if ($alvoEraCoordenador && $totalCoordenadores <= 1)
            {
                $novoCoordenadorId = $_POST["novo_coordenador_id"] ?? null;

                if (!$novoCoordenadorId || (int)$novoCoordenadorId === (int)$usuarioAlvoId)
                {
                    $this->responderJson([
                        "sucesso" => false, 
                        "mensagem" => "Você é o único coordenador da equipe. Selecione outro membro para assumir a coordenação antes de sair."
                    ], 422);
                    return;
                }

                // Promove o novo membro selecionado a coordenador
                $this->repositorySql->alterarPapelMembro($equipeId, (int)$novoCoordenadorId, "coordenador");
            }

            $sucesso = $this->repositorySql->removerMembro($equipeId, $usuarioAlvoId);

            if (!$sucesso)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível remover o membro."], 500);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => $ehProprioUsuario ? "Você saiu da equipe." : "Membro removido com sucesso.",
                "redirect" => $ehProprioUsuario ? URL_BASE . "/usuario/perfil" : null
            ]);
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao remover integrante."], 500);
        }
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

        $validador = new ValidadorHelper();

        $validador->obrigatorio("hash", $hash, "Informe o Hash de acesso da equipe.");
        $validador->obrigatorio("senha", trim($_POST["senha"] ?? ""), "Informe a senha da equipe.");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
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

        $validador = new ValidadorHelper();

        $validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("equipe_id", $_POST["equipe_id"]);
        $usuarioId = $this->validarId("usuario_id", $_POST["usuario_id"]);

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

        $validador = new ValidadorHelper();

        $validador->idPositivo("equipe_id", $_POST["equipe_id"] ?? "");
        $validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        $equipeId = $this->validarId("equipe_id", $_POST["equipe_id"]);
        $usuarioId = $this->validarId("usuario_id", $_POST["usuario_id"]);

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
}
