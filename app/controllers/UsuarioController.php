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
    private ValidadorHelper $validador;
    private array $posts = [];

    public function __construct()
    {
        $this->validador = new ValidadorHelper();
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

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
        }

        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");
        $data["busca_atual"] = $busca;
        $data["usuarios"] = $this->repositorySql->listarTodos($busca);
        $this->view("usuario/list", $data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $this->view("usuario/create");
    }
    
    public function validar(bool $editar = false)
    {
        $nome = trim($_POST["nome"] ?? "");
        $nomeUsuario = trim($_POST["nome_usuario"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        if ($editar)
        {
            $this->validador->idPositivo("id", $_POST["id"] ?? "");
        }

        $this->validador->obrigatorio("nome_usuario", $nomeUsuario, "O campo de nome de usuário é obrigatório");
        $this->validador->obrigatorio("nome", $nome);
        $this->validador->obrigatorio("email", $email);

        $biografia = trim($_POST["biografia"] ?? "");

        if ($biografia !== "")
        {
            $this->validador->tamanho("biografia", $biografia, 0, 500, "biografia");
        }

        if (!isset($this->validador->getErros()["nome_usuario"]))
        {
            $this->validador->tamanho("nome_usuario", $nomeUsuario, 3, $editar ? 50 : 100, "nome de usuario");
        }

        if (!isset($this->validador->getErros()["nome"]))
        {
            $this->validador->tamanho("nome", $nome, 3, 100);
        }

        if (!isset($this->validador->getErros()["email"]))
        {
            $this->validador->email($email);
        }

        // No cadastro a senha é obrigatória; na edição só é validada se for preenchida
        if (!$editar)
        {
            $this->validador->obrigatorio("senha", $senha);
            $this->validador->obrigatorio("confirmar_senha", $_POST["confirmar_senha"] ?? "", "O campo de confirmação de senha é obrigatório");
        }

        if ($senha !== "")
        {
            $this->validador->tamanho("senha", $senha, 8, 100, "senha");

            if ($editar)
            {
                $this->validador->obrigatorio("confirmar_senha", $_POST["confirmar_senha"] ?? "", "O campo de confirmação de senha é obrigatório");
            }
        }

        if (!isset($this->validador->getErros()["senha"]) && !isset($this->validador->getErros()["confirmar_senha"]) && ($senha !== "" || !$editar))
        {
            $this->validador->confirmarValor($senha, $_POST["confirmar_senha"] ?? "", "confirmar_senha", "A senha digitada é diferente da do campo senha");
        }

        if ($editar)
        {
            $respostaImagem = $this->validador->temImagem($_FILES, false);
            $this->posts["imagem"] = null;

            if ($respostaImagem["status"] && !$this->validador->temErros())
            {
                $resposta = $this->uploadService->upload($_FILES["imagem"]);

                if ($resposta["status"])
                {
                    $this->posts["imagem"] = $resposta["mensagem"];
                }
                else
                {
                    $this->validador->setErroImagem($resposta["mensagem"]);
                }
            }
            elseif (!$respostaImagem["status"] && !empty($_POST["remover_imagem"]))
            {
                // Imagem vazia faz o repository gravar NULL no banco
                $this->posts["imagem"] = "";
            }
        }
    }

    // Id de usuário enviado por POST (desativar, ativar e comunicado)
    public function validarId()
    {
        $this->validador->idPositivo("id", $_POST["id"] ?? "");
    }

    public function validarComunicado()
    {
        $this->validarId();

        $mensagem = trim($_POST["mensagem"] ?? "");

        $this->validador->obrigatorio("mensagem", $mensagem, "A mensagem do comunicado é obrigatória.");

        if (!isset($this->validador->getErros()["mensagem"]))
        {
            $this->validador->tamanho("mensagem", $mensagem, 10, 1000, "mensagem");
        }
    }

    public function validarCategoria()
    {
        $this->validador->idsDiferentes(
            "categoria_nova_id",
            $_POST["categoria_antiga_id"] ?? "",
            $_POST["categoria_nova_id"] ?? "",
            "Selecione uma categoria diferente para receber os projetos."
        );
    }

    public function salvar()
    {
        $this->validar();

        if ($this->validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $this->validador->getErros()], 422);
        }

        $posts["nome_usuario"] = filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["nome"] = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $posts["email"] = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $posts["senha"] = $_POST["senha"];

        $usuario = Usuario::map([$posts])[0];

        $resposta = $this->service->salvarUsuario($usuario);

        if (is_array($resposta))
        {
            $erros = array_filter([
                "email" => $resposta["email"] ?? null,
                "nome_usuario" => $resposta["nome_usuario"] ?? null
            ]);

            $this->responderJson(["sucesso" => false, "erros" => $erros], 422);
        }

        $this->responderAcao(true, "Cadastro realizado com sucesso!", 200, URL_BASE . "/login");
    }

    public function editar()
    {
        $this->loginRequired();
        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
        }

        $usuario = new Usuario();
        $usuario->setId((int)$_POST["id"]);
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
        $this->validar(true);

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $posts["imagem"] = $this->posts["imagem"];
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

            $this->responderAcao(true, "Perfil atualizado com sucesso!", 200, URL_BASE . "/usuario/perfil");
        }

        if ($resposta === false)
        {
            $this->responderAcao(false, "Não foi possível atualizar o perfil.", 500);
        }

        // Erros de duplicidade (email ou nome de usuário)
        $errosUnicidade = array_filter([
            "email" => $resposta["email"] ?? null,
            "nome_usuario" => $resposta["nome_usuario"] ?? null
        ]);

        $this->responderJson([
            "sucesso" => false,
            "erros" => $errosUnicidade
        ], 422);
    }
    public function perfil()
    {
        $this->loginRequired();

        $usuarioId = $_GET["id"] ?? $_SESSION["usuario_logado"]->getId();

        $this->validador->idPositivo("id", $usuarioId);

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . "/usuario/perfil?id=" . $_SESSION["usuario_logado"]->getId());
            return;
        }

        $usuarioId = (int)$usuarioId;
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
        $buscaEquipe = trim(filter_input(INPUT_GET, "busca_equipe", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
        $categoriaId = $_GET["categoria_id"] ?? null;

        $usuario = new Usuario();
        $usuario->setId($usuarioId);
        $usuarioEncontrado = $this->repositorySql->buscarId($usuario);

        if (!$usuarioEncontrado || $usuarioEncontrado->getNome() === null) 
        {
            $this->redirect(URL_BASE . "/usuario/perfil?id=" . $_SESSION["usuario_logado"]->getId());
            return;
        }

        $equipes = $buscaEquipe !== ""
            ? $this->equipeRepositorySql->buscarUsuarioFiltrado($usuarioEncontrado, $buscaEquipe)
            : $this->equipeRepositorySql->buscarUsuario($usuarioEncontrado);

        // Resposta rápida para a busca de equipes do perfil
        if (isset($_GET["ajax_equipe"]))
        {
            if (!empty($equipes))
            {
                foreach ($equipes as $equipe)
                {
                    include __DIR__ . "/../views/usuario/elements/cardEquipe.php";
                }
            }
            else
            {
                echo '<p class="rd-empty col-span-full">Nenhuma equipe encontrada.</p>';
            }
            exit;
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
                echo '<p class="rd-empty col-span-full">Nenhum projeto encontrado nesta categoria.</p>';
            }
            exit;
        }

        $categoriaRepository = new \app\repositories\CategoriaRepositorySql();
        $data["usuario"] = $usuarioEncontrado;
        $data["equipes"] = $equipes;
        $data["projetos"] = $projetos;
        $data["busca_equipe_atual"] = $buscaEquipe;
        $data["categorias"] = $categoriaRepository->listarPorUsuario($usuarioId);
        $data["busca_atual"] = $busca;
        $data["categoria_atual"] = $categoriaId;

        $this->view("usuario/perfil", $data);
    }
    public function desativar()
    {
        $this->loginRequired();

        $voltar = $_SERVER["HTTP_REFERER"] ?? URL_BASE . "/admin/usuario/listar";
        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Usuário inválido.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];
        $logadoId = (int)$_SESSION["usuario_logado"]->getId();
        $eAdmin = ($_SESSION["usuario_logado"]->getRegra() === "admin");

        if (!$eAdmin && $logadoId !== $id)
        {
            $this->responderAcao(false, "Acesso negado.", 403);
        }

        $usuario = new Usuario();
        $usuario->setId($id);
        $usuarioAlvo = $this->repositorySql->buscarId($usuario);

        if (!$usuarioAlvo)
        {
            $this->responderAcao(false, "Usuário não encontrado.", 404);
        }

        // Justificativa só quando um admin desativa a conta de outro usuário
        $comJustificativa = $this->exigeJustificativa($id);
        $justificativa = "";

        if ($comJustificativa)
        {
            $erro = $this->erroJustificativa();

            if ($erro)
            {
                $this->responderErroJustificativa($erro);
            }

            $justificativa = $this->justificativaEnviada();
        }

        $this->service->deletar($usuario);

        if ($comJustificativa && !empty($usuarioAlvo->getEmail()))
        {
            try
            {
                $this->mailController->enviarDesativacaoConta($usuarioAlvo->getEmail(), $usuarioAlvo->getNome(), $justificativa);
            }
            catch (\Throwable $e)
            {
                error_log("Erro ao enviar e-mail de desativação de conta: " . $e->getMessage());
            }
        }

        // Desativou a própria conta: encerra a sessão e volta para o início
        if ($id === $logadoId)
        {
            (new \app\services\AutenticacaoService())->logout();

            $this->responderAcao(true, "Sua conta foi desativada.", 200, URL_BASE);
        }

        $this->responderAcao(true, "Usuário desativado com sucesso.", 200, $voltar);
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
            $this->responderAcao(false, "Usuário inválido.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];

        $usuario = new Usuario();
        $usuario->setId($id);
        $this->service->ativar($usuario);

        $this->responderAcao(true, "Usuário reativado com sucesso.");
    }
    public function comunicado()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->responderAcao(false, "Acesso negado. Apenas administradores podem enviar comunicados.", 403);
        }

        $this->validarComunicado();

        if ($this->validador->temErros())
        {
            $erros = $this->validador->getErros();

            $this->responderJson([
                "sucesso" => false,
                "mensagem" => $erros["mensagem"] ?? reset($erros),
                "erros" => $erros
            ], 422);
        }

        $id = (int)$_POST["id"];
        $mensagemTexto = trim(filter_input(INPUT_POST, "mensagem", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");

        $usuario = new Usuario();
        $usuario->setId($id);
        $destinatario = $this->repositorySql->buscarId($usuario);

        if (!$destinatario || empty($destinatario->getEmail()))
        {
            $this->responderAcao(false, "Usuário não encontrado ou sem e-mail cadastrado.", 404);
        }

        try
        {
            $this->mailController->enviarComunicado($destinatario->getEmail(), $destinatario->getNome(), $mensagemTexto);
        } 
        catch(\Throwable $e) 
        {
            error_log("Erro ao enviar comunicado: " . $e->getMessage());

            $this->responderAcao(false, "Erro ao enviar o e-mail de comunicado.", 500);
        }

        $this->responderAcao(true, "Comunicado enviado com sucesso para o usuário!");
    }
    public function excluirCategoria()
    {
        $this->loginRequired();

        $this->validarCategoria();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, $this->validador->getErros()["categoria_nova_id"], 422, null, $this->validador->getErros());
        }

        $categoriaAntigaId = (int)$_POST["categoria_antiga_id"];
        $categoriaNovaId = (int)$_POST["categoria_nova_id"];
        $usuarioId = $_SESSION["usuario_logado"]->getId();

        $categoriaRepository = new \app\repositories\CategoriaRepositorySql();
        $categoriaRepository->reatribuirProjetosEDesativar($categoriaAntigaId, $categoriaNovaId, $usuarioId);

        $this->responderAcao(true, "Categoria apagada e projetos transferidos com sucesso.");
    }
}
