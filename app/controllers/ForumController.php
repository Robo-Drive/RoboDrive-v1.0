<?php

namespace app\controllers;

use app\controllers\MailController;
use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Forum;
use app\repositories\ForumRepositorySql;
use app\services\ForumService;

class ForumController extends Controller
{
    private ForumRepositorySql $repositorySql;
    private ForumService $service;
    private MailController $mailController;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->validador = new ValidadorHelper();
        $this->service = new ForumService();
        $this->repositorySql = new ForumRepositorySql();
        $this->mailController = new MailController();
    }
    public function listarPublico()
    {
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");

        $forumBusca = new Forum();
        $forumBusca->setConteudo($busca);

        $data["busca_atual"] = $busca;
        $data["foruns"] = $this->repositorySql->buscarConteudo($forumBusca);

        $this->view("forum/list",$data);
    }
    public function listarAdmin()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . '/forum/listar');
        }

        $status = (string)($_GET["status"] ?? "1");
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");

        $data["statusFiltro"] = $status;
        $data["busca_atual"] = $busca;
        $data["foruns"] = ($status === "todos") ? $this->repositorySql->listarAdminTodos($busca) : $this->repositorySql->listarPorStatus($status === "1", $busca);

        $this->view("forum/listAdmin",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("forum/create");
    }
    
    public function validar(bool $editar = false)
    {
        if ($editar)
        {
            $this->validador->idPositivo("id", $_POST["id"] ?? "");
        }

        $conteudo = trim($_POST["conteudo"] ?? "");

        $this->validador->obrigatorio("conteudo", $conteudo, "O conteúdo da postagem é obrigatório.");
        if (!isset($this->validador->getErros()["conteudo"]))
        {
            $this->validador->tamanho("conteudo", $conteudo, 3, 2000, "conteúdo");
        }

        $this->validador->visibilidadeForum("visibilidade", $_POST["visibilidade"] ?? "publico");
    }

    public function validarId()
    {
        $this->validador->idPositivo("id", $_POST["id"] ?? $_GET["id"] ?? "");
    }

    public function salvar()
    {
        $this->loginRequired();

        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $posts["conteudo"] = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $visibilidadePost = $_POST["visibilidade"] ?? 'publico';
        $posts["visibilidade"] = is_numeric($visibilidadePost) ? "equipe" : filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $equipeId = null;
        if (is_numeric($visibilidadePost))
        {
            $equipeId = (int)$visibilidadePost;
            $posts["equipe_id"] = $equipeId;
        }

        if ($equipeId && ($erroEquipe = $this->service->validarEquipe($equipeId)))
        {
            $this->responderAcao(false, $erroEquipe, 403);
        }

        $forum = Forum::map([$posts])[0];

        try
        {
            if ($this->service->salvarForum($forum))
            {
                $redirectUrl = $equipeId ? (URL_BASE . '/equipe/perfil?id=' . $equipeId) : (URL_BASE . '/forum/listar');

                $this->responderAcao(true, "Postagem criada com sucesso!", 200, $redirectUrl);
            }

            $this->responderAcao(false, "Não foi possível criar a publicação.", 500);
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao cadastrar postagem: " . $e->getMessage());

            $this->responderAcao(false, "Erro interno ao cadastrar postagem.", 500);
        }
    }

    public function atualizar()
    {
        $this->loginRequired();

        // Mesmo padrão do desativar: só o autor ou um admin
        $postagemAtual = $this->service->buscarPostagem((int)($_POST["id"] ?? 0));

        if (!$postagemAtual)
        {
            $this->responderAcao(false, "Postagem não encontrada.", 404);
        }

        if (!$this->ehAdmin() && (int)$postagemAtual->getUsuario()->getId() !== (int)$_SESSION["usuario_logado"]->getId())
        {
            $this->responderAcao(false, "Você não tem permissão para editar esta postagem.", 403);
        }

        $this->validar(true);

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $posts["id"] = $_POST["id"];
        $posts["conteudo"] = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $visibilidadePost = $_POST["visibilidade"] ?? 'publico';
        $posts["visibilidade"] = is_numeric($visibilidadePost) ? "equipe" : filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (is_numeric($visibilidadePost))
        {
            $posts["equipe_id"] = (int)$visibilidadePost;

            $atual = $postagemAtual;
            $erroEquipe = $this->service->validarEquipe((int)$posts["equipe_id"], $atual?->getEquipe()?->getId() ? (int)$atual->getEquipe()->getId() : null);

            if ($erroEquipe)
            {
                $this->responderAcao(false, $erroEquipe, 403);
            }
        }

        $forum = Forum::map([$posts])[0];

        try
        {
            if ($this->service->editarForum($forum))
            {
                $this->responderAcao(true, "Postagem atualizada com sucesso!", 200, URL_BASE . '/forum/perfil?id=' . $posts["id"]);
            }

            $this->responderAcao(false, "Não foi possível atualizar a publicação.", 500);
        }
        catch (\Throwable $e)
        {
            error_log("Erro ao atualizar postagem: " . $e->getMessage());

            $this->responderAcao(false, "Erro interno ao atualizar postagem.", 500);
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . '/forum/listar');
            return;
        }

        $forum = new Forum();
        $forum->setId((int)$_POST["id"]);
        $data["forum"] = $this->repositorySql->buscarId($forum);

        if($data["forum"] && $data["forum"]->getConteudo() != null && ($this->ehAdmin() || (int)$data["forum"]->getUsuario()->getId() === (int)$_SESSION["usuario_logado"]->getId()))
        {
            $this->view("forum/edit",$data);
        }
        else
        {
            $this->view("forum/list");
        }
    }
    
    public function perfil()
    {
        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . '/forum/listar');
            return;
        }

        $id = (int)($_GET["id"] ?? $_POST["id"]);

        $forumModel = new Forum();
        $forumModel->setId($id);
        $postagem = $this->repositorySql->buscarId($forumModel, $this->ehAdmin());

        if (!$postagem || $postagem->getConteudo() === null)
        {
            $this->redirect(URL_BASE . '/forum/listar');
            return;
        }

        if ($postagem->getVisibilidade() !== 'publico' && $postagem->getVisibilidade() !== null)
        {
            $this->loginRequired();
        }

        if (!$this->service->podeVisualizar($postagem))
        {
            $this->redirect(URL_BASE . '/forum/listar');
            return;
        }

        $data['comentarios'] = (new \app\repositories\ComentarioRepositorySql())->buscarPorForum((int)$postagem->getId());
        $data["forum"] = $postagem;
        $data["coordenaEquipe"] = $this->service->coordenaEquipeDaPostagem($postagem);
        $this->view("forum/perfil", $data);
    }
   
    public function desativar()
    {
        $this->loginRequired();

        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Postagem inválida.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];

        $forum = new \app\models\Forum();
        $forum->setId($id);
        $postagem = $this->repositorySql->buscarId($forum);

        if (!$postagem)
        {
            $this->responderAcao(false, "Postagem não encontrada.", 404);
        }

        $usuarioLogado = $_SESSION['usuario_logado'];
        $eAdmin = ($usuarioLogado->getRegra() === 'admin');
        $autorId = (int)$postagem->getUsuario()->getId();

        if (!$eAdmin && $autorId !== (int)$usuarioLogado->getId() && !$this->service->coordenaEquipeDaPostagem($postagem))
        {
            $this->responderAcao(false, "Você não tem permissão para desativar esta postagem.", 403);
        }

        $comJustificativa = $this->exigeJustificativa($autorId, $this->service->coordenaEquipeDaPostagem($postagem));
        $autoridade = $eAdmin ? "administração" : "coordenação da equipe";
        $justificativa = '';

        if ($comJustificativa)
        {
            $erro = $this->erroJustificativa();

            if ($erro)
            {
                $this->responderErroJustificativa($erro);
            }

            $justificativa = $this->justificativaEnviada();
        }

        $this->repositorySql->desativar($id);

        (new \app\repositories\DenunciaRepositorySql())->dispensar('forum', $id);

        if ($comJustificativa && !empty($postagem->getUsuario()->getEmail()))
        {
            try
            {
                $this->mailController->enviarDesativacaoPostagem(
                    $postagem->getUsuario()->getEmail(),
                    $postagem->getUsuario()->getNome(),
                    $postagem->getConteudo(),
                    $justificativa,
                    $autoridade
                );
            }
            catch (\Throwable $e)
            {
                error_log('Erro ao enviar e-mail de desativação de postagem: ' . $e->getMessage());
            }
        }

        $equipeId = $postagem->getEquipe()?->getId();
        $destino = $equipeId ? (URL_BASE . '/equipe/perfil?id=' . $equipeId) : (URL_BASE . '/forum/listar');

        $this->responderAcao(true, "Postagem desativada com sucesso.", 200, $destino);
    }

    public function ativar()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->responderAcao(false, "Acesso negado.", 403);
        }

        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Postagem inválida.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];

        $forum = new Forum();
        $forum->setId($id);
        $item = $this->repositorySql->buscarId($forum, true);

        if (!$item)
        {
            $this->responderAcao(false, "Postagem não encontrada.", 404);
        }

        if ($item->isStatus() !== false)
        {
            $this->responderAcao(false, "Postagem já está ativa.", 422);
        }

        $this->repositorySql->ativar($id);

        $this->responderAcao(true, "Postagem reativada com sucesso.");
    }
}
