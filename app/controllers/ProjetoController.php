<?php

namespace app\controllers;

use app\core\Controller;
use app\controllers\MailController;
use app\helpers\ValidadorHelper;
use app\models\Categoria;
use app\models\Componente;
use app\models\Equipe;
use app\models\Projeto;
use app\repositories\CodigoRepositorySql;
use app\repositories\ComentarioRepositorySql;
use app\repositories\ComponenteRepositorySql;
use app\repositories\DenunciaRepositorySql;
use app\repositories\ExtraRepositorySql;
use app\repositories\ImagemRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\CategoriaService;
use app\services\CodigoService;
use app\services\ComponenteService;
use app\services\ExtraService;
use app\services\ImagemService;
use app\services\ProjetoService;

class ProjetoController extends Controller
{
    private ProjetoService $service;
    private ProjetoRepositorySql $repositorySql;
    private ComponenteService $componenteService;
    private CategoriaService $categoriaService;
    private CodigoService $codigoService;
    private ExtraService $extraService;
    private ImagemService $imagemService;
    private UsuarioRepositorySql $usuarioRepositorySql;
    private CodigoRepositorySql $codigoRepositorySql;
    private ExtraRepositorySql $extraRepositorySql;
    private ComponenteRepositorySql $componenteRepositorySql;
    private ImagemRepositorySql $imagemRepositorySql;
    private ComentarioRepositorySql $comentarioRepositorySql;
    private DenunciaRepositorySql $denunciaRepositorySql;
    private MailController $mailController;
    private ValidadorHelper $validador;

    public function __construct()
    {
        $this->validador = new ValidadorHelper();
        $this->service = new ProjetoService();
        $this->repositorySql = new ProjetoRepositorySql();
        $this->componenteService = new ComponenteService();
        $this->categoriaService = new CategoriaService();
        $this->codigoService = new CodigoService();
        $this->extraService = new ExtraService();
        $this->imagemService = new ImagemService();
        $this->usuarioRepositorySql = new UsuarioRepositorySql();
        $this->codigoRepositorySql = new CodigoRepositorySql();
        $this->extraRepositorySql = new ExtraRepositorySql();
        $this->componenteRepositorySql = new ComponenteRepositorySql();
        $this->imagemRepositorySql = new ImagemRepositorySql();
        $this->comentarioRepositorySql = new ComentarioRepositorySql();
        $this->denunciaRepositorySql = new DenunciaRepositorySql();
        $this->mailController = new MailController();
    }

    public function listarAdmin()
    {
        $this->loginRequired();

        if (!$this->adminRequired())
        {
            $this->redirect(URL_BASE . "/projeto/listar");
        }

        $status = (string)($_GET["status"] ?? "1");

        $data["statusFiltro"] = $status;
        $data["projetos"] = ($status === "todos") ? $this->repositorySql->listarAdminTodos() : $this->repositorySql->listarPorStatus($status === "1");

        $this->view("projeto/list", $data);
    }

    public function listarPublico()
    {
        $busca = trim(filter_input(INPUT_GET, "busca", FILTER_SANITIZE_FULL_SPECIAL_CHARS)??"");

        if ($busca !== "")
        {
            $projetos = $this->repositorySql->buscarPublicoFiltrado($busca);
        }
        else
        {
            $projetos = $this->repositorySql->listarPublico();
        }

        $isAjax = (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string)$_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest") || isset($_GET["ajax"]);

        if ($isAjax)
        {
            if (!empty($projetos))
            {
                foreach ($projetos as $projeto)
                {
                    include __DIR__ . "/../views/projeto/elements/cardLista.php";
                }
            }
            else
            {
                echo '<p class="rd-empty w-full col-span-full">Nenhum projeto encontrado.</p>';
            }

            exit;
        }

        $data["projetos"] = $projetos;
        $data["busca_atual"] = $busca;

        $this->view("projeto/list", $data);
    }

    public function cadastrar()
    {
        $this->loginRequired();

        $data["componentes"] = $this->componenteService->listarTodos();
        $data["categorias"] = $this->categoriaService->listarTodos();

        $this->view("projeto/create", $data);
    }

    public function validar(bool $editar = false, bool $ehCoordenador = true)
    {
        if ($editar)
        {
            $this->validador->idPositivo("id", $_POST["id"] ?? "");
        }

        if ($ehCoordenador)
        {
            $this->validador->obrigatorio("nome", trim((string)($_POST["nome"] ?? "")), "O nome do projeto é obrigatório.");
            if (!isset($this->validador->getErros()["nome"]))
            {
                $this->validador->tamanho("nome", trim((string)($_POST["nome"] ?? "")), 3, 100);
            }
        }

        $this->validador->obrigatorio("descricao", trim((string)($_POST["descricao"] ?? "")), "A descrição é obrigatória.");
        if (!isset($this->validador->getErros()["descricao"]))
        {
            $this->validador->tamanho("descricao", trim((string)($_POST["descricao"] ?? "")), 3, 2000, "descrição");
        }

        if (!$editar)
        {
            $this->validador->obrigatorio("senha", trim((string)($_POST["senha"] ?? "")), "A senha de acesso ao projeto é obrigatória.");
            $this->validador->tamanho("senha", trim((string)($_POST["senha"] ?? "")), 8, 100, "Senha");
            $this->validador->confirmarValor((string)($_POST["senha"] ?? ""), (string)($_POST["confirmar_senha"] ?? ""), "confirmar_senha", "As senhas não conferem.");
        }

        if (!$editar || $ehCoordenador)
        {
            $this->validador->visibilidadeValida(trim((string)($_POST["visibilidade"] ?? "")));
            $this->validador->categoria($_POST["categoria"] ?? "", $_POST["nova_categoria"] ?? "");
        }

        $this->validador->componentes($_POST["componentes"] ?? []);

        $this->validador->descricoesArquivos($_FILES["codigos"] ?? [], $_POST["codigos_descricao"] ?? [], "codigos_descricao", "do código");
        $this->validador->descricoesArquivos($_FILES["extras"] ?? [], $_POST["extras_descricao"] ?? [], "extras_descricao", "do arquivo extra");

        $this->validador->validarImagens("imagens", $_FILES["imagens"] ?? []);
        $this->validador->validarCodigos("codigos", $_FILES["codigos"] ?? []);
        $this->validador->validarExtras("extras", $_FILES["extras"] ?? []);
    }

    private function mapaComponentes(): array
    {
        $map = [];

        foreach (($_POST["componentes"] ?? []) as $componenteId => $dados)
        {
            if ((int)$componenteId > 0)
            {
                $map[(int)$componenteId] = (int)($dados["quantidade"] ?? 0);
            }
        }

        return $map;
    }

    public function validarMembro(bool $comPapel = false)
    {
        $this->validador->idPositivo("projeto_id", $_POST["projeto_id"] ?? "");
        $this->validador->idPositivo("usuario_id", $_POST["usuario_id"] ?? "");

        if ($comPapel)
        {
            $this->validador->papel(trim((string)($_POST["papel"] ?? "participante")));
        }
    }

    public function validarAcesso(string $hash, string $senha)
    {
        $this->validador->obrigatorio("hash", $hash, "Informe o Hash de acesso do projeto.");
        $this->validador->obrigatorio("senha", $senha, "Informe a senha do projeto.");
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
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $componentesObj = [];

        foreach ($this->mapaComponentes() as $componenteId => $quantidade)
        {
            $comp = new Componente();
            $comp->setId($componenteId);
            $comp->setQuantidade($quantidade);

            $componentesObj[] = $comp;
        }

        $codigos = $_FILES["codigos"] ?? [];
        $codigosDesc = $_POST["codigos_descricao"] ?? [];
        $extras = $_FILES["extras"] ?? [];
        $extrasDesc = $_POST["extras_descricao"] ?? [];

        try
        {
            $categoriaRaw = trim($_POST["categoria"] ?? "");

            $equipeId = !empty($_POST["equipe_id"])
                ? (int)$_POST["equipe_id"]
                : null;

            $erroVinculo = $this->service->validarVinculos(
                $equipeId,
                $categoriaRaw === "criar" ? null : (int)$categoriaRaw,
                (int)$_SESSION["usuario_logado"]->getId(),
                trim((string)($_POST["visibilidade"] ?? ""))
            );

            if ($erroVinculo)
            {
                $this->responderAcao(false, $erroVinculo, 403);
            }

            if ($categoriaRaw === "criar")
            {
                $novaCat = $this->categoriaService->cadastro(["categoria_nome" => trim(filter_input(INPUT_POST, "nova_categoria", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "")]);
                $categoriaId = (int)$novaCat->getId();
            }
            else
            {
                $categoriaId = (int)$categoriaRaw;
            }

            $nome = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
            $descricao = trim(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
            $visibilidade = trim(filter_input(INPUT_POST, "visibilidade", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");

            $senha = $_POST["senha"] ?? "";

            $postsProjeto = [
                "nome" => $nome,
                "descricao" => $descricao,
                "visibilidade" => $visibilidade,
                "equipe_id" => $equipeId,
                "categoria_id" => $categoriaId,
                "componentes" => $componentesObj,
                "codigos_descricao" => $codigosDesc,
                "senha" => $senha
            ];

            $cadastrados = $this->service->salvarProjeto($postsProjeto);

            $projetoSalvo = $cadastrados[0];
            $projetoVersaoSalvo = $cadastrados[1];

            if (!empty($componentesObj))
            {
                $this->componenteService->associar([
                    "componentes" => $componentesObj,
                    "projeto" => $projetoVersaoSalvo
                ]);
            }

            if ($this->validador->possuiArquivos($codigos))
            {
                $this->codigoService->salvarCodigo(
                    $codigos,
                    $codigosDesc,
                    $projetoVersaoSalvo
                );
            }

            if ($this->validador->possuiArquivos($extras))
            {
                $this->extraService->salvarExtras(
                    $extras,
                    $extrasDesc,
                    $projetoVersaoSalvo
                );
            }

            if ($this->validador->possuiArquivos($_FILES["imagens"] ?? []))
            {
                $this->imagemService->salvarImagem(
                    $_FILES["imagens"],
                    $projetoSalvo
                );
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Projeto cadastrado com sucesso!",
                "redirect" => $equipeId ? URL_BASE . "/equipe/perfil?id=" . $equipeId : URL_BASE . "/usuario/perfil"
            ]);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO SALVAR PROJETO: " . $e->getMessage());

            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "Não foi possível cadastrar o projeto."
            ], 500);
        }
    }

    public function editar()
    {
        $this->loginRequired();

        $this->validarId();

        $projetoId = (int)($_POST["id"] ?? $_GET["id"] ?? 0);

        if ($this->validador->temErros() || !$this->service->usuarioTemPermissao($projetoId))
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);

        $projeto = $this->repositorySql->buscarId($projetoBusca, $this->ehAdmin());

        if (!$projeto || $projeto->getNome() === null)
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        $versaoAtual = $this->repositorySql->buscarUltimaVersao($projetoId);

        $codigosAtuais = [];
        $componentesAtuais = [];
        $extrasAtuais = [];

        if ($versaoAtual)
        {
            $versaoId = (int)$versaoAtual["id"];

            $codigosAtuais = $this->codigoRepositorySql->buscarPorVersao($versaoId);
            $componentesAtuais = $this->componenteRepositorySql->buscarPorVersao($versaoId);
            $extrasAtuais = $this->extraRepositorySql->buscarPorVersao($versaoId);
        }

        $data["projeto"] = $projeto;
        $data["componentes"] = $this->componenteService->listarTodos();

        $proprietarioId = $projeto->getUsuario()?->getId();

        $data["categorias"] = $this->categoriaService->listarPorUsuario((int)$proprietarioId);

        $data["codigosAtuais"] = $codigosAtuais;
        $data["componentesAtuais"] = $componentesAtuais;
        $data["extrasAtuais"] = $extrasAtuais;
        $data["imagensAtuais"] = $this->imagemService->buscarProjeto($projetoId);
        $data["ehCoordenador"] = $this->service->usuarioECoordenador($projetoId);

        $this->view("projeto/edit", $data);
    }

    public function atualizar()
    {
        $this->loginRequired();

        $this->validador->idPositivo("id", $_POST["id"] ?? "");

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "ID do projeto inválido."
            ], 422);
        }

        $projetoId = (int)$_POST["id"];

        if (!$this->service->usuarioTemPermissao($projetoId))
        {
            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "Você não tem permissão para alterar este projeto."
            ], 403);
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);

        $projetoExistente = $this->repositorySql->buscarId($projetoBusca);

        if (!$projetoExistente || $projetoExistente->getNome() === null)
        {
            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "Projeto não encontrado."
            ], 404);
        }

        $ehCoordenador = $this->service->usuarioECoordenador($projetoId);

        $proprietarioId = (int)($projetoExistente->getUsuario()?->getId() ?? 0);

        $this->validar(true, $ehCoordenador);

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        $novosCodigos = $_FILES["codigos"] ?? [];
        $novosCodigosDesc = $_POST["codigos_descricao"] ?? [];
        $novosExtras = $_FILES["extras"] ?? [];
        $novosExtrasDesc = $_POST["extras_descricao"] ?? [];

        try
        {
            $this->service->atualizarVersao(
                $projetoId,
                (int)$_SESSION["usuario_logado"]->getId(),
                $this->mapaComponentes(),
                $_POST["codigos_manter"] ?? [],
                $_POST["extras_manter"] ?? [],
                $novosCodigos,
                $novosCodigosDesc,
                $novosExtras,
                $novosExtrasDesc,
                trim((string)($_POST["descricao_alteracao"] ?? ""))
            );

            if ($ehCoordenador)
            {
                $categoriaRaw = trim($_POST["categoria"] ?? "");

                $equipePost = !empty($_POST["equipe_id"]) ? (int)$_POST["equipe_id"] : null;
                $erroVinculo = $this->service->validarVinculos(
                    $equipePost,
                    $categoriaRaw === "criar" ? null : (int)$categoriaRaw,
                    $proprietarioId,
                    trim((string)($_POST["visibilidade"] ?? $projetoExistente->getVisibilidade())),
                    $projetoExistente->getEquipe()?->getId() ? (int)$projetoExistente->getEquipe()->getId() : null
                );

                if ($erroVinculo)
                {
                    $this->responderJson(["sucesso" => false, "mensagem" => $erroVinculo], 403);
                }

                if ($categoriaRaw === "criar")
                {
                    $novaCat = $this->categoriaService->cadastro([
                        "categoria_nome" => trim(
                            filter_input(INPUT_POST, "nova_categoria", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? ""
                        )
                    ], $proprietarioId);

                    $categoriaId = (int)$novaCat->getId();
                }
                else
                {
                    $categoriaId = (int)$categoriaRaw;
                }

                $visibilidade = trim(
                    filter_input(INPUT_POST, "visibilidade", FILTER_SANITIZE_FULL_SPECIAL_CHARS)
                    ?? $projetoExistente->getVisibilidade()
                );
            }
            else
            {
                $categoriaId = (int)($projetoExistente->getCategoria()?->getId() ?? 0);

                $visibilidade = (string)$projetoExistente->getVisibilidade();
            }
            $nome = $ehCoordenador
                ? trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "")
                : (string)$projetoExistente->getNome();

            $descricao = trim(
                filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? ""
            );

            $projetoObj = new Projeto();

            $projetoObj->setId($projetoId);
            $projetoObj->setNome($nome);
            $projetoObj->setDescricao($descricao);
            $projetoObj->setVisibilidade($visibilidade);

            if (!$ehCoordenador)
            {
                $projetoObj->setEquipe($projetoExistente->getEquipe());
            }
            elseif (!empty($_POST["equipe_id"]))
            {
                $equipe = new Equipe();
                $equipe->setId((int)$_POST["equipe_id"]);

                $projetoObj->setEquipe($equipe);
            }

            $catObj = new Categoria();
            $catObj->setId($categoriaId);

            $projetoObj->setCategoria($catObj);

            $this->service->editarProjeto($projetoObj);

            $imagensManter = array_values(array_unique(
                array_map("intval", $_POST["imagens_manter"] ?? [])
            ));

            $this->imagemRepositorySql->desativarImagensExceto(
                $projetoId,
                $imagensManter
            );

            if ($this->validador->possuiArquivos($_FILES["imagens"] ?? []))
            {
                $this->imagemService->salvarImagem(
                    $_FILES["imagens"],
                    $projetoObj
                );
            }

            $this->responderJson([ 
                "sucesso" => true,
                "mensagem" => "Projeto atualizado com sucesso!",
                "redirect" => URL_BASE . "/projeto/perfil?id=" . $projetoId
            ]);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO ATUALIZAR PROJETO: " . $e->getMessage());

            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "Não foi possível atualizar o projeto."
            ], 500);
        }
    }

    public function formularioEntrar()
    {
        $this->loginRequired();

        $this->view("projeto/entrar");
    }

    public function entrar()
    {
        $this->loginRequired();

        $hash = trim($_POST["hash"] ?? ($_POST["hash_acesso"] ?? ""));
        $senha = trim($_POST["senha"] ?? "");

        $this->validarAcesso($hash, $senha);

        if ($this->validador->temErros())
        {
            $this->responderJson([
                "sucesso" => false,
                "erros" => $this->validador->getErros()
            ], 422);
        }

        try
        {
            $projeto = $this->repositorySql->buscarPorHash($hash);

            if (!$projeto)
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Código Hash do projeto não encontrado."
                ], 404);
            }

            if (!password_verify($senha, $projeto->getSenha()))
            {
                $this->responderJson([
                    "sucesso" => false,
                    "erros" => ["senha" => "Senha incorreta para este projeto."]
                ], 422);
            }

            $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

            $equipeDoProjetoId = (int)($projeto->getEquipe()?->getId() ?? 0);

            if ($equipeDoProjetoId > 0 && !(new \app\repositories\EquipeRepositorySql())->usuarioPertenceAEquipe($equipeDoProjetoId, (int)$usuarioLogado->getId()))
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Este projeto pertence a uma equipe. Apenas integrantes da equipe podem entrar nele."
                ], 403);
            }

            $solicitou = $this->repositorySql->solicitarEntrada(
                (int)$projeto->getId(),
                (int)$usuarioLogado->getId()
            );

            if (!$solicitou)
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Você já é integrante ou possui solicitação pendente para este projeto."
                ], 422);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Solicitação de entrada enviada com sucesso! Aguarde a aprovação do coordenador.",
                "redirect" => URL_BASE . "/usuario/perfil"
            ]);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO ENTRAR NO PROJETO: " . $e->getMessage());

            $this->responderJson([
                "sucesso" => false,
                "mensagem" => "Erro ao solicitar entrada no projeto."
            ], 500);
        }
    }

    public function aprovarSolicitacao()
    {
        $this->loginRequired();
        $this->validarMembro();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Dados inválidos para aprovar a solicitação.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["projeto_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->service->usuarioECoordenador($projetoId))
        {
            $this->responderAcao(false, "Apenas coordenadores podem aprovar solicitações.", 403);
        }

        $this->repositorySql->aprovarSolicitacao($projetoId, $usuarioId);

        $this->responderAcao(true, "Solicitação aprovada com sucesso.");
    }

    public function rejeitarSolicitacao()
    {
        $this->loginRequired();
        $this->validarMembro();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Dados inválidos para rejeitar a solicitação.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["projeto_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->service->usuarioECoordenador($projetoId))
        {
            $this->responderAcao(false, "Apenas coordenadores podem rejeitar solicitações.", 403);
        }

        $this->repositorySql->rejeitarSolicitacao($projetoId, $usuarioId);

        $this->responderAcao(true, "Solicitação rejeitada com sucesso.");
    }

    public function alterarPapel()
    {
        $this->loginRequired();
        $this->validarMembro(true);

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Dados inválidos para alterar o papel.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["projeto_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->service->usuarioECoordenador($projetoId))
        {
            $this->responderAcao(false, "Apenas coordenadores podem alterar papéis.", 403);
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projetoAlvo = $this->repositorySql->buscarId($projetoBusca);
        $donoId = (int)($projetoAlvo?->getUsuario()?->getId() ?? 0);

        if ($usuarioId === $donoId && (int)$_SESSION["usuario_logado"]->getId() !== $donoId)
        {
            $this->responderAcao(false, "Apenas o proprietário pode alterar o papel dele no projeto.", 403);
        }

        $resultado = $this->service->alterarPapel($projetoId, $usuarioId, trim($_POST["papel"]));

        if (!$resultado["resposta"])
        {
            $this->responderAcao(false, $resultado["erro"], $resultado["status"]);
        }

        $this->responderAcao(true, "Papel atualizado com sucesso.");
    }

    public function adicionarMembro()
    {
        $this->loginRequired();
        $this->validarMembro(true);

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Dados inválidos para adicionar o membro.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["projeto_id"];
        $usuarioId = (int)$_POST["usuario_id"];

        if (!$this->service->usuarioECoordenador($projetoId))
        {
            $this->responderAcao(false, "Apenas coordenadores podem adicionar membros.", 403);
        }

        $this->repositorySql->adicionarMembro($projetoId, $usuarioId, trim($_POST["papel"] ?? "participante"));

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
            $this->responderAcao(false, "Dados inválidos para remover o membro.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["projeto_id"];
        $usuarioId = (int)$_POST["usuario_id"];
        $ehProprioUsuario = ((int)$_SESSION["usuario_logado"]->getId() === $usuarioId);

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        $donoId = (int)($projeto?->getUsuario()?->getId() ?? 0);

        if (!$ehProprioUsuario && $usuarioId === $donoId)
        {
            $this->responderAcao(false, "O proprietário só pode sair do projeto transferindo a propriedade para outro integrante.", 403);
        }

        if (!$ehProprioUsuario && !($projeto && $this->service->podeGerenciarMembros($projeto)))
        {
            $this->responderAcao(false, "Você não tem permissão para remover este membro.", 403);
        }

        $resultado = $this->service->removerMembro($projetoId, $usuarioId, $novoCoordenador !== "" ? (int)$novoCoordenador : null);

        if (!$resultado["resposta"])
        {
            $this->responderAcao(false, $resultado["erro"], $resultado["status"]);
        }

        if ($ehProprioUsuario)
        {
            $this->responderAcao(true, "Você saiu do projeto.", 200, URL_BASE . "/usuario/perfil");
        }

        $this->responderAcao(true, "Membro removido com sucesso.");
    }

    public function perfil()
    {
        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        $projetoId = (int)$_GET["id"];

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);

        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        if (!$this->service->podeVisualizar($projeto))
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        $todasVersoes = $this->repositorySql->buscarTodasVersoes($projetoId);

        $versaoParam = $_GET["versao"] ?? null;
        $versaoExibida = null;

        if ($versaoParam !== null && $versaoParam !== "" && !empty($todasVersoes))
        {
            foreach ($todasVersoes as $v)
            {
                if ((string)$v["versao"] === (string)$versaoParam)
                {
                    $versaoExibida = $v;
                    break;
                }
            }
        }

        if (!$versaoExibida && !empty($todasVersoes))
        {
            $versaoExibida = $todasVersoes[0];
        }

        $codigos = [];
        $componentes = [];
        $extras = [];

        if ($versaoExibida)
        {
            $versaoId = (int)$versaoExibida["id"];

            $codigos = $this->codigoRepositorySql->buscarPorVersao($versaoId);
            $componentes = $this->componenteRepositorySql->buscarPorVersao($versaoId);
            $extras = $this->extraRepositorySql->buscarPorVersao($versaoId);
        }

        $data["projeto"] = $projeto;
        $data["coordenaEquipe"] = $this->service->coordenaEquipeDoProjeto($projeto);
        $data["comentarios"] = $this->comentarioRepositorySql->buscarPorProjeto($projetoId);
        $data["todasVersoes"] = $todasVersoes;
        $data["versaoExibida"] = $versaoExibida;
        $data["codigos"] = $codigos;
        $data["componentes"] = $componentes;
        $data["extras"] = $extras;
        $data["usuarios"] = $this->usuarioRepositorySql->buscarProjeto($projetoId);
        $data["todosUsuarios"] = $this->usuarioRepositorySql->listarTodos();
        $data["imagens"] = $this->imagemService->buscarProjeto($projetoId);

        $data["solicitacoesPendentes"] = $this->service->usuarioECoordenador($projetoId)
            ? $this->repositorySql->listarSolicitacoesPendentes($projetoId)
            : [];

        $this->view("projeto/perfil", $data);
    }

    public function desativar()
    {
        $this->loginRequired();

        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        $this->validarId();

        if ($this->validador->temErros())
        {
            $this->responderAcao(false, "Projeto inválido.", 422, null, $this->validador->getErros());
        }

        $projetoId = (int)$_POST["id"];

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);

        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            $this->responderAcao(false, "Projeto não encontrado.", 404);
        }

        if (!$this->service->podeDesativar($projeto))
        {
            $this->responderAcao(false, "Você não tem permissão para desativar este projeto.", 403);
        }

        $coordenaEquipe = $this->service->coordenaEquipeDoProjeto($projeto);
        $comJustificativa = $this->exigeJustificativa((int)($projeto->getUsuario()?->getId() ?? 0), $coordenaEquipe);
        $autoridade = match (true) {
            $usuarioLogado->getRegra() === "admin" => "administração",
            $coordenaEquipe => "coordenação da equipe",
            default => "pessoa responsável pelo projeto",
        };
        $justificativa = "";

        if ($comJustificativa)
        {
            $erroJustificativa = $this->erroJustificativa();

            if ($erroJustificativa)
            {
                $this->responderErroJustificativa($erroJustificativa);
            }

            $justificativa = trim((string)($_POST["justificativa"] ?? ""));
        }

        try
        {
            $this->repositorySql->desativar($projetoId);

            $this->denunciaRepositorySql->dispensar(
                "projeto",
                $projetoId
            );

            $usuariosProjeto = $this->usuarioRepositorySql->buscarProjeto($projetoId);

            $donoId = (int)($projeto->getUsuario()?->getId() ?? 0);
            $donoNaLista = count(array_filter($usuariosProjeto, fn($u) => (int)$u->getId() === $donoId)) > 0;

            if ($donoId > 0 && !$donoNaLista)
            {
                $donoBusca = new \app\models\Usuario();
                $donoBusca->setId($donoId);
                $donoCompleto = $this->usuarioRepositorySql->buscarId($donoBusca);

                if ($donoCompleto)
                {
                    $usuariosProjeto[] = $donoCompleto;
                }
            }

            if (!empty($usuariosProjeto))
            {
                $motivoHtml = nl2br(
                    htmlspecialchars($justificativa, ENT_QUOTES, "UTF-8")
                );

                $nomeProjeto = (string)$projeto->getNome();

                foreach ($usuariosProjeto as $integrante)
                {
                    $email = (string)($integrante->getEmail() ?? "");

                    if ($email === "" || (int)$integrante->getId() === (int)$usuarioLogado->getId())
                    {
                        continue;
                    }

                    try
                    {
                        $this->mailController->enviarDesativacaoProjeto(
                            $email,
                            $integrante->getNome(),
                            $nomeProjeto,
                            $motivoHtml,
                            $autoridade
                        );
                    }
                    catch (\Throwable $mailEx)
                    {
                        error_log(
                            "ERRO AO NOTIFICAR DESATIVACAO ({$email}): " .
                            $mailEx->getMessage()
                        );
                    }
                }
            }
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO DESATIVAR PROJETO: " . $e->getMessage());

            $this->responderAcao(false, "Não foi possível desativar o projeto.", 500);
        }

        $equipeProjetoId = (int)($projeto->getEquipe()?->getId() ?? 0);
        $destino = $equipeProjetoId > 0 && !$this->adminRequired()
            ? URL_BASE . "/equipe/perfil?id=" . $equipeProjetoId
            : URL_BASE . "/projeto/listar";

        $this->responderAcao(true, "Projeto desativado com sucesso.", 200, $destino);
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
            $this->responderAcao(false, "Projeto inválido.", 422, null, $this->validador->getErros());
        }

        $id = (int)$_POST["id"];

        $projetoBusca = new Projeto();
        $projetoBusca->setId($id);
        $item = $this->repositorySql->buscarId($projetoBusca, true);

        if (!$item)
        {
            $this->responderAcao(false, "Projeto não encontrado.", 404);
        }

        if ($item->isStatus() !== false)
        {
            $this->responderAcao(false, "Projeto já está ativo.", 422);
        }

        $this->service->ativarProjeto($id);

        $this->responderAcao(true, "Projeto reativado com sucesso.");
    }
}
