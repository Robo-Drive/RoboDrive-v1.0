<?php if (isset($equipe)): ?>
<?php
$usuarioLogado = $_SESSION['usuario_logado'] ?? null;
$usuarioLogadoId = $usuarioLogado && method_exists($usuarioLogado, 'getId') ? $usuarioLogado->getId() : null;
$eAdmin = $usuarioLogado && method_exists($usuarioLogado, 'getRegra') && $usuarioLogado->getRegra() === 'admin';
$eCoordenador = $eAdmin;
$eIntegrante = false;

$totalMembros = count($usuarios ?? []);
$totalCoordenadores = 0;
$papelUsuarioLogado = null;
$outrosMembros = [];

if (!empty($usuarios)) {
    foreach ($usuarios as $membro) {
        $membroId = is_object($membro)
            ? (method_exists($membro, 'getId') ? $membro->getId() : null)
            : ($membro['id'] ?? null);
        $membroNome = is_object($membro)
            ? (method_exists($membro, 'getNome') ? $membro->getNome() : '')
            : ($membro['nome'] ?? '');
        $membroPapel = is_object($membro)
            ? (method_exists($membro, 'getPapelEquipe') ? $membro->getPapelEquipe() : (method_exists($membro, 'getPapelProjeto') ? $membro->getPapelProjeto() : ''))
            : ($membro['papel'] ?? '');

        if ($membroPapel === 'coordenador') {
            $totalCoordenadores++;
        }

        if ($membroId == $usuarioLogadoId) {
            $eIntegrante = true;
            $papelUsuarioLogado = $membroPapel;
            if ($membroPapel === 'coordenador') {
                $eCoordenador = true;
            }
        } else {
            $outrosMembros[] = [
                'id' => $membroId,
                'nome' => $membroNome,
                'papel' => $membroPapel
            ];
        }
    }
}

$ehUnicoCoordenador = ($papelUsuarioLogado === 'coordenador' && $totalCoordenadores <= 1);
$equipeAtiva = ($equipe->isStatus() !== false);
$podeSairEquipe = ($eIntegrante && $totalMembros > 1 && $equipeAtiva);

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
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="font-['Orbitron'] text-2xl sm:text-4xl font-black text-[#F2FEFE]">
                    <?= texto($equipe->getNome()) ?>
                </h1>
                <span class="text-[.65rem] font-bold uppercase tracking-[.2em] px-2.5 py-1 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/20">
                    <?= $eCoordenador ? 'Coordenador' : ($eIntegrante ? 'Membro' : 'Visitante') ?>
                </span>
            </div>
            <?php if (method_exists($equipe, 'getDescricao') && $equipe->getDescricao()): ?>
                <p class="text-[#91B5BD] text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed whitespace-pre-line">
                    <?= texto($equipe->getDescricao()) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <?php if ($eCoordenador && $equipeAtiva): ?>
                <form action="<?= URL_BASE ?>/equipe/editar" method="post">
                    <input type="hidden" name="id" value="<?= (int) $equipe->getId() ?>">
                    <button type="submit" class="rd-btn rd-btn-primary rd-btn-sm">Editar Equipe</button>
                </form>
                <?php if ($eAdmin && $papelUsuarioLogado !== 'coordenador'): ?>
                    <button
                        type="button"
                        class="rd-btn rd-btn-danger rd-btn-sm"
                        data-desativar
                        data-tipo="equipe"
                        data-id="<?= (int) $equipe->getId() ?>"
                        data-nome="<?= texto($equipe->getNome()) ?>"
                    >Desativar</button>
                <?php else: ?>
                    <form action="<?= URL_BASE ?>/equipe/desativar" method="post" data-confirmar="Deseja realmente desativar esta equipe?" >
                        <input type="hidden" name="id" value="<?= (int) $equipe->getId() ?>">
                        <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">Desativar</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($podeSairEquipe): ?>
                <?php if ($ehUnicoCoordenador): ?>
                    <button type="button" onclick="document.getElementById('modalSairEquipe').showModal()" class="rd-btn rd-btn-danger rd-btn-sm">
                        Sair da Equipe
                    </button>
                <?php else: ?>
                    <form action="<?= URL_BASE ?>/equipe/remover-membro" method="post" data-confirmar="Deseja realmente sair desta equipe?" >
                        <input type="hidden" name="equipe_id" value="<?= (int) $equipe->getId() ?>">
                        <input type="hidden" name="usuario_id" value="<?= (int) $usuarioLogadoId ?>">
                        <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">
                            Sair da Equipe
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Equipe desativada: o admin pode reativar -->
            <?php if (!$equipeAtiva): ?>
                <span class="rd-badge rd-badge-private">Desativada</span>
                <?php
                $ativarAlvo = ["tipo" => "equipe", "id" => (int) $equipe->getId(), "ativo" => false];
                include(__DIR__ . "/../../elements/ativarBotao.php");
                ?>
            <?php endif; ?>

            <a href="<?= URL_BASE ?>/usuario/perfil" class="rd-btn rd-btn-ghost">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true"><polyline points="15 5 8 12 15 19"/></svg>
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
            if ($equipeAtiva) {
                include(__DIR__ . "/../../elements/denunciaBotao.php");
            }
            ?>
        </div>
    </div>

    <!-- 2. BARRA SUPERIOR COM O SELECT DE VISUALIZAÇÃO -->
    <div class="border-2 border-[#07556A] bg-[#06141C] p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="text-xs uppercase text-[#91B5BD] tracking-wider font-['Orbitron']">Visualização:</span>
            <img id="iconeSecaoAtual" src="<?= IMG_URL_BASE ?>/projeto-icon.png" alt="" class="w-5 h-5 object-contain">
            <span class="text-xs text-[#F2FEFE]" id="tituloSecaoAtual">Projetos da Equipe</span>
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
                <a href="<?= URL_BASE ?>/projeto/cadastro?equipe_id=<?= (int) $equipe->getId() ?>" class="rd-btn rd-btn-primary rd-btn-sm">+ Novo Projeto</a>
            <?php endif; ?>
        </div>

        <form data-busca data-alvo="#listaProjetosEquipe" method="get" action="<?= URL_BASE ?>/equipe/perfil?id=<?= (int) $equipe->getId() ?>" class="flex gap-3">
            <input type="hidden" name="secao" value="projetos">
            <input type="text" name="busca" value="<?= htmlspecialchars($buscaProjetos ?? '') ?>" placeholder="Pesquisar projetos da equipe pelo nome ou descrição..." class="rd-input w-full flex-1" autocomplete="off">
            <button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Buscar</button>
        </form>
        <div id="listaProjetosEquipe" data-atualizar data-resultados class="space-y-4">
        <?php if (!empty($projetos)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($projetos as $projeto): ?>
                    <?php
                    $projetoNome = method_exists($projeto, 'getNome') ? $projeto->getNome() : ($projeto['nome'] ?? 'Projeto');
                    $projetoDescricao = method_exists($projeto, 'getDescricao') ? $projeto->getDescricao() : ($projeto['descricao'] ?? '');
                    $pId = method_exists($projeto, 'getId') ? $projeto->getId() : ($projeto['id'] ?? null);
                    $pCat = method_exists($projeto, 'getCategoria') ? $projeto->getCategoria()?->getNome() : 'Hardware';
                    ?>
                    <div class="rd-glow p-4 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[.6rem] font-bold uppercase tracking-wider px-2 py-0.5 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/20 font-mono">
                                    <?= texto($pCat ?? 'Geral') ?>
                                </span>
                                <span class="text-[.65rem] text-[#91B5BD] uppercase font-mono">Projeto</span>
                            </div>

                            <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= (int)$pId ?>" class="block mt-1">
                                <h3 class="font-['Orbitron'] text-sm font-bold text-[#F2FEFE] group-hover:text-[#13F3F7] transition truncate">
                                    <?= texto((string)$projetoNome) ?>
                                </h3>
                            </a>

                            <p class="text-xs text-[#91B5BD] line-clamp-3 mt-2 leading-relaxed">
                                <?= texto((string)$projetoDescricao) ?>
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-[#07556A]/60 flex justify-end">
                            <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= (int)$pId ?>" class="text-xs font-bold text-[#13F3F7] hover:underline flex items-center gap-1">
                                Ver projeto →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">Nenhum projeto registrado para esta equipe ainda.</div>
        <?php endif; ?>
        </div>
    </div>

    <!-- 4. SEÇÃO: FÓRUM DA EQUIPE -->
    <div id="aba-forum" class="space-y-4 hidden">
        <div class="flex items-center justify-between border-b border-[#07556A] pb-2">
            <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">02. FÓRUM DE DISCUSSÃO</h2>
            <?php if ($eIntegrante): ?>
                <a href="<?= URL_BASE ?>/forum/cadastro?equipe_id=<?= (int) $equipe->getId() ?>" class="rd-btn rd-btn-primary rd-btn-sm">+ Nova Mensagem</a>
            <?php endif; ?>
        </div>

        <form data-busca data-alvo="#listaForumEquipe" method="get" action="<?= URL_BASE ?>/equipe/perfil?id=<?= (int) $equipe->getId() ?>" class="flex gap-3">
            <input type="hidden" name="secao" value="forum">
            <input type="text" name="busca" value="<?= htmlspecialchars($buscaForum ?? '') ?>" placeholder="Pesquisar postagens da equipe pelo conteúdo..." class="rd-input w-full flex-1" autocomplete="off">
            <button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Buscar</button>
        </form>
        <div id="listaForumEquipe" data-atualizar data-resultados class="space-y-4">
        <?php if (!empty($foruns)): ?>
            <div class="space-y-3">
                 <?php foreach ($foruns as $forum): ?>
                    <?php
                    $autorForum = method_exists($forum, 'getUsuario') ? $forum->getUsuario() : null;
                    $imagemAutor = $autorForum ? $obterImagem($autorForum) : null;
                    $imagemAutorUrl = $urlImagemEquipe($imagemAutor, IMG_URL_BASE . '/perfil.png');
                    $nomeUsuarioForum = $autorForum && method_exists($autorForum, 'getNomeUsuario') ? $autorForum->getNomeUsuario() : 'usuario';
                    $conteudoForum = method_exists($forum, 'getConteudo') ? texto($forum->getConteudo()) : '';
                    $fId = method_exists($forum, 'getId') ? $forum->getId() : ($forum['id'] ?? null);
                    ?>
                    <div class="border-2 border-[#07556A] bg-[#06141C] p-4 hover:border-[#13F3F7] transition flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <img src="<?= htmlspecialchars($imagemAutorUrl) ?>"
                                     alt="Foto de <?= texto($nomeUsuarioForum) ?>"
                                     class="w-10 h-10 object-cover border-2 border-[#13F3F7] shadow-[0_0_10px_rgba(19,243,247,.35)] shrink-0"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='<?= htmlspecialchars(IMG_URL_BASE . '/perfil.png') ?>';">
                                <p class="text-xs text-[#13F3F7] font-bold">@<?= texto($nomeUsuarioForum) ?></p>
                            </div>
                            
                            <!-- Link no texto do fórum -->
                            <a href="<?= URL_BASE ?>/forum/perfil?id=<?= (int)$fId ?>" class="block">
                                <p class="text-sm text-[#F2FEFE] group-hover:text-[#13F3F7] transition whitespace-pre-line">
                                    <?= nl2br((string)$conteudoForum) ?>
                                </p>
                            </a>
                        </div>

                        <!-- Botão de ir para a postagem e responder -->
                        <div class="mt-4 pt-3 border-t border-[#07556A]/60 flex justify-end">
                            <a href="<?= URL_BASE ?>/forum/perfil?id=<?= (int)$fId ?>" class="text-xs font-bold text-[#13F3F7] hover:underline flex items-center gap-1">
                                Ver discussão e comentários →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
             </div>
        <?php else: ?>
            <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">Nenhuma publicação no fórum desta equipe no momento.</div>
        <?php endif; ?>
        </div>
    </div>

    <!-- 5. SEÇÃO: USUÁRIOS DA EQUIPE (EXCLUSIVO PARA COORDENADOR) -->
    <div id="aba-usuarios" class="space-y-6 hidden" data-atualizar>
        <!-- BOX DO HASH -->
        <div class="border-2 border-[#13F3F7] bg-[#082B3A] p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <p class="text-[.65rem] font-bold uppercase tracking-widest text-[#13F3F7]">Código Hash de Entrada da Equipe:</p>
                <p class="text-xs text-[#91B5BD] mt-0.5">Envie este código e a senha para novos membros solicitarem entrada.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-base font-black px-3 py-1 bg-[#000505] border border-[#13F3F7] text-[#F2FEFE] tracking-widest select-all">
                    <?= htmlspecialchars($equipe->getHashAcesso() ?? 'INDISPONÍVEL') ?>
                </span>
            </div>
        </div>

        <!-- SOLICITAÇÕES PENDENTES DE ENTRADA (atualizadas automaticamente) -->
        <?php if ($eCoordenador): ?>
        <div id="solicitacoesEquipe" data-atualizar>
            <?php include __DIR__ . "/solicitacoes.php"; ?>
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
                                alt="Foto de <?= texto($uNome) ?>"
                                class="w-12 h-12 object-cover
                                    border-2 border-[#13F3F7] shadow-[0_0_10px_rgba(19,243,247,.35)] shrink-0"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='<?= htmlspecialchars(IMG_URL_BASE . '/perfil.png', ENT_QUOTES, 'UTF-8') ?>';"
                            >

                            <div class="min-w-0">
                                <p class="text-xs font-bold text-[#F2FEFE]
                                        truncate group-hover:text-[#13F3F7] transition">
                                    <?= texto((string)$uNome) ?>
                                </p>

                                <span class="inline-block text-[.6rem] font-bold
                                            uppercase tracking-wider px-1.5 py-0.5
                                            border border-[#0795A5] text-[#13F3F7]
                                            bg-[#07556A]/20 mt-1">
                                    <?= htmlspecialchars(ucfirst((string)$uPapel)) ?>
                                </span>
                            </div>
                        </a>

                        <!-- REMOVER INTEGRANTE -->
                        <?php if ($eCoordenador && $ehOutroUsuario): ?>
                            <form
                                action="<?= URL_BASE ?>/equipe/remover-membro"
                                method="POST"
                                data-confirmar="Deseja realmente remover este participante da equipe?" class="shrink-0"
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
                                    class="rd-btn-icon" aria-label="Remover participante"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>

                    <!-- ALTERAR CARGO -->
                    <?php if ($eCoordenador && $ehOutroUsuario): ?>
                        <form
                            action="<?= URL_BASE ?>/equipe/alterar-papel"
                            method="POST"
                            data-erro-notificacao
                            class="flex items-center justify-between gap-2
                                pt-3 border-t border-[#07556A]/60"
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
                                onchange="this.form.requestSubmit()"
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

<?php if ($podeSairEquipe && $ehUnicoCoordenador): ?>
<dialog id="modalSairEquipe" aria-labelledby="modalSairEquipeTitulo" class="rd-scroll-hidden m-auto max-h-[90dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-0 text-[#F2FEFE] shadow-[8px_8px_0_#07556A] backdrop:bg-[#000505]/85">
    <div class="h-[3px] w-full bg-[#F2A058]"></div>
    <form action="<?= URL_BASE ?>/equipe/remover-membro" method="post" class="flex flex-col">
        <input type="hidden" name="equipe_id" value="<?= (int) $equipe->getId() ?>">
        <input type="hidden" name="usuario_id" value="<?= (int) $usuarioLogadoId ?>">

        <div class="flex items-start justify-between gap-4 border-b-[3px] border-[#07556A] p-5 sm:p-6">
            <div>
                <p class="rd-eyebrow !text-[#F2A058]">Passar Coordenação</p>
                <h2 id="modalSairEquipeTitulo" class="rd-heading text-xl uppercase sm:text-2xl">SAIR DA <span>EQUIPE</span></h2>
            </div>
            <button type="button" onclick="document.getElementById('modalSairEquipe').close()" aria-label="Fechar" class="rd-btn-icon">
                <img src="<?= IMG_URL_BASE ?>/close-icon.png" alt="" class="h-4 w-4">
            </button>
        </div>

        <div class="flex flex-col gap-6 p-5 sm:p-6">
            <p class="border-l-4 border-[#F2A058] pl-4 text-sm leading-relaxed text-[#91B5BD]">
                Você é o <strong>único coordenador</strong> desta equipe. Para sair, é obrigatório selecionar outro integrante para assumir o cargo de coordenador.
            </p>

            <div class="rd-field">
                <label for="novoCoordenadorEquipe" class="rd-label">Novo Coordenador da Equipe</label>
                <select id="novoCoordenadorEquipe" name="novo_coordenador_id" class="rd-select">
                    <option value="">Selecione um integrante</option>
                    <?php foreach ($outrosMembros as $outro): ?>
                        <option value="<?= (int) $outro['id'] ?>">
                            <?= texto($outro['nome']) ?> (<?= htmlspecialchars(ucfirst($outro['papel'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t-[3px] border-[#07556A] p-5 sm:flex-row sm:justify-end sm:p-6">
            <button type="button" onclick="document.getElementById('modalSairEquipe').close()" class="rd-btn rd-btn-ghost">Cancelar</button>
            <button type="submit" class="rd-btn rd-btn-danger-solid">Transferir e Sair</button>
        </div>
    </form>
</dialog>
<?php endif; ?>

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