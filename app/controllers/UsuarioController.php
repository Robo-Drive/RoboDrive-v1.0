<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Usuario;
use app\repositories\EquipeRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\UploadService;
use app\services\UsuarioService;
use app\controllers\AutenticacaoController;
use app\controllers\MailController;


class UsuarioController extends Controller
{
    private UsuarioService $service;
    private UsuarioRepositorySql $repositorySql;
    private EquipeRepositorySql $equipeRepositorySql;
    private ProjetoRepositorySql $projetoRepositorySql;
    private UploadService $uploadService;
    private MailController $mailController;

    public function __construct()
    {
        $this->service = new UsuarioService();
        if (isset($_SESSION["usuario_logado"]))
        {
            $this->uploadService = new UploadService("/users/user-" . $_SESSION["usuario_logado"]->getId() . "/img");
        }
        $this->repositorySql = new UsuarioRepositorySql();
        $this->equipeRepositorySql = new EquipeRepositorySql();
        $this->projetoRepositorySql = new ProjetoRepositorySql();
        $this->mailController = new MailController();
    }

    public function listar()
    {
        $this->loginRequired();
        $data["usuarios"] = $this->repositorySql->listarTodos();
        $this->view("usuario/list", $data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("usuario/create");
    }
    
    public function salvar()
    {
        $validador = new ValidadorHelper();

        $validador->obrigatorio("nome_usuario", $_POST["nome_usuario"], "O campo de nome de usuário é obrigatório");
        $validador->obrigatorio("nome", $_POST["nome"]);
        $validador->obrigatorio("email", $_POST["email"]);
        $validador->obrigatorio("senha", $_POST["senha"]);
        $validador->obrigatorio("confirmar_senha", $_POST["confirmar_senha"], "O campo de confirmação de senha é obrigatório");

        if (!isset($validador->getErros()["nome_usuario"]))
        {
            $validador->tamanho("nome_usuario", $_POST["nome_usuario"], 3, 100, "nome de usuario");
        }

        if (!isset($validador->getErros()["nome"]))
        {
            $validador->tamanho("nome", $_POST["nome"], 3, 100);
        }

        if (!isset($validador->getErros()["senha"]))
        {
            $validador->tamanho("senha", $_POST["senha"], 8, 100);
        }

        if (!isset($validador->getErros()["senha"]) && !isset($validador->getErros()["confirmar_senha"]))
        {
            $validador->confirmarValor($_POST["senha"], $_POST["confirmar_senha"], "confirmar_senha", "A senha digitada é diferente da do campo senha");
        }

        if (!isset($validador->getErros()["email"]))
        {
            $validador->email($_POST["email"]);
        }

        if ($validador->temErros())
        {
            $data["usuario"] = $_POST;
            $data["erros"] = $validador->getErros();

            if ($this->requisicaoAjax())
            {
                $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
            }

            $this->view("cadastro/cadastro", $data);
            return;
        }
        else
        {
            $posts["nome_usuario"] = filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $posts["nome"] = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $posts["email"] = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $posts["senha"] = $_POST["senha"];

            $usuario = Usuario::map([$posts])[0];

            $resposta = $this->service->salvarUsuario($usuario);

            if (!is_array($resposta))
            {
                if ($this->requisicaoAjax())
                {
                    $this->responderJson(["sucesso" => true, "mensagem" => "Cadastro realizado com sucesso!", "redirect" => URL_BASE . "/login"]);
                }

                (new AutenticacaoController())->logar();
            }
            else
            {
                $data["usuario"] = $posts;
                $data["erros"]["email"] = $resposta["email"];
                $data["erros"]["nome_usuario"] = $resposta["nome_usuario"];

                if ($this->requisicaoAjax())
                {
                    $this->responderJson(["sucesso" => false, "erros" => array_filter($data["erros"])], 422);
                }

                $this->view("cadastro/cadastro", $data);
            }
        }
    }

    public function editar()
    {
        $this->loginRequired();
        $usuario = new Usuario();
        $usuario->setId($_POST["id"]);
        $data["usuario"] = $this->repositorySql->buscarId($usuario);
        if ($data["usuario"]->getNome() != null)
        {
            $this->view("usuario/edit", $data);
        }
        else
        {
            $this->view("usuario/list");
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();

        $validador->obrigatorio("id", $_POST["id"] ?? "");
        $validador->obrigatorio("nome", trim($_POST["nome"] ?? ""));
        $validador->obrigatorio("nome_usuario", trim($_POST["nome_usuario"] ?? ""));
        $validador->obrigatorio("email", trim($_POST["email"] ?? ""));
        $validador->tamanho("nome", trim($_POST["nome"] ?? ""), 3, 100, "Nome");
        $validador->tamanho("nome_usuario", trim($_POST["nome_usuario"] ?? ""), 3, 50, "Nome de usuário");
        $validador->email(trim($_POST["email"] ?? ""));

        if (!empty($_POST["senha"]))
        {
            $validador->tamanho("senha", $_POST["senha"], 8, 100, "Senha");
            $validador->obrigatorio("confirmar_senha", $_POST["confirmar_senha"] ?? "", "O campo de confirmação de senha é obrigatório");
            if (!isset($validador->getErros()["senha"]) && !isset($validador->getErros()["confirmar_senha"]))
            {
                $validador->confirmarValor($_POST["senha"], $_POST["confirmar_senha"], "confirmar_senha", "A senha digitada é diferente da do campo senha");
            }
        }

        $respostaImagem = $validador->temImagem($_FILES, false);
        $posts["imagem"] = null;

        if ($respostaImagem["status"])
        {
            $resposta = $this->uploadService->upload($_FILES["imagem"]);
            if ($resposta["status"])
            {
                $posts["imagem"] = $resposta["mensagem"];
            }
            else
            {
                $validador->setErroImagem($resposta["mensagem"]);
            }
        }
        else if (!empty($_POST["remover_imagem"]))
        {
            // Marca a imagem como string vazia para o repository setar NULL no banco
            $posts["imagem"] = "";
        }

        if ($validador->temErros())
        {
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => $validador->getErros()
                ], 422);
            }

            $data["usuario"] = $_POST;
            $data["erros"] = $validador->getErros();
            $this->view("usuario/edit", $data);
            return;
        }

        $posts["id"] = $_POST["id"];
        $posts["nome"] = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["nome_usuario"] = filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"] = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $posts["biografia"] = filter_input(INPUT_POST, "biografia", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($_POST["regra"]))
        {
            $posts["regra"] = filter_input(INPUT_POST, "regra", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        $posts["senha"] = !empty($_POST["senha"]) ? $_POST["senha"] : null;
        $usuario = Usuario::map([$posts])[0];

        $resposta = $this->service->editarUsuario($usuario);

        if ($resposta === true)
        {
            // Atualiza os dados do usuário na sessão ativa se ele alterou o próprio perfil
            if ($_SESSION["usuario_logado"]->getId() == $posts["id"])
            {
                $usuarioAtualizado = $this->repositorySql->buscarId($usuario);
                if ($usuarioAtualizado)
                {
                    $_SESSION["usuario_logado"] = $usuarioAtualizado;
                }
            }

            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => true,
                    "mensagem" => "Perfil atualizado com sucesso!",
                    "redirect" => URL_BASE . "/usuario/perfil"
                ]);
            }

            $this->redirect(URL_BASE . "/usuario/perfil");
            return;
        }

        // Erros de duplicidade (email ou nome de usuário)
        $errosUnicidade = array_filter([
            "email" => $resposta["email"] ?? null,
            "nome_usuario" => $resposta["nome_usuario"] ?? null
        ]);

        if ($this->requisicaoAjax())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $errosUnicidade
            ], 422);
        }

        $data["usuario"] = $posts;
        $data["erros"] = $errosUnicidade;
        $this->view("usuario/edit", $data);
    }
    public function perfil()
    {
        $this->loginRequired();

        $usuarioId = $_GET["id"] ?? $_SESSION["usuario_logado"]->getId();
        $busca = trim($_GET["busca"] ?? "");
        $categoriaId = $_GET["categoria_id"] ?? null;

        $usuario = new Usuario();
        $usuario->setId($usuarioId);
        $usuarioEncontrado = $this->repositorySql->buscarId($usuario);

        if (!$usuarioEncontrado || $usuarioEncontrado->getNome() === null) 
        {
            $this->redirect(URL_BASE . "/usuario/perfil?id=" . $_SESSION["usuario_logado"]->getId());
            return;
        }

        if (!empty($busca) || !empty($categoriaId)) 
        {
            $projetos = $this->projetoRepositorySql->buscarUsuarioFiltrado($usuarioId, $busca, $categoriaId);
        } 
        else 
        {
            $projetos = $this->projetoRepositorySql->buscarUsuario($usuarioEncontrado);
        }

        // Resposta rápida para busca por Ajax na aba de projetos do perfil
        $isAjax = (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest") || isset($_GET["ajax"]);
        if ($isAjax) 
        {
            if (!empty($projetos)) 
            {
                foreach ($projetos as $projeto) 
                {
                    include __DIR__ . "/../views/usuario/elements/cardProjeto.php";
                }
            } 
            else 
            {
                echo '<p class="rd-empty w-full">Nenhum projeto encontrado nesta categoria.</p>';
            }
            exit;
        }

        $categoriaRepository = new \app\repositories\CategoriaRepositorySql();
        $data["usuario"] = $usuarioEncontrado;
        $data["equipes"] = $this->equipeRepositorySql->buscarUsuario($usuarioEncontrado);
        $data["projetos"] = $projetos;
        $data["categorias"] = $categoriaRepository->listarPorUsuario($usuarioId);
        $data["busca_atual"] = $busca;
        $data["categoria_atual"] = $categoriaId;

        $this->view("usuario/perfil", $data);
    }
    public function desativar()
    {
        $this->loginRequired();

        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
        $justificativa = trim(filter_input(INPUT_POST, "justificativa", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");

        if (!$id)
        {
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . "/usuario/perfil");
            return;
        }

        $eAdmin = ($_SESSION["usuario_logado"]->getRegra() === "admin");

        if (!$eAdmin && $_SESSION["usuario_logado"]->getId() !== $id)
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
            return;
        }

        $usuario = new Usuario();
        $usuario->setId($id);
        $usuarioAlvo = $this->repositorySql->buscarId($usuario);

        if ($usuarioAlvo)
        {
            $this->service->deletar($usuario);
            if (!empty($usuarioAlvo->getEmail()))
            {
                try
                {
                    $this->mailController->enviarDesativacaoConta($usuarioAlvo->getEmail(), $usuarioAlvo->getNome(), $justificativa ?? "");
                }
                catch (\Throwable $e)
                {
                    error_log("Erro ao enviar e-mail de desativação de conta: " . $e->getMessage());
                }
            }
        }

        if ($id === $_SESSION["usuario_logado"]->getId())
        {
            (new AutenticacaoController())->logout();
            return;
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/usuario');
    }
    public function ativar()
    {
        $this->loginRequired();
        $this->adminRequired();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id)
        {
            $usuario = new Usuario();
            $usuario->setId($id);
            $this->service->ativar($usuario);
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/usuario');
    }
    public function comunicado()
    {
        $this->loginRequired();
        
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        if (!$this->adminRequired())
        {
            if ($isAjax)
            {
                http_response_code(403);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado. Apenas administradores podem enviar comunicados.']);
                exit;
            }
            $this->redirect(URL_BASE);
            return;
        }

        $id = filter_input(INPUT_POST,"id", FILTER_VALIDATE_INT);
        $mensagemTexto = trim(filter_input(INPUT_POST, "mensagem", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

        $validador = new ValidadorHelper();
        $validador->obrigatorio("mensagem",$mensagemTexto);
        $validador->tamanho("mensagem",$mensagemTexto,10,1000);
        if ($validador->temErros())
        {
            if ($isAjax)
            {
                http_response_code(422);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode([
                    "sucesso" => false,
                    "mensagem" => "A mensagem do comunicado é obrigatória e deve ter pelo menos 10 caracteres."
                ]);
                exit;
            }
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . "/denuncia");
            return;
        }

        if (!$id)
        {
            if ($isAjax)
            {
                http_response_code(422);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["sucesso" => false, "mensagem" => 'Destinatário inválido.']);
                exit;
            }
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . "/denuncia");
            return;
        }

        $usuario = new Usuario();
        $usuario->setId($id);
        $destinatario = $this->repositorySql->buscarId($usuario);

        if (!$destinatario || empty($destinatario->getEmail()))
        {
            if ($isAjax)
            {
                http_response_code(404);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["sucesso" => false, "mensagem" => "Usuário não encontrado ou sem e-mail cadastrado."]);
                exit;
            }
            $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . "/denuncia");
            return;
        }

        try
        {
            $this->mailController->enviarComunicado($destinatario->getEmail(), $destinatario->getNome(), $mensagemTexto);

            if ($isAjax)
            {
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode([
                    "sucesso" => true,
                    "mensagem" => "Comunicado enviado com sucesso para o usuário!"
                ]);
                exit;
            }
        } 
        catch(\Throwable $e) 
        {
            error_log("Erro ao enviar comunicado: " . $e->getMessage());
            if($isAjax) 
            {
                http_response_code(500);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar o e-mail de comunicado."]);
                exit;
            }
        }

        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . "/denuncia");
    }
    public function excluirCategoria()
    {
        $this->loginRequired();

        $categoriaAntigaId = $_POST["categoria_antiga_id"] ?? null;
        $categoriaNovaId = $_POST["categoria_nova_id"] ?? null;
        $usuarioId = $_SESSION["usuario_logado"]->getId();

        if ($categoriaAntigaId && $categoriaNovaId && $categoriaAntigaId != $categoriaNovaId) 
        {
            $categoriaRepository = new \app\repositories\CategoriaRepositorySql();
            $categoriaRepository->reatribuirProjetosEDesativar($categoriaAntigaId, $categoriaNovaId, $usuarioId);
        }

        $this->redirect(URL_BASE . "/usuario/perfil");
    }
}
