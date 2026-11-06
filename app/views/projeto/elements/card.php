
<?php
if (isset($projeto, $versaoExibida) && $projeto !== null):

    // Função para exibir textos com segurança.
    // Decodifica entidades armazenadas anteriormente e depois escapa o HTML.
    $escapar = static function ($valor): string {
        return htmlspecialchars(
            html_entity_decode(
                (string)($valor ?? ''),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            ),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    };

    $usuarioLogado = $_SESSION['usuario_logado'] ?? null;
    $usuarioLogadoId = $usuarioLogado?->getId();
    $eAdmin = $usuarioLogado?->getRegra() === 'admin';

    // Verificação dos integrantes e seus cargos.
    $eIntegrante = $eAdmin;
    $eCoordenador = $eAdmin;

    if (!empty($usuarios) && $usuarioLogadoId !== null) {
        foreach ($usuarios as $membro) {
            if ((int)$membro->getId() === (int)$usuarioLogadoId) {
                $eIntegrante = true;

                if ($membro->getPapelProjeto() === 'coordenador') {
                    $eCoordenador = true;
                }

                break;
            }
        }
    }

    // Somente coordenadores e administradores gerenciam o projeto.
    $podeGerenciar = $eCoordenador;

    // Dados auxiliares.
    $idsAutores = !empty($usuarios)
        ? array_map(static fn($u) => (int)$u->getId(), $usuarios)
        : [];

    $versaoNumero = (int)($versaoExibida['versao'] ?? 1);
?>

<div class="col-span-12 lg:col-span-10 row-span-12 overflow-y-auto p-4 sm:p-8 space-y-10 rd-scroll-hidden">

    <!-- 1. CABEÇALHO DO PROJETO -->
    <div class="border-b-2 border-[#07556A] pb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

        <div>
            <div class="flex items-center gap-3 flex-wrap">

                <h1 class="font-['Orbitron'] text-2xl sm:text-4xl font-black text-[#F2FEFE]">
                    <?= $escapar($projeto->getNome()) ?>
                </h1>

                <span class="text-[.65rem] font-bold uppercase tracking-[.2em] px-2.5 py-1 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/20">
                    <?= $escapar($projeto->getVisibilidade()) ?>
                </span>

            </div>

            <p class="text-[#91B5BD] text-xs mt-1">
                Categoria:

                <span class="text-white font-semibold">
                    <?= $escapar($projeto->getCategoria()?->getNome() ?? 'Geral') ?>
                </span>
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">

            <?php if ($podeGerenciar): ?>

                <!-- Editar projeto -->
                <form action="<?= URL_BASE ?>/projeto/editar" method="post">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$projeto->getId() ?>"
                    >

                    <button
                        type="submit"
                        class="rd-btn rd-btn-primary !py-2 !px-4 text-xs"
                    >
                        Editar Projeto
                    </button>

                </form>

                <!-- Desativar projeto -->
                <form
                    action="<?= URL_BASE ?>/projeto/desativar"
                    method="post"
                    onsubmit="return confirm('Deseja realmente desativar este projeto?');"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$projeto->getId() ?>"
                    >

                    <button
                        type="submit"
                        class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition"
                    >
                        Desativar
                    </button>

                </form>

            <?php endif; ?>

            <!-- Voltar -->
            <a
                href="<?= URL_BASE ?>/projeto"
                class="border-2 border-[#07556A] px-4 py-2 text-xs font-bold text-[#F2FEFE] hover:border-[#13F3F7] transition"
            >
                Voltar
            </a>

            <!-- Denunciar projeto -->
            <?php
            $denunciaAlvo = [
                'tipo' => 'projeto',
                'id' => (int)$projeto->getId(),
                'nome' => $projeto->getNome(),
                'autores' => $idsAutores,
                'classe' => 'shrink-0'
            ];

            include(__DIR__ . '/../../elements/denunciaBotao.php');
            ?>

        </div>
    </div>


    <!-- 2. DESCRIÇÃO -->
    <div class="border-2 border-[#07556A] bg-[#000505] p-5">

        <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] mb-2 font-['Orbitron']">
            SOBRE O PROJETO
        </h2>

        <p class="text-[#F2FEFE] text-sm leading-relaxed whitespace-pre-line">
            <?= $escapar($projeto->getDescricao()) ?>
        </p>

    </div>


    <!-- 3. GALERIA DE IMAGENS -->
    <?php if (!empty($imagens)): ?>

        <div class="border-2 border-[#07556A] bg-[#000505] p-5">

            <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] mb-4 font-['Orbitron']">
                02. FOTOS DO PROJETO
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <?php foreach ($imagens as $img): ?>

                    <div class="group relative aspect-video border-2 border-[#07556A] overflow-hidden bg-[#06141C] hover:border-[#13F3F7] transition">

                        <img
                            src="<?= URL_BASE ?>/arquivo?arquivo=<?= urlencode($img->getCaminho()) ?>"
                            alt="Imagem do projeto"
                            loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                        >

                    </div>

                <?php endforeach; ?>

            </div>
        </div>

    <?php endif; ?>


    <!-- 4. HISTÓRICO DE VERSÕES -->
    <?php if (!empty($todasVersoes)): ?>

        <div class="border-2 border-[#07556A] bg-[#000505] p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

            <div>

                <div class="flex items-center gap-2">

                    <span class="text-xs uppercase text-[#91B5BD] tracking-wider font-['Orbitron']">
                        Versão em exibição:
                    </span>

                    <span class="font-['Orbitron'] font-black text-[#13F3F7] text-lg">
                        v<?= $versaoNumero ?>
                    </span>

                </div>

                <p class="text-xs text-[#91B5BD] mt-1">

                    Editado por:

                    <?php if (!empty($versaoExibida['autor_nome'])): ?>

                        <a
                            href="<?= URL_BASE ?>/usuario/perfil?id=<?= (int)($versaoExibida['usuario_id'] ?? 0) ?>"
                            class="text-[#13F3F7] font-semibold hover:underline"
                        >
                            <?= $escapar($versaoExibida['autor_nome']) ?>
                        </a>

                    <?php else: ?>

                        <span class="text-white font-semibold">
                            Desconhecido
                        </span>

                    <?php endif; ?>

                    <?php if (!empty($versaoExibida['criado_em'])): ?>

                        <span class="text-[#91B5BD]/70 ml-1">
                            em <?= date('d/m/Y \à\s H:i', strtotime($versaoExibida['criado_em'])) ?>
                        </span>

                    <?php endif; ?>

                </p>

                <?php if (!empty($versaoExibida['descricao_alteracao'])): ?>

                    <p class="text-xs text-[#91B5BD] mt-0.5">

                        Notas da versão:

                        <span class="text-white">
                            <?= $escapar($versaoExibida['descricao_alteracao']) ?>
                        </span>

                    </p>

                <?php endif; ?>

            </div>

            <!-- Selecionar versão -->
            <form
                action="<?= URL_BASE ?>/projeto/perfil"
                method="get"
                class="flex items-center gap-2"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int)$projeto->getId() ?>"
                >

                <label
                    for="seletorVersao"
                    class="text-xs text-[#91B5BD] font-bold uppercase"
                >
                    Histórico:
                </label>

                <select
                    id="seletorVersao"
                    name="versao"
                    onchange="this.form.submit()"
                    class="rd-input !w-auto !py-1 text-xs"
                >

                    <?php foreach ($todasVersoes as $v): ?>

                        <option
                            value="<?= (int)$v['versao'] ?>"
                            <?= ((int)$v['versao'] === $versaoNumero) ? 'selected' : '' ?>
                        >
                            v<?= (int)$v['versao'] ?>
                            -
                            <?= $escapar($v['autor_nome'] ?? 'Autor') ?>

                            <?php if (!empty($v['criado_em'])): ?>
                                (<?= date('d/m/Y', strtotime($v['criado_em'])) ?>)
                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </form>

        </div>

    <?php endif; ?>


    <!-- 5. ARQUIVOS DE CÓDIGO -->
    <div class="border-2 border-[#07556A] bg-[#000505] p-5">

        <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] mb-4 font-['Orbitron']">
            03. ARQUIVOS DE CÓDIGO
        </h2>

        <?php if (!empty($codigos)): ?>

            <div class="space-y-6">

                <?php foreach ($codigos as $idx => $cod): ?>

                    <?php
                    $caminho = $cod->getCaminho();
                    $nomeArquivo = basename((string)$caminho);

                    $conteudo = (
                        is_string($caminho) &&
                        is_file($caminho) &&
                        is_readable($caminho)
                    )
                        ? file_get_contents($caminho)
                        : false;

                    if ($conteudo === false) {
                        $conteudo = '// Arquivo não localizado no storage.';
                    }

                    $totalLinhas = substr_count($conteudo, "\n") + 1;
                    $codigoId = 'code-' . (int)$idx;
                    ?>

                    <div class="border-2 border-[#07556A] bg-[#06141C] overflow-hidden">

                        <!-- Cabeçalho do arquivo -->
                        <div class="bg-[#000505] border-b-2 border-[#07556A] px-4 py-2.5 flex items-center justify-between gap-3">

                            <div class="flex items-center gap-3 min-w-0">

                                <svg
                                    class="w-4 h-4 text-[#13F3F7] shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="square"
                                        stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
                                </svg>

                                <span class="font-mono text-xs font-bold text-[#F2FEFE] truncate">

                                    <?= $escapar($cod->getDescricao() ?: $nomeArquivo) ?>

                                </span>

                            </div>

                            <button
                                type="button"
                                onclick="copiarCodigo('<?= $codigoId ?>', this)"
                                class="text-xs border border-[#07556A] hover:border-[#13F3F7] text-[#91B5BD] hover:text-[#13F3F7] px-2.5 py-1 transition flex items-center gap-1.5 shrink-0"
                            >

                                <svg
                                    class="w-3.5 h-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="square"
                                        stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                    />
                                </svg>

                                <span>Copiar</span>

                            </button>

                        </div>

                        <!-- Conteúdo do código -->
                        <div class="overflow-x-auto p-4 text-xs font-mono flex gap-4 bg-[#030B10]">

                            <!-- Numeração das linhas -->
                            <div class="select-none text-[#4E6B72] text-right font-mono pr-2 border-r leading-[1.625] border-[#07556A]/60">

                                <?php for ($i = 1; $i <= $totalLinhas; $i++): ?>

                                    <div><?= $i ?></div>

                                <?php endfor; ?>

                            </div>

                            <pre
                                class="text-[#F2FEFE] flex-1 outline-none font-mono leading-relaxed"
                                id="<?= $codigoId ?>"
                            ><code><?= htmlspecialchars($conteudo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></code></pre>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="text-sm text-[#91B5BD]">
                Nenhum arquivo de código foi anexado a este projeto.
            </p>

        <?php endif; ?>

    </div>


    <!-- 6. COMPONENTES UTILIZADOS -->
    <div class="border-2 border-[#07556A] bg-[#000505] p-5">

        <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] mb-4 font-['Orbitron']">
            04. COMPONENTES DO PROJETO
        </h2>

        <?php if (!empty($componentes)): ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                <?php foreach ($componentes as $comp): ?>

                    <a
                        href="<?= URL_BASE ?>/componente/perfil?id=<?= (int)$comp->getId() ?>"
                        class="border-2 border-[#07556A] bg-[#06141C] p-4 flex gap-4 hover:border-[#13F3F7] transition group"
                    >

                        <img
                            src="<?= !empty($comp->getImagem())
                                ? URL_BASE . '/arquivo?arquivo=' . urlencode($comp->getImagem())
                                : IMG_URL_BASE . '/componente-icon.png' ?>"
                            alt="<?= $escapar($comp->getNome()) ?>"
                            loading="lazy"
                            class="w-14 h-14 object-cover border border-[#07556A] shrink-0"
                        >

                        <div class="min-w-0 flex-1">

                            <h3 class="text-sm font-bold text-[#F2FEFE] truncate group-hover:text-[#13F3F7] transition">
                                <?= $escapar($comp->getNome()) ?>
                            </h3>

                            <p class="text-xs text-[#91B5BD] line-clamp-2 mt-0.5">
                                <?= $escapar($comp->getDescricao()) ?>
                            </p>

                            <span class="inline-block mt-2 text-[.65rem] font-bold uppercase tracking-wider text-[#13F3F7]">
                                Qtd: <?= (int)($comp->getQuantidade() ?? 1) ?> un.
                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="text-sm text-[#91B5BD]">
                Nenhum componente cadastrado neste projeto.
            </p>

        <?php endif; ?>

    </div>


    <!-- 7. EQUIPE DO PROJETO -->
    <div class="border-2 border-[#07556A] bg-[#000505] p-5">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">

            <div>

                <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">
                    05. EQUIPE DO PROJETO
                </h2>

                <p class="text-[.65rem] text-[#91B5BD] mt-0.5">
                    Integrantes podem visualizar o projeto. Coordenadores e administradores podem gerenciar seus dados.
                </p>

            </div>

        </div>


        <!-- HASH DE ENTRADA -->
        <?php if ($eCoordenador): ?>

            <div class="mb-4 border-2 border-[#13F3F7] bg-[#00171F] p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">

                <div>

                    <p class="text-[.65rem] font-bold uppercase tracking-widest text-[#13F3F7]">
                        Código Hash de Entrada do Projeto:
                    </p>

                    <p class="text-xs text-[#91B5BD] mt-0.5">
                        Compartilhe este Hash e a senha do projeto com novos colaboradores.
                    </p>

                </div>

                <span class="font-mono text-base font-black px-3 py-1 bg-black border border-[#13F3F7] text-white tracking-widest select-all break-all">
                    <?= $escapar($projeto->getHashAcesso() ?? 'INDISPONÍVEL') ?>
                </span>

            </div>

        <?php endif; ?>


        <!-- INTEGRANTES -->
        <div class="border-2 border-[#07556A] bg-[#000505] p-5 space-y-6">

            <div class="flex items-center justify-between border-b border-[#07556A] pb-3">

                <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">
                    INTEGRANTES DO PROJETO
                </h2>

                <span class="text-xs text-[#91B5BD]">
                    Total: <?= count($usuarios ?? []) ?>
                </span>

            </div>


            <!-- SOLICITAÇÕES PENDENTES -->
            <?php if ($eCoordenador && !empty($solicitacoesPendentes)): ?>

                <div class="border-2 border-yellow-500/80 bg-[#06141C] p-4 space-y-3">

                    <h3 class="text-xs font-black uppercase text-yellow-400 font-['Orbitron']">
                        Solicitações de Entrada Pendentes (<?= count($solicitacoesPendentes) ?>)
                    </h3>

                    <div class="divide-y divide-[#07556A]">

                        <?php foreach ($solicitacoesPendentes as $pendente): ?>

                            <div class="py-2.5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

                                <div>

                                    <p class="text-xs font-bold text-white">
                                        <?= $escapar($pendente['nome'] ?? 'Usuário') ?>
                                    </p>

                                    <p class="text-[.65rem] text-gray-400">
                                        @<?= $escapar($pendente['nome_usuario'] ?? '') ?>
                                    </p>

                                </div>

                                <div class="flex items-center gap-2">

                                    <!-- Aprovar -->
                                    <form
                                        action="<?= URL_BASE ?>/projeto/aprovar-solicitacao"
                                        method="post"
                                    >

                                        <input
                                            type="hidden"
                                            name="projeto_id"
                                            value="<?= (int)$projeto->getId() ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="usuario_id"
                                            value="<?= (int)$pendente['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="rd-btn rd-btn-primary !py-1 !px-3 text-xs !bg-emerald-600 hover:!bg-emerald-500 !text-black font-bold"
                                        >
                                            Aceitar
                                        </button>

                                    </form>


                                    <!-- Rejeitar -->
                                    <form
                                        action="<?= URL_BASE ?>/projeto/rejeitar-solicitacao"
                                        method="post"
                                    >

                                        <input
                                            type="hidden"
                                            name="projeto_id"
                                            value="<?= (int)$projeto->getId() ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="usuario_id"
                                            value="<?= (int)$pendente['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="border border-red-500 text-red-400 hover:bg-red-500/20 px-3 py-1 text-xs font-bold transition"
                                        >
                                            Recusar
                                        </button>

                                    </form>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>


            <!-- LISTA DOS INTEGRANTES -->
            <?php if (!empty($usuarios)): ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                    <?php foreach ($usuarios as $usr): ?>

                        <?php
                        $usrId = (int)$usr->getId();
                        $usrPapel = $usr->getPapelProjeto() ?? 'participante';

                        $ehOutroUsuario = $usrId !== (int)$usuarioLogadoId;
                        $ehParticipante = $usrPapel === 'participante';
                        ?>

                        <div class="border-2 border-[#07556A] bg-[#06141C] p-3 flex flex-col justify-between gap-3 group hover:border-[#13F3F7] transition">

                            <div class="flex items-center justify-between gap-3">

                                <!-- Perfil do integrante -->
                                <a
                                    href="<?= URL_BASE ?>/usuario/perfil?id=<?= $usrId ?>"
                                    class="flex items-center gap-3 min-w-0 flex-1"
                                >

                                    <img
                                        src="<?= !empty($usr->getImagem())
                                            ? URL_BASE . '/arquivo?arquivo=' . urlencode($usr->getImagem())
                                            : IMG_URL_BASE . '/perfil.png' ?>"
                                        alt="Foto do usuário"
                                        loading="lazy"
                                        class="w-10 h-10 object-cover border border-[#07556A] shrink-0"
                                    >

                                    <div class="min-w-0">

                                        <p class="text-xs font-bold text-[#F2FEFE] truncate group-hover:text-[#13F3F7] transition">
                                            <?= $escapar($usr->getNome()) ?>
                                        </p>

                                        <span class="inline-block text-[.6rem] font-bold uppercase tracking-wider px-1.5 py-0.5 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/30 mt-1">
                                            <?= ucfirst($escapar($usrPapel)) ?>
                                        </span>

                                    </div>

                                </a>


                                <!-- Remover participante -->
                                <?php if ($eCoordenador && $ehOutroUsuario && $ehParticipante): ?>

                                    <form
                                        action="<?= URL_BASE ?>/projeto/remover-membro"
                                        method="post"
                                        onsubmit="return confirm('Deseja realmente remover este participante do projeto?');"
                                        class="shrink-0"
                                    >

                                        <input
                                            type="hidden"
                                            name="projeto_id"
                                            value="<?= (int)$projeto->getId() ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="usuario_id"
                                            value="<?= $usrId ?>"
                                        >

                                        <button
                                            type="submit"
                                            onclick="console.log('Botão de remoção clicado');"
                                            title="Remover participante"
                                            class="text-red-400 hover:text-red-300 border border-transparent hover:border-red-500/40 p-1.5 text-base transition"
                                        >
                                            ✕
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>


                            <!-- ALTERAR CARGO -->
                            <?php if ($eCoordenador && $ehOutroUsuario): ?>

                                <form
                                    action="<?= URL_BASE ?>/projeto/alterar-papel"
                                    method="post"
                                    class="flex items-center gap-2 pt-2 border-t border-[#07556A]/50"
                                >

                                    <input
                                        type="hidden"
                                        name="projeto_id"
                                        value="<?= (int)$projeto->getId() ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="usuario_id"
                                        value="<?= $usrId ?>"
                                    >

                                    <label class="text-[.65rem] text-[#91B5BD] uppercase font-bold">
                                        Cargo:
                                    </label>

                                    <select
                                        name="papel"
                                        onchange="this.form.submit()"
                                        class="bg-[#000505] text-[#13F3F7] border border-[#07556A] text-[.7rem] px-2 py-0.5 focus:outline-none"
                                    >

                                        <option
                                            value="participante"
                                            <?= $usrPapel === 'participante' ? 'selected' : '' ?>
                                        >
                                            Participante
                                        </option>

                                        <option
                                            value="coordenador"
                                            <?= $usrPapel === 'coordenador' ? 'selected' : '' ?>
                                        >
                                            Coordenador
                                        </option>

                                    </select>

                                </form>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p class="text-sm text-[#91B5BD]">
                    Nenhum integrante associado a este projeto.
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- SCRIPT: COPIAR CÓDIGO -->
<script>
function copiarCodigo(elementId, btn) {

    const elemento = document.getElementById(elementId);

    if (!elemento) {
        return;
    }

    const code = elemento.innerText;
    const span = btn.querySelector('span');

    const textoOriginal = span
        ? span.innerText
        : 'Copiar';

    if (!navigator.clipboard || !window.isSecureContext) {
        alert('Não foi possível copiar automaticamente. Verifique se a página está em uma conexão segura.');
        return;
    }

    navigator.clipboard.writeText(code)
        .then(() => {

            if (span) {
                span.innerText = 'Copiado!';
            }

            btn.classList.add(
                'border-[#13F3F7]',
                'text-[#13F3F7]'
            );

            setTimeout(() => {

                if (span) {
                    span.innerText = textoOriginal;
                }

                btn.classList.remove(
                    'border-[#13F3F7]',
                    'text-[#13F3F7]'
                );

            }, 2000);

        })
        .catch(() => {
            alert('Erro ao copiar o código.');
        });

}
</script>

<?php endif; ?>
