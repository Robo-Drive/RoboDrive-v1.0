<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Componente;
use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\ComponenteService;
use app\services\CategoriaService;
use app\services\ProjetoService;
use app\services\CodigoService;
use app\services\ImagemService;

class ProjetoController extends Controller
{
    private ProjetoService $service;
    private ProjetoRepositorySql $repositorySql;
    private ComponenteService $componenteService;
    private CategoriaService $categoriaService;
    private UsuarioRepositorySql $usuarioRepositorySql;

    public function __construct()
    {
        $this->service = new ProjetoService();
        $this->repositorySql = new ProjetoRepositorySql();
        $this->componenteService = new ComponenteService();
        $this->categoriaService = new CategoriaService();
        $this->usuarioRepositorySql = new UsuarioRepositorySql();
    }

    /*
     * Retorna uma resposta JSON e encerra a execução.
     */
    private function responderJson(array $dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $dados,
            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        );

        exit;
    }

    /*
     * Obtém um ID inteiro válido do POST ou GET.
     */
    private function obterId(array $origens, string $campo): ?int
    {
        foreach ($origens as $origem) {
            if (!isset($origem[$campo])) {
                continue;
            }

            $id = filter_var($origem[$campo], FILTER_VALIDATE_INT);

            if ($id !== false && $id > 0) {
                return (int) $id;
            }
        }

        return null;
    }

    private function visibilidadeValida(string $visibilidade): bool
    {
        return in_array(
            $visibilidade,
            ['privado', 'publico', 'equipe'],
            true
        );
    }

    /*
     * Verifica se o usuário logado pode acessar/alterar o projeto.
     * Administradores têm acesso irrestrito.
     */
    private function usuarioTemPermissao(int $projetoId): bool
    {
        if ($projetoId <= 0) {
            return false;
        }

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        if (!$usuarioLogado) {
            return false;
        }

        if ($usuarioLogado->getRegra() === 'admin') {
            return true;
        }

        return $this->repositorySql->usuarioPertenceAoProjeto(
            $projetoId,
            (int) $usuarioLogado->getId()
        );
    }

    /*
     * Verifica se o usuário é coordenador do projeto ou administrador.
     */
    private function usuarioECoordenador(int $projetoId): bool
    {
        if ($projetoId <= 0) {
            return false;
        }

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        if (!$usuarioLogado) {
            return false;
        }

        if ($usuarioLogado->getRegra() === 'admin') {
            return true;
        }

        return $this->repositorySql->usuarioECoordenador(
            $projetoId,
            (int) $usuarioLogado->getId()
        );
    }

    /*
     * Lista os projetos públicos.
     */
    public function listar()
    {
        $this->loginRequired();

        $data['projetos'] = $this->repositorySql->listarPublico();

        $this->view('projeto/list', $data);
    }

    /*
     * Exibe o formulário de cadastro.
     */
    public function cadastrar()
    {
        $this->loginRequired();

        $data['componentes'] = $this->componenteService->listarTodos();
        $data['categorias'] = $this->categoriaService->listarTodos();

        $this->view('projeto/create', $data);
    }

    /*
     * Cadastra um projeto.
     */
    public function salvar()
    {
        $this->loginRequired();

        $validador = new ValidadorHelper();

        $nome = trim(
            (string) ($_POST['nome'] ?? '')
        );

        $descricao = trim(
            (string) ($_POST['descricao'] ?? '')
        );

        $visibilidade = trim(
            (string) ($_POST['visibilidade'] ?? '')
        );

        $senhaProjeto = (string) ($_POST['senha'] ?? '');
        $confirmarSenhaProjeto = (string) ($_POST['confirmar_senha'] ?? '');

        $categoriaPost = trim(
            (string) ($_POST['categoria'] ?? '')
        );

        $novaCategoria = trim(
            (string) ($_POST['novaCategoria'] ?? '')
        );

        $equipeIdPost = null;

        if (!empty($_POST['equipe_id'])) {
            $equipeIdPost = filter_var(
                $_POST['equipe_id'],
                FILTER_VALIDATE_INT
            );

            if ($equipeIdPost === false || $equipeIdPost <= 0) {
                $validador->obrigatorio(
                    'equipe_id',
                    '',
                    'Equipe inválida.'
                );
            } else {
                $equipeIdPost = (int) $equipeIdPost;
            }
        }

        // Senha
        $validador->obrigatorio(
            'senha',
            $senhaProjeto,
            'A senha de acesso ao projeto é obrigatória.'
        );

        $validador->tamanho(
            'senha',
            $senhaProjeto,
            8,
            100,
            'Senha do projeto'
        );

        if ($senhaProjeto !== $confirmarSenhaProjeto) {
            $validador->obrigatorio(
                'confirmar_senha',
                '',
                'As senhas do projeto não conferem.'
            );
        }

        // Nome
        $validador->obrigatorio(
            'nome',
            $nome,
            'O nome do projeto é obrigatório.'
        );

        $validador->tamanho(
            'nome',
            $nome,
            3,
            100,
            'Nome'
        );

        // Descrição
        $validador->obrigatorio(
            'descricao',
            $descricao,
            'A descrição é obrigatória.'
        );

        $validador->tamanho(
            'descricao',
            $descricao,
            3,
            2000,
            'Descrição'
        );

        // Visibilidade
        if (!$this->visibilidadeValida($visibilidade)) {
            $validador->obrigatorio(
                'visibilidade',
                '',
                'Selecione uma visibilidade válida.'
            );
        }

        // Categoria
        if ($categoriaPost === '') {
            $validador->obrigatorio(
                'categoria',
                '',
                'Selecione ou crie uma categoria.'
            );
        } elseif ($categoriaPost === 'criar') {
            $validador->obrigatorio(
                'novaCategoria',
                $novaCategoria,
                'Informe o nome da nova categoria.'
            );

            $validador->tamanho(
                'novaCategoria',
                $novaCategoria,
                3,
                100,
                'Nova categoria'
            );
        } else {
            $categoriaIdValidada = filter_var(
                $categoriaPost,
                FILTER_VALIDATE_INT
            );

            if ($categoriaIdValidada === false || $categoriaIdValidada <= 0) {
                $validador->obrigatorio(
                    'categoria',
                    '',
                    'Categoria inválida.'
                );
            } else {
                $categoriaPost = (string) $categoriaIdValidada;
            }
        }

        // Componentes
        $componentesRaw = $_POST['componentes'] ?? [];
        $componentesObj = [];

        if (!is_array($componentesRaw)) {
            $componentesRaw = [];

            $validador->obrigatorio(
                'componentes',
                '',
                'Os componentes enviados são inválidos.'
            );
        }

        foreach ($componentesRaw as $componenteId => $dados) {
            if (!is_array($dados)) {
                $validador->obrigatorio(
                    'componentes',
                    '',
                    'Dados de componente inválidos.'
                );
                continue;
            }

            $idValidado = filter_var(
                $componenteId,
                FILTER_VALIDATE_INT
            );

            if ($idValidado === false || $idValidado <= 0) {
                $validador->obrigatorio(
                    'componentes',
                    '',
                    'ID de componente inválido.'
                );
                continue;
            }

            $qtd = $dados['quantidade'] ?? 0;

            $validador->componenteQuantidade($qtd);

            $componente = new Componente();
            $componente->setId((int) $idValidado);
            $componente->setQuantidade($qtd);

            $componentesObj[] = $componente;
        }

        // Descrições dos códigos
        $codigosDesc = $_POST['codigos_descricao'] ?? [];

        if (!is_array($codigosDesc)) {
            $codigosDesc = [];

            $validador->obrigatorio(
                'codigos_descricao',
                '',
                'As descrições dos códigos são inválidas.'
            );
        }

        foreach ($codigosDesc as $i => $descricaoCodigo) {
            $descricaoCodigo = trim((string) $descricaoCodigo);
            $campo = 'codigos_descricao_' . $i;

            $validador->obrigatorio(
                $campo,
                $descricaoCodigo,
                'A descrição do código é obrigatória.'
            );

            $validador->tamanho(
                $campo,
                $descricaoCodigo,
                3,
                255,
                'Descrição do código'
            );
            
            
            $codigosDesc[$i] = $descricaoCodigo;
        }
        // Validação das descrições dos arquivos extras
        $extrasDesc = $_POST['extras_descricao'] ?? [];
        if (!is_array($extrasDesc)) {
            $extrasDesc = [];
        }

        // Se foram enviados arquivos extras, cada um deve ter sua descrição
        if (!empty($_FILES['extras']['name'][0])) {
            foreach ($_FILES['extras']['name'] as $i => $nomeExtra) {
                if (empty($nomeExtra)) continue;

                $descricaoExtra = trim((string)($extrasDesc[$i] ?? ''));
                $campo = 'extras_descricao_' . $i;

                $validador->obrigatorio(
                    $campo,
                    $descricaoExtra,
                    'A descrição do arquivo extra "' . $nomeExtra . '" é obrigatória.'
                );

                $validador->tamanho(
                    $campo,
                    $descricaoExtra,
                    3,
                    255,
                    'Descrição do arquivo extra'
                );

                $extrasDesc[$i] = $descricaoExtra;
            }
        }

                // Validação estrita por categoria
        $validador->validarImagens('imagens', $_FILES['imagens'] ?? []);
        $validador->validarCodigos('codigos', $_FILES['codigos'] ?? []);
        $validador->validarExtras('extras', $_FILES['extras'] ?? []);

        if ($validador->temErros()) {
            $this->responderJson([
                'sucesso' => false,
                'erros' => $validador->getErros()
            ], 422);
        }

        try {
            // Obtém ou cadastra a categoria.
            if ($categoriaPost === 'criar') {
                $novaCatObj = $this->categoriaService->cadastro([
                    'categoria_nome' => $novaCategoria
                ]);

                if (!$novaCatObj || !$novaCatObj->getId()) {
                    throw new \RuntimeException(
                        'Não foi possível cadastrar a categoria.'
                    );
                }

                $categoriaId = (int) $novaCatObj->getId();
            } else {
                $categoriaId = (int) $categoriaPost;
            }
            $extrasDesc = $_POST['extras_descricao'] ?? [];
            if (!is_array($extrasDesc)) { $extrasDesc = []; }

            $postsProjeto = [
                'nome' => $nome,
                'descricao' => $descricao,
                'visibilidade' => $visibilidade,
                'equipe_id' => $equipeIdPost,
                'categoria_id' => $categoriaId,
                'componentes' => $componentesObj,
                'codigos_descricao' => $codigosDesc,
                'senha' => $senhaProjeto
            ];

            $cadastrados = $this->service->salvarProjeto($postsProjeto);

            if (
                !is_array($cadastrados)
                || !isset($cadastrados[0], $cadastrados[1])
            ) {
                throw new \RuntimeException(
                    'O cadastro do projeto não retornou os dados esperados.'
                );
            }

            $projetoSalvo = $cadastrados[0];
            $projetoVersaoSalvo = $cadastrados[1];

            // Associa componentes à versão.
            if (!empty($componentesObj)) {
                $this->componenteService->associar([
                    'componentes' => $componentesObj,
                    'projeto' => $projetoVersaoSalvo
                ]);
            }

            // Salva códigos.
            if (!empty($_FILES['codigos']['name'][0])) {
                (new CodigoService())->salvarCodigo(
                    $_FILES['codigos'],
                    $codigosDesc,
                    $projetoVersaoSalvo
                );
            }

            // Salva imagens.
            if (!empty($_FILES['imagens']['name'][0])) {
                (new ImagemService())->salvarImagem(
                    $_FILES['imagens'],
                    $projetoSalvo
                );
            }
                        // Salva arquivos extras / documentações
            if (!empty($_FILES['extras']['name'][0])) {
                (new \app\services\ExtraService())->salvarExtras(
                    $_FILES['extras'],
                    $extrasDesc,
                    $projetoVersaoSalvo
                );
            }
            $this->responderJson([
                'sucesso' => true,
                'mensagem' => 'Projeto cadastrado com sucesso!',
                'redirect' => URL_BASE . '/usuario/perfil'
            ]);
        } catch (\Throwable $e) {
            error_log(
                "ERRO AO SALVAR PROJETO:\n" . (string) $e
            );

            $this->responderJson([
                'sucesso' => false,
                'mensagem' => 'Não foi possível cadastrar o projeto.'
            ], 500);
        }
    }

    /*
     * Exibe o formulário de edição.
     */
    public function editar()
    {
        $this->loginRequired();

        $id = $this->obterId(
            [$_POST, $_GET],
            'id'
        );

        if (!$id || !$this->usuarioTemPermissao($id)) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($id);

        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        $versaoAtual = $this->repositorySql->buscarUltimaVersao($id);

        $codigosAtuais = [];
        $componentesAtuais = [];
        $codigosAtuais = [];
        $componentesAtuais = [];
        $extrasAtuais = [];

        if ($versaoAtual) {
            $codigosAtuais = (new \app\repositories\CodigoRepositorySql())
                ->buscarPorVersao((int) $versaoAtual['id']);

            $componentesAtuais = (new \app\repositories\ComponenteRepositorySql())
                ->buscarPorVersao((int) $versaoAtual['id']);

            $extrasAtuais = (new \app\repositories\ExtraRepositorySql())
                ->buscarPorVersao((int) $versaoAtual['id']);
        }

        $data['projeto'] = $projeto;
        $data['componentes'] = $this->componenteService->listarTodos();
        $data['categorias'] = $this->categoriaService->listarTodos();
        $data['codigosAtuais'] = $codigosAtuais;
        $data['componentesAtuais'] = $componentesAtuais;
        $data['extrasAtuais'] = $extrasAtuais;
        $data['imagensAtuais'] = (new ImagemService())->buscarProjeto($id);

        
        $this->view('projeto/edit', $data);
    }

        /*
     * Atualiza os dados gerais e, se necessário, cria uma nova versão.
     */
    public function atualizar()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'id');

        if (!$projetoId) {
            $this->responderJson([
                'sucesso' => false,
                'mensagem' => 'ID do projeto inválido.'
            ], 422);
        }

        if (!$this->usuarioTemPermissao($projetoId)) {
            $this->responderJson([
                'sucesso' => false,
                'mensagem' => 'Você não tem permissão para alterar este projeto.'
            ], 403);
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);

        $projetoExistente = $this->repositorySql->buscarId($projetoBusca);

        if (!$projetoExistente || $projetoExistente->getNome() === null) {
            $this->responderJson([
                'sucesso' => false,
                'mensagem' => 'Projeto não encontrado.'
            ], 404);
        }

        $validador = new ValidadorHelper();

        $nome = trim((string) ($_POST['nome'] ?? ''));
        $descricao = trim((string) ($_POST['descricao'] ?? ''));
        $visibilidade = trim((string) ($_POST['visibilidade'] ?? ''));
        $categoriaPost = $_POST['categoria'] ?? '';
        $descricaoAlteracao = trim(
            (string) ($_POST['descricao_alteracao'] ?? '')
        );

        $equipeIdPost = filter_input(
            INPUT_POST,
            'equipe_id',
            FILTER_VALIDATE_INT
        ) ?: null;

        $novaCategoria = trim(
            (string) ($_POST['novaCategoria'] ?? '')
        );

        $componentesRaw = $_POST['componentes'] ?? [];

        // Códigos
        $novosCodigos = $_FILES['codigos'] ?? [];
        $novosCodigosDesc = $_POST['codigos_descricao'] ?? [];
        $codigosManter = $_POST['codigos_manter'] ?? [];

        if (!is_array($codigosManter)) {
            $codigosManter = [];
        }

        // Extras
        $novosExtras = $_FILES['extras'] ?? [];
        $novosExtrasDesc = $_POST['extras_descricao'] ?? [];
        $extrasManter = $_POST['extras_manter'] ?? [];

        if (!is_array($extrasManter)) {
            $extrasManter = [];
        }

        // Validação de Nome
        $validador->obrigatorio(
            'nome',
            $nome,
            'O nome do projeto é obrigatório.'
        );

        $validador->tamanho(
            'nome',
            $nome,
            3,
            100,
            'Nome'
        );

        // Validação de Descrição
        $validador->obrigatorio(
            'descricao',
            $descricao,
            'A descrição é obrigatória.'
        );

        $validador->tamanho(
            'descricao',
            $descricao,
            3,
            2000,
            'Descrição'
        );

        // Validação de Visibilidade
        if (!$this->visibilidadeValida($visibilidade)) {
            $validador->obrigatorio(
                'visibilidade',
                '',
                'Selecione uma visibilidade válida.'
            );
        }

        // Validação de Categoria
        if ($categoriaPost === '') {
            $validador->obrigatorio(
                'categoria',
                '',
                'Selecione ou crie uma categoria.'
            );
        } elseif ($categoriaPost === 'criar') {
            $validador->obrigatorio(
                'novaCategoria',
                $novaCategoria,
                'Informe o nome da nova categoria.'
            );

            $validador->tamanho(
                'novaCategoria',
                $novaCategoria,
                3,
                100,
                'Nova categoria'
            );
        } else {
            $categoriaIdValidada = filter_var(
                $categoriaPost,
                FILTER_VALIDATE_INT
            );

            if ($categoriaIdValidada === false || $categoriaIdValidada <= 0) {
                $validador->obrigatorio(
                    'categoria',
                    '',
                    'Categoria inválida.'
                );
            }
        }

        // Validação de Componentes
        if (!is_array($componentesRaw)) {
            $componentesRaw = [];
        }

        $mapForm = [];
                // Validação das descrições dos novos arquivos extras (na edição)
        if (!empty($novosExtras['name'][0])) {
            foreach ($novosExtras['name'] as $i => $nomeExtra) {
                if (empty($nomeExtra)) continue;

                $descricaoExtra = trim((string)($novosExtrasDesc[$i] ?? ''));
                $campo = 'extras_descricao_' . $i;

                $validador->obrigatorio(
                    $campo,
                    $descricaoExtra,
                    'A descrição do arquivo extra "' . $nomeExtra . '" é obrigatória.'
                );

                $validador->tamanho(
                    $campo,
                    $descricaoExtra,
                    3,
                    255,
                    'Descrição do arquivo extra'
                );

                $novosExtrasDesc[$i] = $descricaoExtra;
            }
        }

        foreach ($componentesRaw as $componenteId => $dados) {
            $idValidado = filter_var(
                $componenteId,
                FILTER_VALIDATE_INT
            );

            if ($idValidado === false || $idValidado <= 0) {
                $validador->obrigatorio(
                    'componentes',
                    '',
                    'ID de componente inválido.'
                );
                continue;
            }

            $qtd = $dados['quantidade'] ?? 0;

            $validador->componenteQuantidade($qtd);

            if (
                filter_var($qtd, FILTER_VALIDATE_INT) === false
                || (int) $qtd <= 0
            ) {
                continue;
            }

            $mapForm[(int) $idValidado] = (int) $qtd;
        }

        // Validação estrita por categoria de arquivos
        $validador->validarImagens('imagens', $_FILES['imagens'] ?? []);
        $validador->validarCodigos('codigos', $_FILES['codigos'] ?? []);
        $validador->validarExtras('extras', $_FILES['extras'] ?? []);

        if ($validador->temErros()) {
            $this->responderJson([
                'sucesso' => false,
                'erros' => $validador->getErros()
            ], 422);
        }

        try {
            // Busca a versão atual do projeto
            $versaoAtual = $this->repositorySql->buscarUltimaVersao($projetoId);

            $versaoAtualId = $versaoAtual
                ? (int) $versaoAtual['id']
                : 0;

            $codigoRepo = new \app\repositories\CodigoRepositorySql();
            $compRepo   = new \app\repositories\ComponenteRepositorySql();
            $extraRepo  = new \app\repositories\ExtraRepositorySql();

            // 1. Códigos atualmente associados
            $codigosBanco = $versaoAtualId
                ? $codigoRepo->buscarPorVersao($versaoAtualId)
                : [];

            $idsCodigosBanco = array_map(
                fn($codigo) => (int) $codigo->getId(),
                $codigosBanco
            );

            $codigosManterInt = [];
            foreach ($codigosManter as $codigoId) {
                $codigoIdValidado = filter_var($codigoId, FILTER_VALIDATE_INT);
                if ($codigoIdValidado !== false && $codigoIdValidado > 0) {
                    $codigosManterInt[] = (int) $codigoIdValidado;
                }
            }
            $codigosManterInt = array_values(array_unique($codigosManterInt));

            $codigosNaoPertencentes = array_diff($codigosManterInt, $idsCodigosBanco);
            if (!empty($codigosNaoPertencentes)) {
                $this->responderJson([
                    'sucesso' => false,
                    'mensagem' => 'Um ou mais códigos não pertencem a este projeto.'
                ], 403);
            }

            // 2. Extras atualmente associados
            $extrasBanco = $versaoAtualId
                ? $extraRepo->buscarPorVersao($versaoAtualId)
                : [];

            $idsExtrasBanco = array_map(
                fn($extra) => (int) $extra->getId(),
                $extrasBanco
            );

            $extrasManterInt = [];
            foreach ($extrasManter as $extraId) {
                $extraIdValidado = filter_var($extraId, FILTER_VALIDATE_INT);
                if ($extraIdValidado !== false && $extraIdValidado > 0) {
                    $extrasManterInt[] = (int) $extraIdValidado;
                }
            }
            $extrasManterInt = array_values(array_unique($extrasManterInt));

            $extrasNaoPertencentes = array_diff($extrasManterInt, $idsExtrasBanco);
            if (!empty($extrasNaoPertencentes)) {
                $this->responderJson([
                    'sucesso' => false,
                    'mensagem' => 'Um ou mais arquivos extras não pertencem a este projeto.'
                ], 403);
            }

            sort($idsCodigosBanco);
            sort($codigosManterInt);
            sort($idsExtrasBanco);
            sort($extrasManterInt);

            // Verifica se houve alteração em Códigos
            $temNovosCodigos = !empty($novosCodigos['name'][0])
                && trim((string) $novosCodigos['name'][0]) !== '';
            $mudouCodigos = $temNovosCodigos || ($idsCodigosBanco !== $codigosManterInt);

            // Verifica se houve alteração em Extras
            $temNovosExtras = !empty($novosExtras['name'][0])
                && trim((string) $novosExtras['name'][0]) !== '';
            $mudouExtras = $temNovosExtras || ($idsExtrasBanco !== $extrasManterInt);

            // Verifica se houve alteração em Componentes
            $compsBanco = $versaoAtualId
                ? $compRepo->buscarPorVersao($versaoAtualId)
                : [];

            $mapBanco = [];
            foreach ($compsBanco as $componente) {
                $mapBanco[(int) $componente->getId()] = (int) $componente->getQuantidade();
            }

            ksort($mapBanco);
            ksort($mapForm);

            $mudouComponentes = ($mapBanco !== $mapForm);

            // Cria nova versão quando códigos, componentes OU extras mudarem
            if ($mudouCodigos || $mudouComponentes || $mudouExtras) {
                $novaVersaoNum = (
                    $versaoAtual
                    ? (int) $versaoAtual['versao']
                    : 0
                ) + 1;

                $nota = $descricaoAlteracao !== ''
                    ? $descricaoAlteracao
                    : "Atualização para versão v{$novaVersaoNum}";

                $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

                if (!$usuarioLogado) {
                    throw new \RuntimeException(
                        'Usuário não autenticado.'
                    );
                }

                $novaVersaoId = $this->repositorySql->criarVersao(
                    $projetoId,
                    (int) $usuarioLogado->getId(),
                    $novaVersaoNum,
                    $nota
                );

                if (!$novaVersaoId) {
                    throw new \RuntimeException(
                        'Não foi possível criar a nova versão.'
                    );
                }

                // 1. Reassocia os códigos antigos mantidos
                foreach ($codigosManterInt as $codigoId) {
                    $codigoRepo->associarVersao(
                        $codigoId,
                        $novaVersaoId
                    );
                }

                // Salva novos códigos enviados
                if ($temNovosCodigos) {
                    $versaoObj = new Projeto();
                    $versaoObj->setId((int) $novaVersaoId);

                    (new CodigoService())->salvarCodigo(
                        $novosCodigos,
                        $novosCodigosDesc,
                        $versaoObj
                    );
                }

                // 2. Reassocia os extras antigos mantidos
                foreach ($extrasManterInt as $extraId) {
                    $extraRepo->associarVersao(
                        $extraId,
                        $novaVersaoId
                    );
                }

                // Salva novos arquivos extras enviados
                if ($temNovosExtras) {
                    $versaoObjExtra = new Projeto();
                    $versaoObjExtra->setId((int) $novaVersaoId);

                    (new \app\services\ExtraService())->salvarExtras(
                        $novosExtras,
                        $novosExtrasDesc,
                        $versaoObjExtra
                    );
                }

                // 3. Associa componentes à nova versão
                $componentesObj = [];
                foreach ($mapForm as $componenteId => $qtd) {
                    $componente = new Componente();
                    $componente->setId((int) $componenteId);
                    $componente->setQuantidade((int) $qtd);

                    $componentesObj[] = $componente;
                }

                if (!empty($componentesObj)) {
                    $novaVersaoObj = new Projeto();
                    $novaVersaoObj->setId((int) $novaVersaoId);

                    $this->componenteService->associar([
                        'componentes' => $componentesObj,
                        'projeto' => $novaVersaoObj
                    ]);
                }
            }

            // Atualiza os dados gerais do projeto
            if ($categoriaPost === 'criar') {
                $novaCatObj = $this->categoriaService->cadastro([
                    'categoria_nome' => $novaCategoria
                ]);

                if (!$novaCatObj || !$novaCatObj->getId()) {
                    throw new \RuntimeException(
                        'Não foi possível cadastrar a categoria.'
                    );
                }

                $categoriaId = (int) $novaCatObj->getId();
            } else {
                $categoriaId = (int) $categoriaPost;
            }

            $projetoObj = new Projeto();
            $projetoObj->setId($projetoId);
            $projetoObj->setNome($nome);
            $projetoObj->setDescricao($descricao);
            $projetoObj->setVisibilidade($visibilidade);

            if ($equipeIdPost !== null) {
                $equipe = new \app\models\Equipe();
                $equipe->setId($equipeIdPost);
                $projetoObj->setEquipe($equipe);
            }

            $categoria = new \app\models\Categoria();
            $categoria->setId($categoriaId);
            $projetoObj->setCategoria($categoria);

            $this->service->editarProjeto($projetoObj);

            // Atualiza as imagens mantidas
            $imagensManter = $_POST['imagens_manter'] ?? [];
            if (!is_array($imagensManter)) {
                $imagensManter = [];
            }

            $imagensManterInt = [];
            foreach ($imagensManter as $imagemId) {
                $imagemIdValidado = filter_var($imagemId, FILTER_VALIDATE_INT);
                if ($imagemIdValidado !== false && $imagemIdValidado > 0) {
                    $imagensManterInt[] = (int) $imagemIdValidado;
                }
            }

            $imagemRepo = new \app\repositories\ImagemRepositorySql();
            $imagemRepo->desativarImagensExceto(
                $projetoId,
                $imagensManterInt
            );

            // Salva novas imagens enviadas
            if (!empty($_FILES['imagens']['name'][0])) {
                (new ImagemService())->salvarImagem(
                    $_FILES['imagens'],
                    $projetoObj
                );
            }

            $this->responderJson([
                'sucesso' => true,
                'mensagem' => 'Projeto atualizado com sucesso!',
                'redirect' => URL_BASE . '/projeto/perfil?id=' . $projetoId
            ]);
        } catch (\Throwable $e) {
            error_log(
                "ERRO AO ATUALIZAR PROJETO:\n" . (string) $e
            );

            $this->responderJson([
                'sucesso' => false,
                'mensagem' => 'Não foi possível atualizar o projeto.'
            ], 500);
        }
    }

    /*
     * Exibe o perfil de um projeto.
     */
    public function perfil()
    {
        $this->loginRequired();

        $id = $this->obterId([$_GET], 'id');

        if (!$id) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($id);

        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        // Projetos privados exigem participação ou administração.
        if (
            $projeto->getVisibilidade() === 'privado'
            && !$this->usuarioTemPermissao($id)
        ) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        $todasVersoes = $this->repositorySql->buscarTodasVersoes($id);

        $versaoParam = $this->obterId([$_GET], 'versao');
        $versaoExibida = null;

        if ($versaoParam && !empty($todasVersoes)) {
            foreach ($todasVersoes as $versao) {
                if ((int) $versao['versao'] === $versaoParam) {
                    $versaoExibida = $versao;
                    break;
                }
            }
        }

        // Se nenhuma versão foi selecionada, exibe a mais recente.
        if (!$versaoExibida && !empty($todasVersoes)) {
            $versaoExibida = $todasVersoes[0];
        }

        $codigos = [];
        $componentes = [];

        if ($versaoExibida) {
            $codigos = (new \app\repositories\CodigoRepositorySql())
                ->buscarPorVersao((int) $versaoExibida['id']);

            $componentes = (new \app\repositories\ComponenteRepositorySql())
                ->buscarPorVersao((int) $versaoExibida['id']);
        }
        $extras = [];
        if ($versaoExibida) {
            $codigos = (new \app\repositories\CodigoRepositorySql())
                ->buscarPorVersao((int) $versaoExibida['id']);

            $componentes = (new \app\repositories\ComponenteRepositorySql())
                ->buscarPorVersao((int) $versaoExibida['id']);

            $extras = (new \app\repositories\ExtraRepositorySql())
                ->buscarPorVersao((int) $versaoExibida['id']);
        }

        $data['extras'] = $extras;

        $data['projeto'] = $projeto;
        $data['comentarios'] = (new \app\repositories\ComentarioRepositorySql())->buscarPorProjeto($id);
        $data['todasVersoes'] = $todasVersoes;
        $data['versaoExibida'] = $versaoExibida;
        $data['codigos'] = $codigos;
        $data['componentes'] = $componentes;

        $data['usuarios'] = $this->usuarioRepositorySql->buscarProjeto($id);
        $data['todosUsuarios'] = $this->usuarioRepositorySql->listarTodos();

        $data['imagens'] = (new ImagemService())->buscarProjeto($id);

        $data['solicitacoesPendentes'] = $this->usuarioECoordenador($id)
            ? $this->repositorySql->listarSolicitacoesPendentes($id)
            : [];

        $this->view('projeto/perfil', $data);
    }

        public function desativar()
    {
        $this->loginRequired();

        $id = $this->obterId([$_POST], 'id');
        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        if (!$id || !$usuarioLogado || $usuarioLogado->getRegra() !== 'admin') {
            if ($isAjax) {
                $this->responderJson(['sucesso' => false, 'mensagem' => 'Acesso negado.'], 403);
            }
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE . '/projeto');
            return;
        }

        $justificativa = trim((string) ($_POST['justificativa'] ?? ''));

        // Validação obrigatória da justificativa no backend (mínimo de 10 caracteres)
        if (mb_strlen($justificativa) < 10) {
            if ($isAjax) {
                $this->responderJson([
                    'sucesso' => false,
                    'mensagem' => 'A justificativa é obrigatória e deve ter pelo menos 10 caracteres.'
                ], 422);
            }
            $this->redirect($_SERVER['HTTP_REFERER'] ?? URL_BASE . '/projeto');
            return;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($id);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null) {
            if ($isAjax) {
                $this->responderJson(['sucesso' => false, 'mensagem' => 'Projeto não encontrado.'], 404);
            }
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        try {
            // Executa a desativação no banco
            $this->repositorySql->desativar($id);

            // Dispensa denúncias ativas relacionadas ao projeto
            (new \app\repositories\DenunciaRepositorySql())->dispensar('projeto', $id);

            // Notifica os integrantes por e-mail com a justificativa
            $usuariosProjeto = $this->usuarioRepositorySql->buscarProjeto($id);

            if (!empty($usuariosProjeto)) {
                $mailHelper = new \app\helpers\MailHelper();
                $motivoHtml = nl2br(htmlspecialchars($justificativa, ENT_QUOTES, 'UTF-8'));
                $nomeProjeto = htmlspecialchars((string) $projeto->getNome(), ENT_QUOTES, 'UTF-8');

                foreach ($usuariosProjeto as $integrante) {
                    $email = $integrante->getEmail();

                    if (empty($email)) {
                        continue;
                    }

                    $nomeUsuario = htmlspecialchars((string) $integrante->getNome(), ENT_QUOTES, 'UTF-8');

                    $mensagem = "
                        <div style='font-family: Arial, sans-serif; background-color: #06141C; color: #F2FEFE; padding: 20px; border: 2px solid #07556A;'>
                            <h2 style='color: #F2A058;'>RoboDrive - Projeto Desativado</h2>
                            <p>Olá, <strong>{$nomeUsuario}</strong>,</p>
                            <p>Informamos que o projeto <strong>\"{$nomeProjeto}\"</strong> foi desativado pela administração pelo seguinte motivo:</p>
                            <blockquote style='background-color: #000505; border-left: 4px solid #F2A058; padding: 12px; margin: 15px 0; color: #F2FEFE;'>
                                {$motivoHtml}
                            </blockquote>
                            <p>Caso discorde desta decisão, responda a este e-mail para solicitar revisão.</p>
                            <hr style='border: 0; border-top: 1px solid #07556A; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #91B5BD;'>Atenciosamente,<br><strong>Administração RoboDrive</strong></p>
                        </div>
                    ";

                    try {
                        $mailHelper->enviarEmail(
                            $email,
                            null,
                            '',
                            '[RoboDrive] Projeto Desativado',
                            $mensagem
                        );
                    } catch (\Throwable $mailError) {
                        error_log('Erro ao enviar e-mail de desativação: ' . $mailError->getMessage());
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log('Erro ao desativar projeto: ' . (string) $e);
            if ($isAjax) {
                $this->responderJson([
                    'sucesso' => false,
                    'mensagem' => 'Ocorreu um erro interno ao desativar o projeto.'
                ], 500);
            }
        }

        // Resposta de sucesso
        if ($isAjax) {
            $this->responderJson([
                'sucesso' => true,
                'mensagem' => 'Projeto desativado com sucesso!',
                'redirect' => URL_BASE . '/denuncia'
            ]);
        }

        $this->redirect(URL_BASE . '/usuario/perfil');
    }

    /*
     * Altera o papel de um integrante.
     */
    public function alterarPapel()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'projeto_id');
        $usuarioId = $this->obterId([$_POST], 'usuario_id');

        $papel = trim((string) ($_POST['papel'] ?? ''));

        if (
            !$projetoId
            || !$usuarioId
            || !in_array($papel, ['coordenador', 'participante'], true)
            || !$this->usuarioECoordenador($projetoId)
        ) {
            $this->redirect(
                URL_BASE . '/projeto/perfil?id=' . ($projetoId ?? 0)
            );
            return;
        }

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        // Coordenadores não podem alterar o próprio papel.
        if (
            $usuarioLogado
            && (int) $usuarioLogado->getId() === $usuarioId
            && $usuarioLogado->getRegra() !== 'admin'
        ) {
            $this->redirect(
                URL_BASE . '/projeto/perfil?id=' . $projetoId
            );
            return;
        }

        $this->repositorySql->atualizarPapelMembro(
            $projetoId,
            $usuarioId,
            $papel
        );

        $this->redirect(
            URL_BASE . '/projeto/perfil?id=' . $projetoId
        );
    }

    /*
     * Adiciona um integrante ao projeto.
     */
    public function adicionarMembro()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'projeto_id');
        $usuarioId = $this->obterId([$_POST], 'usuario_id');

        $papel = trim(
            (string) ($_POST['papel'] ?? 'participante')
        );

        if (!in_array($papel, ['coordenador', 'participante'], true)) {
            $papel = 'participante';
        }

        if (
            !$projetoId
            || !$usuarioId
            || !$this->usuarioECoordenador($projetoId)
        ) {
            $this->redirect(
                URL_BASE . '/projeto/perfil?id=' . ($projetoId ?? 0)
            );
            return;
        }

        $this->repositorySql->adicionarMembro(
            $projetoId,
            $usuarioId,
            $papel
        );

        $this->redirect(
            URL_BASE . '/projeto/perfil?id=' . $projetoId
        );
    }

    /*
     * Remove um integrante do projeto.
     */
    public function removerMembro()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'projeto_id');
        $usuarioId = $this->obterId([$_POST], 'usuario_id');

        if (
            !$projetoId
            || !$usuarioId
            || !$this->usuarioECoordenador($projetoId)
        ) {
            $this->redirect(
                URL_BASE . '/projeto/perfil?id=' . ($projetoId ?? 0)
            );
            return;
        }

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        // Impede que um coordenador remova a si mesmo.
        if (
            $usuarioLogado
            && (int) $usuarioLogado->getId() === $usuarioId
            && $usuarioLogado->getRegra() !== 'admin'
        ) {
            $this->redirect(
                URL_BASE . '/projeto/perfil?id=' . $projetoId
            );
            return;
        }

        $this->repositorySql->removerMembro(
            $projetoId,
            $usuarioId
        );

        $this->redirect(
            URL_BASE . '/projeto/perfil?id=' . $projetoId
        );
    }

    /*
     * Exibe o formulário para entrar em um projeto.
     */
    public function formularioEntrar()
    {
        $this->loginRequired();

        $this->view('projeto/entrar');
    }

    /*
     * Solicita entrada em um projeto usando hash e senha.
     */
    public function entrar()
    {
        $this->loginRequired();

        $hash = trim(
            (string) ($_POST['hash'] ?? '')
        );

        $senha = (string) ($_POST['senha'] ?? '');

        $erros = [];

        if ($hash === '') {
            $erros['hash'] = 'O código Hash é obrigatório.';
        }

        if ($senha === '') {
            $erros['senha'] = 'A senha do projeto é obrigatória.';
        }

        if (!empty($erros)) {
            $this->view('projeto/entrar', [
                'erros' => $erros,
                'hash' => $hash
            ]);
            return;
        }

        $projeto = $this->repositorySql->buscarPorHash($hash);

        if (!$projeto) {
            $this->view('projeto/entrar', [
                'erros' => [
                    'geral' => 'Código Hash não encontrado.'
                ],
                'hash' => $hash
            ]);
            return;
        }

        if (!password_verify($senha, $projeto->getSenha())) {
            $this->view('projeto/entrar', [
                'erros' => [
                    'senha' => 'Senha incorreta para este projeto.'
                ],
                'hash' => $hash
            ]);
            return;
        }

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        if (!$usuarioLogado) {
            $this->loginRequired();
            return;
        }

        $solicitou = $this->repositorySql->solicitarEntrada(
            (int) $projeto->getId(),
            (int) $usuarioLogado->getId()
        );

        if (!$solicitou) {
            $this->view('projeto/entrar', [
                'erros' => [
                    'geral' => 'Não foi possível enviar a solicitação. Você pode já ser integrante ou possuir uma solicitação pendente.'
                ],
                'hash' => $hash
            ]);
            return;
        }

        $this->view('projeto/entrar', [
            'sucesso' => 'Solicitação enviada! Aguarde a aprovação do coordenador do projeto.'
        ]);
    }

    /*
     * Aprova uma solicitação de entrada.
     */
    public function aprovarSolicitacao()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'projeto_id');
        $usuarioId = $this->obterId([$_POST], 'usuario_id');

        if (
            $projetoId
            && $usuarioId
            && $this->usuarioECoordenador($projetoId)
        ) {
            $this->repositorySql->aprovarSolicitacao(
                $projetoId,
                $usuarioId
            );
        }

        $this->redirect(
            URL_BASE . '/projeto/perfil?id=' . ($projetoId ?? 0)
        );
    }

    /*
     * Rejeita uma solicitação de entrada.
     */
    public function rejeitarSolicitacao()
    {
        $this->loginRequired();

        $projetoId = $this->obterId([$_POST], 'projeto_id');
        $usuarioId = $this->obterId([$_POST], 'usuario_id');

        if (
            $projetoId
            && $usuarioId
            && $this->usuarioECoordenador($projetoId)
        ) {
            $this->repositorySql->rejeitarSolicitacao(
                $projetoId,
                $usuarioId
            );
        }

        $this->redirect(
            URL_BASE . '/projeto/perfil?id=' . ($projetoId ?? 0)
        );
    }
}
