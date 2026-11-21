<?php
$titulo = "Postagem do Fórum";
$header = "Fórum";
if (isset($forum)):
include_once(__DIR__ . "/../elements/header.php");

$usuarioLogado = $_SESSION["usuario_logado"];
$eAdmin = ($usuarioLogado->getRegra() === "admin");
$eAutor = ($forum->getUsuario()->getId() === $usuarioLogado->getId());
$forumAtivo = ($forum->isStatus() !== false);
$eCoordenadorEquipe = !empty($coordenaEquipe);

$fotoAutor = $forum->getUsuario()->getImagem()
    ? URL_BASE . '/arquivo?arquivo=' . urlencode($forum->getUsuario()->getImagem())
    : IMG_URL_BASE . '/perfil.png';
?>

<div class="rd-shell">
    <?php include_once(__DIR__ . "/../elements/sidebar.php"); ?>

    <main class="rd-content overflow-y-auto rd-scroll-hidden p-4 sm:p-8 w-full space-y-6">

        <!-- 1. CABEÇALHO DA POSTAGEM -->
        <div class="border-b-2 border-[#07556A] pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="rd-eyebrow">FÓRUM DA COMUNIDADE</p>
                <h1 class="font-['Orbitron'] text-xl sm:text-3xl font-black text-[#F2FEFE]">
                    DISCUSSÃO DO FÓRUM
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <?php if (!$forumAtivo): ?>
                    <span class="rd-badge rd-badge-private">Desativada</span>
                    <?php
                    $ativarAlvo = ["tipo" => "forum", "id" => (int) $forum->getId(), "ativo" => false];
                    include(__DIR__ . "/../elements/ativarBotao.php");
                    ?>
                <?php endif; ?>

                <?php if (($eAdmin || $eAutor || $eCoordenadorEquipe) && $forumAtivo): ?>
                    <?php if ($eAdmin || $eAutor): ?>
                    <form action="<?= URL_BASE ?>/forum/editar" method="post">
                        <input type="hidden" name="id" value="<?= $forum->getId() ?>">
                        <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">
                            Editar
                        </button>
                    </form>
                    <?php endif; ?>

                    <?php if (($eAdmin || $eCoordenadorEquipe) && !$eAutor): ?>
                        <button
                            type="button"
                            class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition"
                            data-desativar
                            data-tipo="forum"
                            data-id="<?= $forum->getId() ?>"
                            data-nome="<?= texto(mb_strimwidth(html_entity_decode($forum->getConteudo()), 0, 50, '...')) ?>"
                            data-responsavel="@<?= texto($forum->getUsuario()->getNomeUsuario() ?? '') ?>"
                        >
                            Desativar
                        </button>
                    <?php else: ?>
                        <form action="<?= URL_BASE ?>/forum/desativar" method="post" onsubmit="return confirm('Deseja realmente desativar esta postagem?');">
                            <input type="hidden" name="id" value="<?= $forum->getId() ?>">
                            <button type="submit" class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition">
                                Desativar
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= URL_BASE ?>/forum/listar" class="border-2 border-[#07556A] px-4 py-2 text-xs font-bold text-[#F2FEFE] hover:border-[#13F3F7] transition">
                    Voltar
                </a>
            </div>
        </div>

        <!-- 2. CARD PRINCIPAL DA POSTAGEM (LARGURA TOTAL) -->
        <article class="border-2 border-[#07556A] bg-[#06141C] p-5 sm:p-6 space-y-5 w-full">
            
            <!-- AUTOR, DATA E DENÚNCIA -->
            <div class="flex items-center justify-between border-b border-[#07556A]/60 pb-4">
                <div class="flex items-center gap-3 min-w-0">
                    <img 
                        src="<?= htmlspecialchars($fotoAutor) ?>" 
                        alt="Foto do autor" 
                        class="w-12 h-12 object-cover border-2 border-[#07556A] shrink-0"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= IMG_URL_BASE ?>/perfil.png';"
                    >
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#F2FEFE] truncate">
                            @<?= texto($forum->getUsuario()->getNomeUsuario() ?? 'Usuário') ?>
                        </p>
                        <span class="text-xs text-[#91B5BD] font-mono">
                            Publicado em <?= $forum->getCriadoEm() ? $forum->getCriadoEm()->format('d/m/Y H:i') : 'Data não informada' ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-[.65rem] font-bold uppercase tracking-wider px-2.5 py-1 border border-[#07556A] text-[#13F3F7] bg-[#000505]">
                        <?= htmlspecialchars(ucfirst($forum->getVisibilidade() ?? 'Público')) ?>
                    </span>

                    <?php
                    // Botão de denúncia
                    $denunciaAlvo = [
                        "tipo"    => "forum",
                        "id"      => $forum->getId(),
                        "nome"    => mb_strimwidth($forum->getConteudo(), 0, 50, '...'),
                        "autores" => [$forum->getUsuario()->getId()],
                        "classe"  => "shrink-0"
                    ];
                    if ($forumAtivo) {
                        include(__DIR__ . "/../elements/denunciaBotao.php");
                    }
                    ?>
                </div>
            </div>

            <!-- CONTEÚDO DA POSTAGEM -->
            <div class="text-[#F2FEFE] text-base leading-relaxed whitespace-pre-line bg-[#000505] p-5 border border-[#07556A]/60 w-full">
                <?= nl2br(texto($forum->getConteudo())) ?>
            </div>

        </article>

        <!-- 3. SEÇÃO DE COMENTÁRIOS DO FÓRUM (MESMA LARGURA TOTAL) -->
        <section class="border-2 border-[#07556A] bg-[#06141C] p-5 sm:p-6 space-y-6 w-full">
            <div class="flex items-center justify-between border-b border-[#07556A] pb-3">
                <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron']">
                    RESPOSTAS / COMENTÁRIOS (<?= count($comentarios ?? []) ?>)
                </h2>
            </div>

            <!-- FORMULÁRIO DE NOVO COMENTÁRIO -->
            <form action="<?= URL_BASE ?>/comentario/salvar" method="post" class="space-y-3 w-full">
                <input type="hidden" name="tipo" value="forum">
                <input type="hidden" name="origem_id" value="<?= $forum->getId() ?>">
                
                <div class="rd-field w-full">
                    <label for="comentarioConteudo" class="rd-label">Deixe sua resposta ou contribuição</label>
                    <textarea 
                        id="comentarioConteudo" 
                        name="conteudo" 
                        rows="3" 
                        maxlength="2000" 
                        placeholder="Escreva sua resposta para esta postagem..." 
                        class="rd-textarea !bg-[#000505] w-full"
                    ></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rd-btn rd-btn-primary !py-2 !px-4 text-xs">
                        Publicar Comentário
                    </button>
                </div>
            </form>

            <!-- LISTAGEM DE COMENTÁRIOS -->
            <div class="space-y-4 pt-4 border-t border-[#07556A]/60 w-full">
                <?php if (!empty($comentarios)): ?>
                    <?php foreach ($comentarios as $coment): ?>
                        <?php
                            $fotoComent = $coment->getUsuario()->getImagem()
                                ? URL_BASE . '/arquivo?arquivo=' . urlencode($coment->getUsuario()->getImagem())
                                : IMG_URL_BASE . '/perfil.png';
                        ?>
                        <div class="border border-[#07556A] bg-[#000505] p-4 space-y-3 w-full hover:border-[#13F3F7]/50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img 
                                        src="<?= htmlspecialchars($fotoComent) ?>" 
                                        alt="Foto" 
                                        class="w-9 h-9 object-cover border border-[#07556A] shrink-0"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='<?= IMG_URL_BASE ?>/perfil.png';"
                                    >
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-[#F2FEFE] truncate">
                                            @<?= texto($coment->getUsuario()->getNomeUsuario()) ?>
                                        </p>
                                        <span class="text-[.65rem] text-[#91B5BD] font-mono">
                                            <?= $coment->getCriadoEm() ? $coment->getCriadoEm()->format('d/m/Y H:i') : '' ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <?php if ($coment->getUsuario()->getId() === $usuarioLogado->getId() || $eAdmin): ?>
                                        <?php if ($eAdmin && $coment->getUsuario()->getId() !== $usuarioLogado->getId()): ?>
                                            <button
                                                type="button"
                                                title="Desativar comentário"
                                                class="text-red-400 hover:text-red-300 text-xs px-2 py-1 font-bold"
                                                data-desativar
                                                data-tipo="comentario"
                                                data-origem="forum"
                                                data-id="<?= $coment->getId() ?>"
                                                data-nome="<?= texto(mb_strimwidth(html_entity_decode($coment->getConteudo()), 0, 50, '...')) ?>"
                                                data-responsavel="@<?= texto($coment->getUsuario()->getNomeUsuario()) ?>"
                                            >
                                                Desativar
                                            </button>
                                        <?php else: ?>
                                            <form action="<?= URL_BASE ?>/comentario/desativar" method="post" onsubmit="return confirm('Deseja desativar este comentário?');">
                                                <input type="hidden" name="id" value="<?= $coment->getId() ?>">
                                                <input type="hidden" name="tipo" value="forum">
                                                <button type="submit" title="Desativar comentário" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 font-bold">
                                                    Desativar
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php
                                    $denunciaAlvo = [
                                        "tipo"    => "comentario",
                                        "id"      => $coment->getId(),
                                        "nome"    => mb_strimwidth($coment->getConteudo(), 0, 50, '...'),
                                        "autores" => [$coment->getUsuario()->getId()],
                                        "classe"  => "shrink-0"
                                    ];
                                    include(__DIR__ . "/../elements/denunciaBotao.php");
                                    ?>
                                </div>
                            </div>

                            <p class="text-sm text-[#F2FEFE] leading-relaxed whitespace-pre-line pl-12">
                                <?= nl2br(texto($coment->getConteudo())) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="border border-dashed border-[#07556A] bg-[#06141C] p-6 text-center text-[#91B5BD] text-xs">
                        Nenhum comentário nesta discussão ainda. Seja o primeiro a responder!
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>
</div>

<?php
include_once(__DIR__ . "/../elements/adminModais.php");
include_once(__DIR__ . "/../elements/denunciaModal.php");
include_once(__DIR__ . "/../elements/footer.php");
endif;
?>