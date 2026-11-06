<?php if (isset($equipe)): ?>
<?php
$usuarioLogado = $_SESSION['usuario_logado'] ?? null;
$usuarioLogadoId = $usuarioLogado && method_exists($usuarioLogado, 'getId') ? $usuarioLogado->getId() : null;
$eAdmin = $usuarioLogado && method_exists($usuarioLogado, 'getRegra') && $usuarioLogado->getRegra() === 'admin';
$eCoordenador = $eAdmin;
$eIntegrante = $eAdmin;

if (!empty($usuarios)) {
    foreach ($usuarios as $membro) {
        $membroId = is_object($membro)
            ? (method_exists($membro, 'getId') ? $membro->getId() : null)
            : ($membro['id'] ?? null);
        $membroPapel = is_object($membro)
            ? (method_exists($membro, 'getPapelProjeto') ? $membro->getPapelProjeto() : '')
            : ($membro['papel'] ?? '');

        if ($membroId == $usuarioLogadoId) {
            $eIntegrante = true;
            if ($membroPapel === 'coordenador') {
                $eCoordenador = true;
            }
            break;
        }
    }
}

$urlImagemEquipe = static function ($imagem, $fallback = null) {
    $imagem = is_string($imagem) ? trim($imagem) : '';

    if ($imagem === '') {
        return $fallback ?: (IMG_URL_BASE . '/perfil.png');
    }

    if (preg_match('~^(https?://|data:image/)~i', $imagem)) {
        return $imagem;
    }

    if (strpos($imagem, URL_BASE . '/arquivo?') === 0) {
        return $imagem;
    }

    return URL_BASE . '/arquivo?arquivo=' . rawurlencode($imagem);
};

$obterImagem = static function ($item) {
    if (is_object($item)) {
        return method_exists($item, 'getImagem') ? $item->getImagem() : null;
    }
    return is_array($item) ? ($item['imagem'] ?? null) : null;
};
?>

<div class="col-span-12 lg:col-span-10 row-span-12 overflow-y-auto p-4 sm:p-8 space-y-8 rd-scroll-hidden">

    <!-- 1. CABEÇALHO DA EQUIPE -->
    <div class="border-b-2 border-[#07556A] pb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-['Orbitron'] text-2xl sm:text-4xl font-black text-[#F2FEFE]">
                    <?= htmlspecialchars(html_entity_decode($equipe->getNome())) ?>
                </h1>
                <span class="text-[.65rem] font-bold uppercase tracking-[.2em] px-2.5 py-1 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/20">
                    <?= $eCoordenador ? 'Coordenador' : ($eIntegrante ? 'Membro' : 'Visitante') ?>
                </span>
            </div>
            <?php if (method_exists($equipe, 'getDescricao') && $equipe->getDescricao()): ?>
                <p class="text-[#91B5BD] text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed whitespace-pre-line">
                    <?= htmlspecialchars(html_entity_decode($equipe->getDescricao())) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($eCoordenador): ?>
                <form action="<?= URL_BASE ?>/equipe/editar" method="post">
                    <input type="hidden" name="id" value="<?= (int) $equipe->getId() ?>">
                    <button type="submit" class="rd-btn rd-btn-primary !py-2 !px-4 text-xs">Editar Equipe</button>
                </form>
                <form action="<?= URL_BASE ?>/equipe/desativar" method="post" onsubmit="return confirm('Deseja realmente desativar esta equipe?');">
                    <input type="hidden" name="id" value="<?= (int) $equipe->getId() ?>">
                    <button type="submit" class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition">Desativar</button>
                </form>
            <?php endif; ?>
            <a href="<?= URL_BASE ?>/usuario/perfil" class="border-2 border-[#07556A] px-4 py-2 text-xs font-bold text-[#F2FEFE] hover:border-[#13F3F7] transition">
                Voltar
            </a>

            <?php
            $idsMembros = !empty($usuarios) ? array_map(function($u) {
                return is_object($u) ? $u->getId() : ($u['id'] ?? null);
            }, $usuarios) : [];

            $denunciaAlvo = [
                "tipo"    => "equipe",
                "id"      => $equipe->getId(),
                "nome"    => $equipe->getNome(),
                "autores" => $idsMembros,
                "classe"  => "shrink-0"
            ];
            include(__DIR__ . "/../../elements/denunciaBotao.php");
            ?>
        </div>
    </div>

    <!-- 2. BARRA SUPERIOR COM O SELECT DE VISUALIZAÇÃO -->
    <div class="border-2 border-[#07556A] bg-[#000505] p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="text-xs uppercase text-[#91B5BD] tracking-wider font-['Orbitron']">Visualização:</span>
            <img id="iconeSecaoAtual" src="<?= IMG_URL_BASE ?>/projeto-icon.png" alt="" class="w-5 h-5 object-contain">
            <span class="text-xs text-white" id="tituloSecaoAtual">Projetos da Equipe</span>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <label for="seletorAbaEquipe" class="text-xs text-[#91B5BD] font-bold uppercase whitespace-nowrap">Exibir:</label>
            <select id="seletorAbaEquipe" onchange="alternarAbaEquipe(this.value)" class="rd-input !w-full sm:!w-64 !py-1.5 text-xs cursor-pointer">
                <option value="projetos">Projetos</option>
                <option value="forum">Fórum</option>
                <?php if ($eCoordenador): ?>
                    <option value="usuarios">Usuários da Equipe</option>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <!-- 3. SEÇÃO: PROJETOS DA EQUIPE -->
    <div id="aba-projetos" class="space-y-4">
        <div class="flex items-center justify-between border-b border-[#07556A] pb-2">
            <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">PROJETOS DA EQUIPE</h2>
            <?php if ($eIntegrante): ?>
                <a href="<?= URL_BASE ?>/projeto/cadastro?equipe_id=<?= (int) $equipe->getId() ?>" class="rd-btn rd-btn-primary !py-1 !px-3 text-xs">+ Novo Projeto</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($projetos)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($projetos as $projeto): ?>
                    <?php
                    $projetoImagem = $obterImagem($projeto);
                    $projetoImagemUrl = $urlImagemEquipe($projetoImagem, IMG_URL_BASE . '/projeto-icon.png');
                    $projetoNome = method_exists($projeto, 'getNome') ? $projeto->getNome() : 'Projeto';
                    $projetoDescricao = method_exists($projeto, 'getDescricao') ? $projeto->getDescricao() : '';
                    ?>
                    <div class="border-2 border-[#07556A] bg-[#06141C] p-4 hover:border-[#13F3F7] transition">
                        <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= (int) $projeto->getId() ?>" class="block">
                            <img src="<?= htmlspecialchars($projetoImagemUrl) ?>"
                                 alt="Imagem do projeto <?= htmlspecialchars($projetoNome) ?>"
                                 class="w-full h-40 object-cover border border-[#07556A] mb-3"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='<?= htmlspecialchars(IMG_URL_BASE . '/projeto-icon.png') ?>';">
                            <span class="block font-['Orbitron'] text-sm font-bold text-[#F2FEFE] hover:text-[#13F3F7] truncate">
                                <?= htmlspecialchars(html_entity_decode($projetoNome)) ?>
                            </span>
                        </a>
                        <p class="text-xs text-[#91B5BD] line-clamp-2 mt-2">
                            <?= htmlspecialchars(html_entity_decode((string) $projetoDescricao)) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">Nenhum projeto registrado para esta equipe ainda.</div>
        <?php endif; ?>
    </div>

    <!-- 4. SEÇÃO: FÓRUM DA EQUIPE -->
    <div id="aba-forum" class="space-y-4 hidden">
        <div class="flex items-center justify-between border-b border-[#07556A] pb-2">
            <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">02. FÓRUM DE DISCUSSÃO</h2>
            <?php if ($eIntegrante): ?>
                <a href="<?= URL_BASE ?>/forum/cadastro?equipe_id=<?= (int) $equipe->getId() ?>" class="rd-btn rd-btn-primary !py-1 !px-3 text-xs">+ Nova Mensagem</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($postsForum)): ?>
            <div class="space-y-3">
                <?php foreach ($postsForum as $forum): ?>
                    <?php
                    $autorForum = method_exists($forum, 'getUsuario') ? $forum->getUsuario() : null;
                    $imagemAutor = $autorForum ? $obterImagem($autorForum) : null;
                    $imagemAutorUrl = $urlImagemEquipe($imagemAutor, IMG_URL_BASE . '/perfil.png');
                    $nomeUsuarioForum = $autorForum && method_exists($autorForum, 'getNomeUsuario') ? $autorForum->getNomeUsuario() : 'usuario';
                    $conteudoForum = method_exists($forum, 'getConteudo') ? $forum->getConteudo() : '';
                    ?>
                    <div class="border-2 border-[#07556A] bg-[#06141C] p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="<?= htmlspecialchars($imagemAutorUrl) ?>"
                                 alt="Foto de <?= htmlspecialchars($nomeUsuarioForum) ?>"
                                 class="w-10 h-10 object-cover border border-[#07556A] shrink-0"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='<?= htmlspecialchars(IMG_URL_BASE . '/perfil.png') ?>';">
                            <p class="text-xs text-[#13F3F7] font-bold">@<?= htmlspecialchars($nomeUsuarioForum) ?></p>
                        </div>
                        <p class="text-sm text-[#F2FEFE] mt-1"><?= nl2br(htmlspecialchars((string) $conteudoForum)) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">Nenhuma publicação no fórum desta equipe no momento.</div>
        <?php endif; ?>
    </div>

    <!-- 5. SEÇÃO: USUÁRIOS DA EQUIPE (EXCLUSIVO PARA COORDENADOR) -->
    <div id="aba-usuarios" class="space-y-6 hidden">
        <!-- BOX DO HASH -->
        <div class="border-2 border-[#13F3F7] bg-[#00171F] p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <p class="text-[.65rem] font-bold uppercase tracking-widest text-[#13F3F7]">Código Hash de Entrada da Equipe:</p>
                <p class="text-xs text-[#91B5BD] mt-0.5">Envie este código e a senha para novos membros solicitarem entrada.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-base font-black px-3 py-1 bg-black border border-[#13F3F7] text-white tracking-widest select-all">
                    <?= htmlspecialchars($equipe->getHashAcesso() ?? 'INDISPONÍVEL') ?>
                </span>
            </div>
        </div>

        <!-- SOLICITAÇÕES PENDENTES DE ENTRADA -->
        <?php if (!empty($solicitacoesPendentes)): ?>
            <div class="border-2 border-yellow-500/80 bg-[#06141C] p-4 space-y-3">
                <h3 class="text-xs font-black uppercase text-yellow-400 font-['Orbitron']">
                    ⏳ Solicitações de Entrada Pendentes (<?= count($solicitacoesPendentes) ?>)
                </h3>
                <div class="divide-y divide-[#07556A]">
                    <?php foreach ($solicitacoesPendentes as $pendente): ?>
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-white"><?= htmlspecialchars($pendente['nome']) ?></p>
                                <p class="text-[.65rem] text-gray-400">@<?= htmlspecialchars($pendente['nome_usuario']) ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="<?= URL_BASE ?>/equipe/aprovar-solicitacao" method="post">
                                    <input type="hidden" name="equipe_id" value="<?= $equipe->getId() ?>">
                                    <input type="hidden" name="usuario_id" value="<?= $pendente['id'] ?>">
                                    <button type="submit" class="rd-btn rd-btn-primary !py-1 !px-3 text-xs !bg-emerald-600 hover:!bg-emerald-500 !text-black font-bold">Aceitar</button>
                                </form>
                                <form action="<?= URL_BASE ?>/equipe/rejeitar-solicitacao" method="post">
                                    <input type="hidden" name="equipe_id" value="<?= $equipe->getId() ?>">
                                    <input type="hidden" name="usuario_id" value="<?= $pendente['id'] ?>">
                                    <button type="submit" class="border border-red-500 text-red-400 hover:bg-red-500/20 px-3 py-1 text-xs font-bold transition">Recusar</button>
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
                $uId = is_object($usr)
                    ? (method_exists($usr, 'getId') ? $usr->getId() : null)
                    : ($usr['id'] ?? null);

                $uNome = is_object($usr)
                    ? (method_exists($usr, 'getNome') ? $usr->getNome() : '')
                    : ($usr['nome'] ?? '');

                $uImagem = $obterImagem($usr);
                $uImagemUrl = $urlImagemEquipe(
                    $uImagem,
                    IMG_URL_BASE . '/perfil.png'
                );

                // Como estamos em uma equipe, priorize o papel da equipe.
                $uPapel = is_object($usr)
                    ? (
                        method_exists($usr, 'getPapelEquipe')
                            ? $usr->getPapelEquipe()
                            : (method_exists($usr, 'getPapelProjeto')
                                ? $usr->getPapelProjeto()
                                : 'participante')
                    )
                    : ($usr['papel'] ?? 'participante');

                $ehOutroUsuario = (int)$uId !== (int)$usuarioLogadoId;
                ?>

                <div class="border-2 border-[#07556A] bg-[#06141C] p-4
                            flex flex-col gap-3 group
                            hover:border-[#13F3F7] transition">

                    <!-- INFORMAÇÕES DO INTEGRANTE -->
                    <div class="flex items-center justify-between gap-3">

                        <a href="<?= URL_BASE ?>/usuario/perfil?id=<?= (int)$uId ?>"
                        class="flex items-center gap-3 min-w-0 flex-1">

                            <img
                                src="<?= htmlspecialchars($uImagemUrl, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Foto de <?= htmlspecialchars($uNome, ENT_QUOTES, 'UTF-8') ?>"
                                class="w-12 h-12 rounded-full object-cover
                                    border border-[#07556A] shrink-0"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='<?= htmlspecialchars(IMG_URL_BASE . '/perfil.png', ENT_QUOTES, 'UTF-8') ?>';"
                            >

                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#F2FEFE]
                                        truncate group-hover:text-[#13F3F7] transition">
                                    <?= htmlspecialchars(
                                        html_entity_decode((string)$uNome),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                                <span class="inline-block text-[.6rem] font-bold
                                            uppercase tracking-wider px-1.5 py-0.5
                                            border border-[#0795A5] text-[#13F3F7]
                                            bg-[#07556A]/30 mt-1">
                                    <?= htmlspecialchars(ucfirst((string)$uPapel)) ?>
                                </span>
                            </div>
                        </a>

                        <!-- REMOVER INTEGRANTE -->
                        <?php if ($eCoordenador && $ehOutroUsuario): ?>
                            <form
                                action="<?= URL_BASE ?>/equipe/remover-membro"
                                method="POST"
                                onsubmit="return confirm('Deseja realmente remover este participante da equipe?');"
                                class="shrink-0"
                            >
                                <input
                                    type="hidden"
                                    name="equipe_id"
                                    value="<?= (int)$equipe->getId() ?>"
                                >

                                <input
                                    type="hidden"
                                    name="usuario_id"
                                    value="<?= (int)$uId ?>"
                                >

                                <button
                                    type="submit"
                                    title="Remover participante"
                                    class="text-red-400 hover:text-red-300
                                        border border-transparent
                                        hover:border-red-500/40
                                        p-1.5 text-base transition"
                                >
                                    ✕
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>

                    <!-- ALTERAR CARGO -->
                    <?php if ($eCoordenador && $ehOutroUsuario): ?>
                        <form
                            action="<?= URL_BASE ?>/equipe/alterar-papel"
                            method="POST"
                            class="flex items-center justify-between gap-2
                                pt-3 border-t border-[#07556A]/50"
                        >
                            <input
                                type="hidden"
                                name="equipe_id"
                                value="<?= (int)$equipe->getId() ?>"
                            >

                            <input
                                type="hidden"
                                name="usuario_id"
                                value="<?= (int)$uId ?>"
                            >

                            <label class="text-[.65rem] text-[#91B5BD] uppercase font-bold">
                                Cargo:
                            </label>

                            <select
                                name="papel"
                                onchange="this.form.submit()"
                                class="bg-[#000505] text-[#13F3F7]
                                    border border-[#07556A]
                                    text-[.7rem] px-2 py-1
                                    focus:outline-none"
                            >
                                <option value="participante"
                                    <?= $uPapel === 'participante' ? 'selected' : '' ?>>
                                    Participante
                                </option>

                                <option value="coordenador"
                                    <?= $uPapel === 'coordenador' ? 'selected' : '' ?>>
                                    Coordenador
                                </option>
                            </select>
                        </form>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            </div>
        <?php else: ?>
            <p class="text-xs text-[#91B5BD]">Nenhum integrante associado a esta equipe.</p>
        <?php endif; ?>
    </div>

</div>

<script>
function alternarAbaEquipe(aba) {
    const abas = {
        projetos: { el: document.getElementById('aba-projetos'), titulo: 'Projetos da Equipe', icone: 'projeto-icon.png' },
        forum: { el: document.getElementById('aba-forum'), titulo: 'Fórum da Equipe', icone: 'forum-icon.png' },
        usuarios: { el: document.getElementById('aba-usuarios'), titulo: 'Usuários da Equipe', icone: 'usuarios-icon.svg' }
    };

    const tituloEl = document.getElementById('tituloSecaoAtual');
    const iconeEl = document.getElementById('iconeSecaoAtual');

    for (const chave in abas) {
        if (abas[chave].el) {
            abas[chave].el.classList.toggle('hidden', chave !== aba);
        }
    }

    if (abas[aba]) {
        if (tituloEl) tituloEl.innerText = abas[aba].titulo;
        if (iconeEl) iconeEl.src = '<?= IMG_URL_BASE ?>/' + abas[aba].icone;
    }
}
</script>
<?php endif; ?>