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

    public function __construct()
    {
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

    private function usuarioTemPermissao(int $projetoId): bool
    {
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        if (!$usuarioLogado || $projetoId <= 0)
        {
            return false;
        }

        if ($usuarioLogado->getRegra() === "admin")
        {
            return true;
        }

        return $this->repositorySql->usuarioPertenceAoProjeto($projetoId, (int)$usuarioLogado->getId());
    }

    private function usuarioECoordenador(int $projetoId): bool
    {
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        if (!$usuarioLogado || $projetoId <= 0)
        {
            return false;
        }

        if ($usuarioLogado->getRegra() === "admin")
        {
            return true;
        }

        return $this->repositorySql->usuarioECoordenador($projetoId, (int)$usuarioLogado->getId());
    }

    private function possuiArquivosEnviados(array $arquivoCampo): bool
    {
        if (empty($arquivoCampo["name"]) || !is_array($arquivoCampo["name"]))
        {
            return false;
        }

        foreach ($arquivoCampo["name"] as $nome)
        {
            if (trim((string)$nome) !== "")
            {
                return true;
            }
        }

        return false;
    }

    private function validarDadosBaseProjeto(ValidadorHelper $validador, bool $validarVisibilidadeECategoria, bool $exigirSenha): void
    {
        $validador->obrigatorio("nome", trim((string)($_POST["nome"] ?? "")), "O nome do projeto é obrigatório.");
        $validador->tamanho("nome", trim((string)($_POST["nome"] ?? "")), 3, 100, "Nome");

        $validador->obrigatorio("descricao", trim((string)($_POST["descricao"] ?? "")), "A descrição é obrigatória.");
        $validador->tamanho("descricao", trim((string)($_POST["descricao"] ?? "")), 3, 2000, "Descrição");

        if ($exigirSenha)
        {
            $validador->obrigatorio("senha", trim((string)($_POST["senha"] ?? "")), "A senha de acesso ao projeto é obrigatória.");
            $validador->tamanho("senha", trim((string)($_POST["senha"] ?? "")), 6, 100, "Senha");
            $validador->confirmarValor((string)($_POST["senha"] ?? ""), (string)($_POST["confirmar_senha"] ?? ""), "confirmar_senha", "As senhas não conferem.");
        }

        if ($validarVisibilidadeECategoria)
        {
            $categoria = trim((string)($_POST["categoria"] ?? ""));
            $validador->visibilidadeValida(trim((string)($_POST["visibilidade"] ?? "")));
            $validador->obrigatorio("categoria", $categoria, "Selecione ou crie uma categoria.");

            if ($categoria === "criar")
            {
                $novaCategoria = trim((string)($_POST["nova_categoria"] ?? ""));
                $validador->obrigatorio("nova_categoria", $novaCategoria, "Informe o nome da nova categoria.");
                $validador->tamanho("nova_categoria", $novaCategoria, 3, 100, "Nova categoria");
            }
        }
    }

    private function validarComponentes(ValidadorHelper $validador, array $componentesRaw): array
    {
        $map = [];

        foreach ($componentesRaw as $componenteId => $dados)
        {
            $idInt = (int)$componenteId;
            if ($idInt <= 0)
            {
                continue;
            }

            $quantidade = (int)($dados["quantidade"] ?? 0);
            $validador->componenteQuantidade($quantidade);
            $map[$idInt] = $quantidade;
        }

        return $map;
    }

    private function validarDescricoesArquivos(ValidadorHelper $validador, array $arquivos, array $descricoes, string $campoPrefixo, string $rotulo): void
    {
        if (!$this->possuiArquivosEnviados($arquivos))
        {
            return;
        }

        foreach ($arquivos["name"] as $i => $nomeArquivo)
        {
            $nomeLimpo = trim((string)$nomeArquivo);
            if ($nomeLimpo === "")
            {
                continue;
            }

            $desc = trim((string)($descricoes[$i] ?? ""));
            $validador->obrigatorio($campoPrefixo . "_" . $i, $desc, "A descrição de {$rotulo} {$nomeLimpo} é obrigatória.");
            $validador->tamanho($campoPrefixo . "_" . $i, $desc, 3, 255, "Descrição de {$rotulo}");
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

        $validador = new ValidadorHelper();

        $validador->obrigatorio("hash", $hash, "Informe o Hash de acesso do projeto.");
        $validador->obrigatorio("senha", $senha, "Informe a senha do projeto.");

        if ($validador->temErros()) 
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        try 
        {
            $projeto = $this->repositorySql->buscarPorHash($hash);

            if (!$projeto) 
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Código Hash do projeto não encontrado."], 404);
            }

            if (!password_verify($senha, $projeto->getSenha())) 
            {
                $this->responderJson(["sucesso" => false, "erros" => ["senha" => "Senha incorreta para este projeto."]], 422);
            }

            $usuarioLogado = $_SESSION["usuario_logado"] ?? null;
            $solicitou = $this->repositorySql->solicitarEntrada((int)$projeto->getId(), (int)$usuarioLogado->getId());

            if (!$solicitou) 
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Você já é integrante ou possui solicitação pendente para este projeto."], 422);
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

            $this->responderJson(["sucesso" => false, "mensagem" => "Erro ao solicitar entrada no projeto."], 500);
        }
    }

    public function aprovarSolicitacao()
    {
        $this->loginRequired();

        $projetoId = $_POST["projeto_id"] ?? null;
        $usuarioId = $_POST["usuario_id"] ?? null;

        if ($projetoId && $usuarioId && $this->usuarioECoordenador((int)$projetoId)) 
        {
            $this->repositorySql->aprovarSolicitacao((int)$projetoId, (int)$usuarioId);
        }

        $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
    }

    public function rejeitarSolicitacao()
    {
        $this->loginRequired();

        $projetoId = $_POST["projeto_id"] ?? null;
        $usuarioId = $_POST["usuario_id"] ?? null;

        if ($projetoId && $usuarioId && $this->usuarioECoordenador((int)$projetoId)) 
        {
            $this->repositorySql->rejeitarSolicitacao((int)$projetoId, (int)$usuarioId);
        }

        $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
    }

    public function alterarPapel()
    {
        $this->loginRequired();

        $projetoId = $_POST["projeto_id"] ?? null;
        $usuarioId = $_POST["usuario_id"] ?? null;
        $papel = trim((string)($_POST["papel"] ?? ""));

        if (!$projetoId || !$usuarioId || !in_array($papel, ["coordenador", "participante"], true) || !$this->usuarioECoordenador((int)$projetoId)) 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . ($projetoId ?? 0));
            return;
        }

        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        // Coordenador não pode alterar o próprio papel diretamente
        if ($usuarioLogado && (int)$usuarioLogado->getId() === (int)$usuarioId && $usuarioLogado->getRegra() !== "admin") 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
            return;
        }

        $this->repositorySql->atualizarPapelMembro((int)$projetoId, (int)$usuarioId, $papel);

        $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
    }

    public function adicionarMembro()
    {
        $this->loginRequired();

        $projetoId = $_POST["projeto_id"] ?? null;
        $usuarioId = $_POST["usuario_id"] ?? null;
        $papel = trim((string)($_POST["papel"] ?? "participante"));

        if (!in_array($papel, ["coordenador", "participante"], true)) 
        {
            $papel = "participante";
        }

        if (!$projetoId || !$usuarioId || !$this->usuarioECoordenador((int)$projetoId)) 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . ($projetoId ?? 0));
            return;
        }

        $this->repositorySql->adicionarMembro((int)$projetoId, (int)$usuarioId, $papel);

        $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
    }

    public function removerMembro()
    {
        $this->loginRequired();

        $projetoId = $_POST["projeto_id"] ?? null;
        $usuarioId = $_POST["usuario_id"] ?? null;
        $novoCoordenadorId = $_POST["novo_coordenador_id"] ?? null;

        if (!$projetoId || !$usuarioId) 
        {
            $this->redirect(URL_BASE . "/projeto");
            return;
        }

        $usuarioLogadoId = $_SESSION["usuario_logado"]->getId();
        $ehProprioUsuario = ((int)$usuarioLogadoId === (int)$usuarioId);

        // Se não for o próprio usuário saindo, apenas coordenadores podem remover
        if (!$ehProprioUsuario && !$this->usuarioECoordenador((int)$projetoId)) 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
            return;
        }

        $membros = $this->repositorySql->listarMembros((int)$projetoId);

        // Se houver apenas 1 membro no projeto, ninguém pode sair
        if (count($membros) <= 1) 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
            return;
        }

        $coordenadores = array_filter($membros, fn($m) => $m["papel"] === "coordenador");
        $membroAlvo = null;
        foreach ($membros as $m) 
        {
            if ((int)$m["id"] === (int)$usuarioId) 
            {
                $membroAlvo = $m;
                break;
            }
        }

        if (!$membroAlvo) 
        {
            $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId((int)$projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        $ehDonoDoProjeto = ($projeto && (int)$projeto->getUsuario()?->getId() === (int)$usuarioId);
        $ehUnicoCoordenador = ($membroAlvo["papel"] === "coordenador" && count($coordenadores) <= 1);

        // Se for o único coordenador ou o dono do projeto, é obrigatório passar a responsabilidade para outro membro
        if ($ehUnicoCoordenador || $ehDonoDoProjeto) 
        {
            if (!$novoCoordenadorId || (int)$novoCoordenadorId === (int)$usuarioId) 
            {
                $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
                return;
            }

            // Promove o novo membro escolhido para coordenador se era o único coordenador
            if ($ehUnicoCoordenador) 
            {
                $this->repositorySql->atualizarPapelMembro((int)$projetoId, (int)$novoCoordenadorId, "coordenador");
            }

            // Transfere a titularidade do projeto se for o dono
            if ($ehDonoDoProjeto) 
            {
                $this->repositorySql->transferirProprietario((int)$projetoId, (int)$novoCoordenadorId);
            }
        }

        $this->repositorySql->removerMembro((int)$projetoId, (int)$usuarioId);

        if ($ehProprioUsuario) 
        {
            $this->redirect(URL_BASE . "/usuario/perfil");
            return;
        }

        $this->redirect(URL_BASE . "/projeto/perfil?id=" . $projetoId);
    }

    public function listarTodos()
    {
        $this->loginRequired();
        $this->adminRequired();

        $status = (string)($_GET["status"] ?? "1");
        $data["statusFiltro"] = $status;
        $data["projetos"] = ($status === "todos")
            ? $this->repositorySql->listarAdminTodos()
            : $this->repositorySql->listarPorStatus($status === "1");
        $this->view("projeto/list", $data);
    }

    public function listarPublico()
    {
        $busca = trim((string)($_GET["busca"] ?? ""));

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

    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();
        $this->validarDadosBaseProjeto($validador, true, true);

        $mapComponentes = $this->validarComponentes($validador, $_POST["componentes"] ?? []);
        $componentesObj = [];
        foreach ($mapComponentes as $componenteId => $quantidade)
        {
            $comp = new Componente();
            $comp->setId($componenteId);
            $comp->setQuantidade($quantidade);
            $componentesObj[] = $comp;
        }

        $codigos = $_FILES["codigos"] ?? [];
        $codigosDesc = $_POST["codigos_descricao"] ?? [];
        $this->validarDescricoesArquivos($validador, $codigos, $codigosDesc, "codigos_descricao", "do código");

        $extras = $_FILES["extras"] ?? [];
        $extrasDesc = $_POST["extras_descricao"] ?? [];
        $this->validarDescricoesArquivos($validador, $extras, $extrasDesc, "extras_descricao", "do arquivo extra");

        $validador->validarImagens("imagens", $_FILES["imagens"] ?? []);
        $validador->validarCodigos("codigos", $codigos);
        $validador->validarExtras("extras", $extras);

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        try
        {
            $categoriaRaw = trim((string)($_POST["categoria"] ?? ""));
            if ($categoriaRaw === "criar")
            {
                $novaCat = $this->categoriaService->cadastro(["categoria_nome" => trim((string)($_POST["nova_categoria"] ?? ""))]);
                $categoriaId = (int)$novaCat->getId();
            }
            else
            {
                $categoriaId = (int)$categoriaRaw;
            }

            if ($categoriaId <= 0)
            {
                $this->responderJson(["sucesso" => false, "erros" => ["categoria" => "Categoria inválida."]], 422);
            }

            $equipeId = !empty($_POST["equipe_id"]) ? (int)$_POST["equipe_id"] : null;

            $postsProjeto = [
                "nome" => trim((string)($_POST["nome"] ?? "")),
                "descricao" => trim((string)($_POST["descricao"] ?? "")),
                "visibilidade" => trim((string)($_POST["visibilidade"] ?? "")),
                "equipe_id" => $equipeId,
                "categoria_id" => $categoriaId,
                "componentes" => $componentesObj,
                "codigos_descricao" => $codigosDesc,
                "senha" => (string)($_POST["senha"] ?? "")
            ];

            $cadastrados = $this->service->salvarProjeto($postsProjeto);
            $projetoSalvo = $cadastrados[0];
            $projetoVersaoSalvo = $cadastrados[1];

            if (!empty($componentesObj))
            {
                $this->componenteService->associar(["componentes" => $componentesObj, "projeto" => $projetoVersaoSalvo]);
            }

            if ($this->possuiArquivosEnviados($codigos))
            {
                $this->codigoService->salvarCodigo($codigos, $codigosDesc, $projetoVersaoSalvo);
            }

            if ($this->possuiArquivosEnviados($extras))
            {
                $this->extraService->salvarExtras($extras, $extrasDesc, $projetoVersaoSalvo);
            }

            if ($this->possuiArquivosEnviados($_FILES["imagens"] ?? []))
            {
                $this->imagemService->salvarImagem($_FILES["imagens"], $projetoSalvo);
            }

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Projeto cadastrado com sucesso!",
                "redirect" => URL_BASE . "/usuario/perfil"
            ]);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO SALVAR PROJETO: " . $e->getMessage());
            $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível cadastrar o projeto."], 500);
        }
    }

    public function editar()
    {
        $this->loginRequired();

        $projetoId = (int)($_POST["id"] ?? $_GET["id"] ?? 0);

        if ($projetoId <= 0 || !$this->usuarioTemPermissao($projetoId))
        {
            $this->redirect(URL_BASE . "/projeto");
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            $this->redirect(URL_BASE . "/projeto");
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
        $data["categorias"] = $proprietarioId
            ? $this->categoriaService->listarPorUsuario((int)$proprietarioId)
            : $this->categoriaService->listarTodos();
        $data["codigosAtuais"] = $codigosAtuais;
        $data["componentesAtuais"] = $componentesAtuais;
        $data["extrasAtuais"] = $extrasAtuais;
        $data["imagensAtuais"] = $this->imagemService->buscarProjeto($projetoId);
        $data["ehCoordenador"] = $this->usuarioECoordenador($projetoId);

        $this->view("projeto/edit", $data);
    }

    public function atualizar()
    {
        $this->loginRequired();

        $projetoId = (int)($_POST["id"] ?? 0);

        if ($projetoId <= 0)
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "ID do projeto inválido."], 422);
        }

        if (!$this->usuarioTemPermissao($projetoId))
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Você não tem permissão para alterar este projeto."], 403);
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projetoExistente = $this->repositorySql->buscarId($projetoBusca);

        if (!$projetoExistente || $projetoExistente->getNome() === null)
        {
            $this->responderJson(["sucesso" => false, "mensagem" => "Projeto não encontrado."], 404);
        }

        $ehCoordenador = $this->usuarioECoordenador($projetoId);
        $proprietarioId = (int)($projetoExistente->getUsuario()?->getId() ?? 0);

        $validador = new ValidadorHelper();
        $this->validarDadosBaseProjeto($validador, $ehCoordenador, false);
        $mapForm = $this->validarComponentes($validador, $_POST["componentes"] ?? []);

        $novosCodigos = $_FILES["codigos"] ?? [];
        $novosCodigosDesc = $_POST["codigos_descricao"] ?? [];
        $this->validarDescricoesArquivos($validador, $novosCodigos, $novosCodigosDesc, "codigos_descricao", "do código");

        $novosExtras = $_FILES["extras"] ?? [];
        $novosExtrasDesc = $_POST["extras_descricao"] ?? [];
        $this->validarDescricoesArquivos($validador, $novosExtras, $novosExtrasDesc, "extras_descricao", "do arquivo extra");

        $validador->validarImagens("imagens", $_FILES["imagens"] ?? []);
        $validador->validarCodigos("codigos", $novosCodigos);
        $validador->validarExtras("extras", $novosExtras);

        if ($validador->temErros())
        {
            $this->responderJson(["sucesso" => false, "erros" => $validador->getErros()], 422);
        }

        try
        {
            $versaoAtual = $this->repositorySql->buscarUltimaVersao($projetoId);
            $versaoAtualId = (int)($versaoAtual["id"] ?? 0);

            $codigosBanco = $versaoAtualId > 0 ? $this->codigoRepositorySql->buscarPorVersao($versaoAtualId) : [];
            $idsCodigosBanco = array_values(array_unique(array_map(fn($c) => (int)$c->getId(), $codigosBanco)));
            $codigosManter = array_values(array_unique(array_map("intval", $_POST["codigos_manter"] ?? [])));

            $extrasBanco = $versaoAtualId > 0 ? $this->extraRepositorySql->buscarPorVersao($versaoAtualId) : [];
            $idsExtrasBanco = array_values(array_unique(array_map(fn($e) => (int)$e->getId(), $extrasBanco)));
            $extrasManter = array_values(array_unique(array_map("intval", $_POST["extras_manter"] ?? [])));

            sort($idsCodigosBanco, SORT_NUMERIC);
            sort($codigosManter, SORT_NUMERIC);
            sort($idsExtrasBanco, SORT_NUMERIC);
            sort($extrasManter, SORT_NUMERIC);

            $temNovosCodigos = $this->possuiArquivosEnviados($novosCodigos);
            $mudouCodigos = $temNovosCodigos || ($idsCodigosBanco !== $codigosManter);

            $temNovosExtras = $this->possuiArquivosEnviados($novosExtras);
            $mudouExtras = $temNovosExtras || ($idsExtrasBanco !== $extrasManter);

            $compsBanco = $versaoAtualId > 0 ? $this->componenteRepositorySql->buscarPorVersao($versaoAtualId) : [];
            $mapBanco = [];
            foreach ($compsBanco as $comp)
            {
                $mapBanco[(int)$comp->getId()] = (int)$comp->getQuantidade();
            }

            ksort($mapBanco, SORT_NUMERIC);
            ksort($mapForm, SORT_NUMERIC);
            $mudouComponentes = ($mapBanco !== $mapForm);

            if ($mudouCodigos || $mudouComponentes || $mudouExtras)
            {
                $novaVersaoNum = (int)($versaoAtual["versao"] ?? 0) + 1;
                $nota = trim((string)($_POST["descricao_alteracao"] ?? "")) ?: "Atualização para versão v{$novaVersaoNum}";
                $usuarioLogado = $_SESSION["usuario_logado"];

                $novaVersaoId = (int)$this->repositorySql->criarVersao($projetoId, (int)$usuarioLogado->getId(), $novaVersaoNum, $nota);

                foreach ($codigosManter as $codigoId)
                {
                    $this->codigoRepositorySql->associarVersao($codigoId, $novaVersaoId);
                }

                if ($temNovosCodigos)
                {
                    $versaoObj = new Projeto();
                    $versaoObj->setId($novaVersaoId);
                    $this->codigoService->salvarCodigo($novosCodigos, $novosCodigosDesc, $versaoObj);
                }

                foreach ($extrasManter as $extraId)
                {
                    $this->extraRepositorySql->associarVersao($extraId, $novaVersaoId);
                }

                if ($temNovosExtras)
                {
                    $versaoObjExtra = new Projeto();
                    $versaoObjExtra->setId($novaVersaoId);
                    $this->extraService->salvarExtras($novosExtras, $novosExtrasDesc, $versaoObjExtra);
                }

                $componentesObj = [];
                foreach ($mapForm as $componenteId => $qtd)
                {
                    $comp = new Componente();
                    $comp->setId($componenteId);
                    $comp->setQuantidade($qtd);
                    $componentesObj[] = $comp;
                }

                if (!empty($componentesObj))
                {
                    $novaVersaoObj = new Projeto();
                    $novaVersaoObj->setId($novaVersaoId);
                    $this->componenteService->associar(["componentes" => $componentesObj, "projeto" => $novaVersaoObj]);
                }
            }

            if ($ehCoordenador)
            {
                $categoriaRaw = trim((string)($_POST["categoria"] ?? ""));
                if ($categoriaRaw === "criar")
                {
                    $novaCat = $this->categoriaService->cadastro(["categoria_nome" => trim((string)($_POST["nova_categoria"] ?? ""))], $proprietarioId);
                    $categoriaId = (int)$novaCat->getId();
                }
                else
                {
                    $categoriaId = (int)$categoriaRaw;
                }

                if ($categoriaId <= 0)
                {
                    $this->responderJson(["sucesso" => false, "erros" => ["categoria" => "Categoria inválida."]], 422);
                }

                $visibilidade = trim((string)($_POST["visibilidade"] ?? $projetoExistente->getVisibilidade()));
            }
            else
            {
                $categoriaId = (int)($projetoExistente->getCategoria()?->getId() ?? 0);
                $visibilidade = (string)$projetoExistente->getVisibilidade();
            }

            $projetoObj = new Projeto();
            $projetoObj->setId($projetoId);
            $projetoObj->setNome(trim((string)($_POST["nome"] ?? "")));
            $projetoObj->setDescricao(trim((string)($_POST["descricao"] ?? "")));
            $projetoObj->setVisibilidade($visibilidade);

            if (!empty($_POST["equipe_id"]))
            {
                $equipe = new Equipe();
                $equipe->setId((int)$_POST["equipe_id"]);
                $projetoObj->setEquipe($equipe);
            }

            $catObj = new Categoria();
            $catObj->setId($categoriaId);
            $projetoObj->setCategoria($catObj);

            $this->service->editarProjeto($projetoObj);

            if ($this->possuiArquivosEnviados($_FILES["imagens"] ?? []))
            {
                $this->imagemService->salvarImagem($_FILES["imagens"], $projetoObj);
            }

            $imagensManter = array_values(array_unique(array_map("intval", $_POST["imagens_manter"] ?? [])));
            $this->imagemRepositorySql->desativarImagensExceto($projetoId, $imagensManter);

            $this->responderJson([
                "sucesso" => true,
                "mensagem" => "Projeto atualizado com sucesso!",
                "redirect" => URL_BASE . "/projeto/perfil?id=" . $projetoId
            ]);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO ATUALIZAR PROJETO: " . $e->getMessage());
            $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível atualizar o projeto."], 500);
        }
    }

    public function perfil()
    {
        $projetoId = (int)($_GET["id"] ?? 0);

        if ($projetoId <= 0)
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            $this->redirect(URL_BASE . "/projeto/listar");
            return;
        }

        if ($projeto->getVisibilidade() === "privado" && !$this->usuarioTemPermissao($projetoId))
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
        $data["comentarios"] = $this->comentarioRepositorySql->buscarPorProjeto($projetoId);
        $data["todasVersoes"] = $todasVersoes;
        $data["versaoExibida"] = $versaoExibida;
        $data["codigos"] = $codigos;
        $data["componentes"] = $componentes;
        $data["extras"] = $extras;
        $data["usuarios"] = $this->usuarioRepositorySql->buscarProjeto($projetoId);
        $data["todosUsuarios"] = $this->usuarioRepositorySql->listarTodos();
        $data["imagens"] = $this->imagemService->buscarProjeto($projetoId);
        $data["solicitacoesPendentes"] = $this->usuarioECoordenador($projetoId)
            ? $this->repositorySql->listarSolicitacoesPendentes($projetoId)
            : [];

        $this->view("projeto/perfil", $data);
    }

    public function desativar()
    {
        $this->loginRequired();

        $projetoId = (int)($_POST["id"] ?? 0);
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;
        $isAjax = (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string)$_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest");
        $fallbackUrl = URL_BASE . "/projeto/listar";

        if ($projetoId <= 0 || !$usuarioLogado || $usuarioLogado->getRegra() !== "admin")
        {
            if ($isAjax)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Acesso negado."], 403);
            }
            $this->redirect($fallbackUrl);
            return;
        }

        $justificativa = trim((string)($_POST["justificativa"] ?? ""));

        if (mb_strlen($justificativa) < 10)
        {
            if ($isAjax)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "A justificativa é obrigatória (mínimo de 10 caracteres)."], 422);
            }
            $this->redirect($fallbackUrl);
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            if ($isAjax)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Projeto não encontrado."], 404);
            }
            $this->redirect($fallbackUrl);
            return;
        }

        try
        {
            $this->repositorySql->desativar($projetoId);
            $this->denunciaRepositorySql->dispensar("projeto", $projetoId);

            $usuariosProjeto = $this->usuarioRepositorySql->buscarProjeto($projetoId);

            if (!empty($usuariosProjeto))
            {
                $motivoHtml = nl2br(htmlspecialchars($justificativa, ENT_QUOTES, "UTF-8"));
                $nomeProjeto = (string)$projeto->getNome();

                foreach ($usuariosProjeto as $integrante)
                {
                    $email = (string)($integrante->getEmail() ?? "");
                    if ($email === "")
                    {
                        continue;
                    }

                    try
                    {
                        $this->mailController->enviarDesativacaoProjeto($email, $integrante->getNome(), $nomeProjeto, $motivoHtml);
                    }
                    catch (\Throwable $mailEx)
                    {
                        error_log("ERRO AO NOTIFICAR DESATIVACAO ({$email}): " . $mailEx->getMessage());
                    }
                }
            }

            if ($isAjax)
            {
                $this->responderJson(["sucesso" => true, "mensagem" => "Projeto desativado com sucesso."]);
            }

            $this->redirect($fallbackUrl);
        }
        catch (\Throwable $e)
        {
            error_log("ERRO AO DESATIVAR PROJETO: " . $e->getMessage());
            if ($isAjax)
            {
                $this->responderJson(["sucesso" => false, "mensagem" => "Não foi possível desativar o projeto."], 500);
            }
            $this->redirect($fallbackUrl);
        }
    }
}