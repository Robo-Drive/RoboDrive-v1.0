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
            6,
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

        // Arquivos
        $validador->validarArquivo(
            'imagens',
            $_FILES['imagens'] ?? []
        );

        $validador->validarArquivo(
            'codigos',
            $_FILES['codigos'] ?? []
        );

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

        if ($versaoAtual) {
            $codigosAtuais = (new \app\repositories\CodigoRepositorySql())
                ->buscarPorVersao((int) $versaoAtual['id']);

            $componentesAtuais = (new \app\repositories\ComponenteRepositorySql())
                ->buscarPorVersao((int) $versaoAtual['id']);
        }

        $data['projeto'] = $projeto;
        $data['versaoAtual'] = $versaoAtual;
        $data['codigosAtuais'] = $codigosAtuais;
        $data['componentesAtuais'] = $componentesAtuais;

        $data['imagensAtuais'] = (new \app\repositories\ImagemRepositorySql())
            ->buscarProjeto($id);

        $data['componentes'] = $this->componenteService->listarTodos();
        $data['categorias'] = $this->categoriaService->listarTodos();

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
        $categoriaId = filter_var(
            $categoriaPost,
            FILTER_VALIDATE_INT
        );

        if ($categoriaId === false || $categoriaId <= 0) {
            $validador->obrigatorio(
                'categoria',
                '',
                'Selecione uma categoria válida.'
            );
        } else {
            $categoriaId = (int) $categoriaId;
        }

        // Equipe
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

        // Códigos antigos que serão mantidos.
        $codigosManter = $_POST['codigos_manter'] ?? [];

        if (!is_array($codigosManter)) {
            $codigosManter = [];

            $validador->obrigatorio(
                'codigos_manter',
                '',
                'A lista de códigos mantidos é inválida.'
            );
        }

        // Novos códigos.
        $novosCodigos = $_FILES['codigos'] ?? [];
        $novosCodigosDesc = $_POST['codigos_descricao'] ?? [];

        if (!is_array($novosCodigosDesc)) {
            $novosCodigosDesc = [];

            $validador->obrigatorio(
                'codigos_descricao',
                '',
                'As descrições dos códigos são inválidas.'
            );
        }

        foreach ($novosCodigosDesc as $i => $descricaoCodigo) {
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

            $novosCodigosDesc[$i] = $descricaoCodigo;
        }

        // Componentes.
        $componentesForm = $_POST['componentes'] ?? [];

        if (!is_array($componentesForm)) {
            $componentesForm = [];

            $validador->obrigatorio(
                'componentes',
                '',
                'A lista de componentes é inválida.'
            );
        }

        $mapForm = [];

        foreach ($componentesForm as $componenteId => $dados) {
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

            // Não aceita quantidades inválidas ou negativas.
            if (
                filter_var($qtd, FILTER_VALIDATE_INT) === false
                || (int) $qtd <= 0
            ) {
                continue;
            }

            $mapForm[(int) $idValidado] = (int) $qtd;
        }

        // Validação dos arquivos enviados.
        $validador->validarArquivo(
            'codigos',
            $novosCodigos
        );

        $validador->validarArquivo(
            'imagens',
            $_FILES['imagens'] ?? []
        );

        if ($validador->temErros()) {
            $this->responderJson([
                'sucesso' => false,
                'erros' => $validador->getErros()
            ], 422);
        }

        try {
            // Busca a versão atual.
            $versaoAtual = $this->repositorySql->buscarUltimaVersao(
                $projetoId
            );

            $versaoAtualId = $versaoAtual
                ? (int) $versaoAtual['id']
                : 0;

            $codigoRepo = new \app\repositories\CodigoRepositorySql();
            $compRepo = new \app\repositories\ComponenteRepositorySql();

            // Códigos atualmente associados à versão.
            $codigosBanco = $versaoAtualId
                ? $codigoRepo->buscarPorVersao($versaoAtualId)
                : [];

            $idsCodigosBanco = array_map(
                fn($codigo) => (int) $codigo->getId(),
                $codigosBanco
            );

            // Valida os IDs dos códigos que serão mantidos.
            $codigosManterInt = [];

            foreach ($codigosManter as $codigoId) {
                $codigoIdValidado = filter_var(
                    $codigoId,
                    FILTER_VALIDATE_INT
                );

                if (
                    $codigoIdValidado === false
                    || $codigoIdValidado <= 0
                ) {
                    $this->responderJson([
                        'sucesso' => false,
                        'mensagem' => 'Foi enviado um código inválido.'
                    ], 422);
                }

                $codigosManterInt[] = (int) $codigoIdValidado;
            }

            $codigosManterInt = array_values(
                array_unique($codigosManterInt)
            );

            // Impede manter códigos que não pertencem à versão atual.
            $codigosNaoPertencentes = array_diff(
                $codigosManterInt,
                $idsCodigosBanco
            );

            if (!empty($codigosNaoPertencentes)) {
                $this->responderJson([
                    'sucesso' => false,
                    'mensagem' => 'Um ou mais códigos não pertencem a este projeto.'
                ], 403);
            }

            sort($idsCodigosBanco);
            sort($codigosManterInt);

            // Verifica se foram enviados novos códigos.
            $temNovosCodigos = !empty($novosCodigos['name'][0])
                && trim((string) $novosCodigos['name'][0]) !== '';

            $mudouCodigos = $temNovosCodigos
                || ($idsCodigosBanco !== $codigosManterInt);

            // Componentes atuais.
            $compsBanco = $versaoAtualId
                ? $compRepo->buscarPorVersao($versaoAtualId)
                : [];

            $mapBanco = [];

            foreach ($compsBanco as $componente) {
                $mapBanco[(int) $componente->getId()] =
                    (int) $componente->getQuantidade();
            }

            ksort($mapBanco);
            ksort($mapForm);

            $mudouComponentes = ($mapBanco !== $mapForm);

            // Cria nova versão somente quando códigos ou componentes mudarem.
            if ($mudouCodigos || $mudouComponentes) {
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

                // Reassocia os códigos antigos mantidos.
                foreach ($codigosManterInt as $codigoId) {
                    $codigoRepo->associarVersao(
                        $codigoId,
                        $novaVersaoId
                    );
                }

                // Salva os novos códigos.
                if ($temNovosCodigos) {
                    $versaoObj = new Projeto();
                    $versaoObj->setId((int) $novaVersaoId);

                    (new CodigoService())->salvarCodigo(
                        $novosCodigos,
                        $novosCodigosDesc,
                        $versaoObj
                    );
                }

                // Associa os componentes à nova versão.
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

            // Atualiza os dados gerais do projeto.
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

            // Executa a atualização apenas uma vez.
            $this->service->editarProjeto($projetoObj);

            // Atualiza as imagens mantidas.
            $imagensManter = $_POST['imagens_manter'] ?? [];

            if (!is_array($imagensManter)) {
                $imagensManter = [];
            }

            $imagensManterInt = [];

            foreach ($imagensManter as $imagemId) {
                $imagemIdValidado = filter_var(
                    $imagemId,
                    FILTER_VALIDATE_INT
                );

                if (
                    $imagemIdValidado !== false
                    && $imagemIdValidado > 0
                ) {
                    $imagensManterInt[] = (int) $imagemIdValidado;
                }
            }

            $imagemRepo = new \app\repositories\ImagemRepositorySql();

            $imagemRepo->desativarImagensExceto(
                $projetoId,
                $imagensManterInt
            );

            // Salva novas imagens.
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

        $data['projeto'] = $projeto;
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

    /*
     * Desativa um projeto.
     * Nesta implementação, somente administradores podem executar a ação.
     */
    public function desativar()
    {
        $this->loginRequired();

        $id = $this->obterId([$_POST], 'id');

        $usuarioLogado = $_SESSION['usuario_logado'] ?? null;

        if (
            !$id
            || !$usuarioLogado
            || $usuarioLogado->getRegra() !== 'admin'
        ) {
            $this->redirect(
                $_SERVER['HTTP_REFERER'] ?? URL_BASE . '/projeto'
            );
            return;
        }

        $justificativa = trim(
            (string) ($_POST['justificativa'] ?? '')
        );

        $projetoBusca = new Projeto();
        $projetoBusca->setId($id);

        $projeto = $this->repositorySql->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null) {
            $this->redirect(URL_BASE . '/projeto');
            return;
        }

        try {
            $this->repositorySql->desativar($id);

            // Dispensa denúncias ativas relacionadas ao projeto.
            (new \app\repositories\DenunciaRepositorySql())
                ->dispensar('projeto', $id);

            // Notifica os integrantes.
            $usuariosProjeto = $this->usuarioRepositorySql->buscarProjeto($id);

            if (!empty($usuariosProjeto)) {
                $mailHelper = new \app\helpers\MailHelper();

                $motivoHtml = $justificativa !== ''
                    ? nl2br(htmlspecialchars(
                        $justificativa,
                        ENT_QUOTES,
                        'UTF-8'
                    ))
                    : 'Sem justificativa informada.';

                foreach ($usuariosProjeto as $integrante) {
                    $email = $integrante->getEmail();

                    if (empty($email)) {
                        continue;
                    }

                    $nomeUsuario = htmlspecialchars(
                        (string) $integrante->getNome(),
                        ENT_QUOTES,
                        'UTF-8'
                    );

                    $nomeProjeto = htmlspecialchars(
                        (string) $projeto->getNome(),
                        ENT_QUOTES,
                        'UTF-8'
                    );

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
                        error_log(
                            'Erro ao enviar e-mail de desativação: '
                            . $mailError->getMessage()
                        );
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log(
                'Erro ao desativar projeto: ' . (string) $e
            );
        }

        $this->redirect(
            $_SERVER['HTTP_REFERER'] ?? URL_BASE . '/projeto'
        );
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
