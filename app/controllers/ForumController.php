<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Forum;
use app\repositories\ForumRepositorySql;
use app\services\ForumService;

class ForumController extends Controller
{
    private ForumRepositorySql $repositorySql;
    private ForumService $service;
    public function __construct()
    {
        $this->service = new ForumService();
        $this->repositorySql = new ForumRepositorySql();
    }
    public function listar()
    {
        $this->loginRequired();
        $data['foruns'] = $this->repositorySql->listarTodos();
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
        
        $validador->obrigatorio('conteudo',   trim($_POST["conteudo"]));
        $validador->tamanho('conteudo', $_POST["conteudo"], 3,100);
        
        $posts["conteudo"]   = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"]   = is_numeric($_POST["visibilidade"]) ? "equipe" : filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(is_numeric($_POST["visibilidade"]))
        {
            $posts["equipe_id"] = $_POST["visibilidade"];
        }
        $forum = Forum::map([$posts])[0];
        
        if($validador->temErros())
        {
            $data["forum"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('forum/create',$data);
        }
        else
        {
            if ($this->service->salvarForum($forum))
            {
                $this->redirect(URL_BASE . '/forum');
            } 
            else
            {
                $data["forum"] = $forum;
                $data["erros"]["forum"] = "Erro: Este forum já está cadastrado!";
                $this->view('forum/create',$data);
            }
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
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('conteudo',   $_POST["conteudo"]);
        $validador->tamanho('conteudo', $_POST["conteudo"], 3,100);
        
        $posts["id"]  = $_POST["id"];
        $posts["conteudo"]   = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["visibilidade"] = is_numeric($_POST["visibilidade"]) ? "equipe" : filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(is_numeric($_POST["visibilidade"]))
        {
            $posts["equipe_id"] = $_POST["visibilidade"];
        }
        $forum = Forum::map([$posts])[0];
        if($validador->temErros())
        {
            $data["forum"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('forum/edit',$data);
        }
        else
        {
            if ($this->service->editarForum($forum))
            {
                $this->redirect(URL_BASE . '/forum/listar');
            } 
            else
            {
                $data["forum"] = $forum;
                $data["erros"]["forum"] = "Erro: Este forum já está cadastrado!";
                $this->view('forum/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->redirect(URL_BASE . '/forum');
            return;
        }

        $forumModel = new Forum();
        $forumModel->setId($id);
        $postagem = $this->repositorySql->buscarId($forumModel);

        if (!$postagem || $postagem->getConteudo() === null) {
            $this->redirect(URL_BASE . '/forum');
            return;
        }

        $data["forum"] = $postagem;
        $this->view("forum/perfil", $data);
    }
   
    public function desativar()
    {
        $this->loginRequired();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $justificativa = trim(filter_input(INPUT_POST, 'justificativa', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

        if (!$id) {
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/forum');
            return;
        }

        $forum = new \app\models\Forum();
        $forum->setId($id);
        $postagem = $this->repositorySql->buscarId($forum);

        $usuarioLogado = $_SESSION['usuario_logado'];
        $eAdmin = ($usuarioLogado->getRegra() === 'admin');

        // Permite se for admin ou o próprio autor
        if ($postagem && ($eAdmin || $postagem->getUsuario()->getId() === $usuarioLogado->getId())) {
            $this->repositorySql->desativar($id);

            // Se houver denúncia pendente para este fórum, dispensa
            (new \app\repositories\DenunciaRepositorySql())->dispensar('forum', $id);

            // Notifica o autor por e-mail caso o admin tenha colocado justificativa
            if (!empty($postagem->getUsuario()->getEmail())) {
                $mailHelper = new \app\helpers\MailHelper();
                $motivoHtml = !empty($justificativa) ? nl2br(htmlspecialchars($justificativa)) : 'Sem justificativa detalhada informada.';
                $conteudoTrecho = htmlspecialchars(mb_strimwidth($postagem->getConteudo(), 0, 100, '...'));

                $mensagem = "
                    <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                        <h2 style='color: #F2A058;'>RoboDrive - Postagem Desativada</h2>
                        <p>Olá, <strong>" . htmlspecialchars($postagem->getUsuario()->getNome()) . "</strong>,</p>
                        <p>Informamos que sua postagem no fórum (<em>\"{$conteudoTrecho}\"</em>) foi desativada pela administração pelo seguinte motivo:</p>
                        <blockquote style='background-color: #000505; border-left: 4px solid #F2A058; padding: 12px; margin: 15px 0; color: #F2FEFE;'>
                            {$motivoHtml}
                        </blockquote>
                        <p>Caso discorde desta decisão, responda a este e-mail para solicitar revisão.</p>
                        <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                        <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>Administração RoboDrive</strong></p>
                    </div>
                ";

                $mailHelper->enviarEmail($postagem->getUsuario()->getEmail(), null, '', '[RoboDrive] Sua postagem foi desativada', $mensagem);
            }
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/forum');
    }
}