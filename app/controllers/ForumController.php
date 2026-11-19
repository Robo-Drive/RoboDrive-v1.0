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

    public function __construct()
    {
        $this->service = new ForumService();
        $this->repositorySql = new ForumRepositorySql();
        $this->mailController = new MailController();
    }
    public function listar()
    {
        $status = $_GET["status"] ?? null;
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;
        $eAdmin = ($usuarioLogado && method_exists($usuarioLogado, 'getRegra') && $usuarioLogado->getRegra() === 'admin');

        if ($eAdmin && $status !== null)
        {
            $data["statusFiltro"] = $status;
            $data["foruns"] = ($status === "todos")
                ? $this->repositorySql->listarAdminTodos()
                : $this->repositorySql->listarPorStatus($status === "1");
        }
        else
        {
            $data['foruns'] = $this->repositorySql->listarTodos();
        }

        $this->view("forum/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("forum/create");
    }
        public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        
        $conteudo = trim($_POST["conteudo"] ?? '');
        $validador->obrigatorio('conteudo', $conteudo, 'O conteúdo da postagem é obrigatório.');
        $validador->tamanho('conteudo', $conteudo, 3, 2000, 'Conteúdo');
        
        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["forum"] = $_POST;
            $data["erros"] = $validador->getErros();
            $this->view('forum/create', $data);
            return;
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

        $forum = Forum::map([$posts])[0];

        try
        {
            if ($this->service->salvarForum($forum))
            {
                $redirectUrl = $equipeId ? (URL_BASE . '/equipe/perfil?id=' . $equipeId) : (URL_BASE . '/forum/listar');

                if ($this->requisicaoAjax())
                {
                    $this->responderJson([
                        "sucesso" => true,
                        "mensagem" => "Postagem criada com sucesso!",
                        "redirect" => $redirectUrl
                    ]);
                }

                $this->redirect($redirectUrl);
                return;
            }

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Não foi possível criar a publicação."
                ], 500);
            }

            $data["forum"] = $posts;
            $data["erros"]["forum"] = "Erro ao cadastrar postagem no fórum.";
            $this->view('forum/create', $data);
        }
        catch (\Throwable $e)
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Erro interno ao cadastrar postagem."
                ], 500);
            }

            $data["forum"] = $posts;
            $data["erros"]["forum"] = "Erro ao cadastrar postagem no fórum.";
            $this->view('forum/create', $data);
        }
    }

    public function atualizar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        
        $conteudo = trim($_POST["conteudo"] ?? '');
        $validador->obrigatorio('conteudo', $conteudo, 'O conteúdo da postagem é obrigatório.');
        $validador->tamanho('conteudo', $conteudo, 3, 2000, 'Conteúdo');
        
        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["forum"] = $_POST;
            $data["erros"] = $validador->getErros();
            $this->view('forum/edit', $data);
            return;
        }

        $posts["id"] = $_POST["id"];
        $posts["conteudo"] = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $visibilidadePost = $_POST["visibilidade"] ?? 'publico';
        $posts["visibilidade"] = is_numeric($visibilidadePost) ? "equipe" : filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (is_numeric($visibilidadePost))
        {
            $posts["equipe_id"] = (int)$visibilidadePost;
        }

        $forum = Forum::map([$posts])[0];

        try
        {
            if ($this->service->editarForum($forum))
            {
                if ($this->requisicaoAjax())
                {
                    $this->responderJson([
                        "sucesso" => true,
                        "mensagem" => "Postagem atualizada com sucesso!",
                        "redirect" => URL_BASE . '/forum/perfil?id=' . $posts["id"]
                    ]);
                }

                $this->redirect(URL_BASE . '/forum/listar');
                return;
            }

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Não foi possível atualizar a publicação."
                ], 500);
            }

            $data["forum"] = $posts;
            $data["erros"]["forum"] = "Erro ao atualizar postagem no fórum.";
            $this->view('forum/edit', $data);
        }
        catch (\Throwable $e)
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Erro interno ao atualizar postagem."
                ], 500);
            }

            $data["forum"] = $posts;
            $data["erros"]["forum"] = "Erro ao atualizar postagem no fórum.";
            $this->view('forum/edit', $data);
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $forum = new Forum();
        $forum->setId($_POST["id"]);
        $data["forum"] = $this->repositorySql->buscarId($forum);
        if($data["forum"]->getConteudo() != null)
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
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id)
        {
            $this->redirect(URL_BASE . '/forum');
            return;
        }

        $forumModel = new Forum();
        $forumModel->setId($id);
        $postagem = $this->repositorySql->buscarId($forumModel);

        if (!$postagem || $postagem->getConteudo() === null)
        {
            $this->redirect(URL_BASE . '/forum');
            return;
        }

        if ($postagem->getVisibilidade() === 'equipe')
        {
            $this->loginRequired();
        }

        $data['comentarios'] = (new \app\repositories\ComentarioRepositorySql())->buscarPorForum((int)$postagem->getId());
        $data["forum"] = $postagem;
        $this->view("forum/perfil", $data);
    }
   
    public function desativar()
    {
        $this->loginRequired();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $justificativa = trim(filter_input(INPUT_POST, 'justificativa', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

        if (!$id)
        {
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/forum');
            return;
        }

        $forum = new \app\models\Forum();
        $forum->setId($id);
        $postagem = $this->repositorySql->buscarId($forum);

        $usuarioLogado = $_SESSION['usuario_logado'];
        $eAdmin = ($usuarioLogado->getRegra() === 'admin');

        // Permite se for admin ou o próprio autor
        if ($postagem && ($eAdmin || $postagem->getUsuario()->getId() === $usuarioLogado->getId()))
        {
            $this->repositorySql->desativar($id);

            // Se houver denúncia pendente para este fórum, dispensa
            (new \app\repositories\DenunciaRepositorySql())->dispensar('forum', $id);

            // Notifica o autor por e-mail caso o admin tenha colocado justificativa
            if (!empty($postagem->getUsuario()->getEmail()))
            {
                try
                {
                    $this->mailController->enviarDesativacaoPostagem(
                        $postagem->getUsuario()->getEmail(),
                        $postagem->getUsuario()->getNome(),
                        $postagem->getConteudo(),
                        $justificativa ?? ''
                    );
                }
                catch (\Throwable $e)
                {
                    error_log('Erro ao enviar e-mail de desativação de postagem: ' . $e->getMessage());
                }
            }
        }
        // Se a postagem pertencia a uma equipe, volta para o perfil da equipe
        $equipeId = $postagem?->getEquipe()?->getId();
        if ($equipeId)
        {
            $this->redirect(URL_BASE . '/equipe/perfil?id=' . $equipeId);
            return;
        }

        $this->redirect(URL_BASE . '/forum');
    }
}