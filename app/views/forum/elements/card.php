<?php if (isset($forum)): ?>
<?php
    $autorForum = method_exists($forum, 'getUsuario') ? $forum->getUsuario() : null;
    $imagemAutor = ($autorForum && method_exists($autorForum, 'getImagem') && $autorForum->getImagem())
        ? URL_BASE . '/arquivo?arquivo=' . urlencode($autorForum->getImagem())
        : IMG_URL_BASE . '/perfil.png';
    $nomeUsuarioForum = $autorForum && method_exists($autorForum, 'getNomeUsuario')
        ? $autorForum->getNomeUsuario()
        : 'usuario';
    $conteudoForum = method_exists($forum, 'getConteudo')
        ? htmlspecialchars(html_entity_decode($forum->getConteudo(), ENT_QUOTES, 'UTF-8'))
        : '';
    $fId = method_exists($forum, 'getId') ? $forum->getId() : ($forum['id'] ?? null);
    $dataPublicacao = ($forum->getCriadoEm() instanceof DateTimeInterface)
        ? $forum->getCriadoEm()->format('d/m/Y H:i')
        : '';
?>
<div class="relative border-2 border-[#07556A] bg-[#06141C] p-4 hover:border-[#13F3F7] transition flex flex-col justify-between group">
    <div>
        <!-- AUTOR E DATA -->
        <div class="flex items-center gap-3 mb-3 pr-10">
            <img 
                src="<?= htmlspecialchars($imagemAutor) ?>" 
                alt="Foto de <?= htmlspecialchars($nomeUsuarioForum) ?>" 
                class="w-10 h-10 object-cover border border-[#07556A] shrink-0"
                loading="lazy" 
                onerror="this.onerror=null;this.src='<?= IMG_URL_BASE ?>/perfil.png';"
            >
            <div class="min-w-0">
                <p class="text-xs text-[#13F3F7] font-bold truncate">@<?= htmlspecialchars(html_entity_decode($nomeUsuarioForum)) ?></p>
                <?php if (!empty($dataPublicacao)): ?>
                    <span class="text-[.6rem] text-[#91B5BD] font-mono block"><?= $dataPublicacao ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- CONTEÚDO COM LINK -->
        <a href="<?= URL_BASE ?>/forum/perfil?id=<?= (int)$fId ?>" class="block">
            <p class="text-sm text-[#F2FEFE] group-hover:text-[#13F3F7] transition whitespace-pre-line line-clamp-4">
                <?= nl2br((string)$conteudoForum) ?>
            </p>
        </a>
    </div>

    <!-- RODAPÉ: VER DISCUSSÃO / COMENTÁRIOS -->
    <div class="mt-4 pt-3 border-t border-[#07556A]/50 flex items-center justify-between">
        <a href="<?= URL_BASE ?>/forum/perfil?id=<?= (int)$fId ?>" class="text-xs font-bold text-[#13F3F7] hover:underline flex items-center gap-1">
            💬 Ver discussão e respostas →
        </a>
        <span class="text-[.65rem] text-[#91B5BD] uppercase font-mono">
            <?= htmlspecialchars(ucfirst($forum->getVisibilidade() ?? 'Público')) ?>
        </span>
    </div>

    <!-- BOTÃO DE DENÚNCIA -->
    <?php
    $denunciaAlvo = [
        "tipo"    => "forum",
        "id"      => (int)$fId,
        "nome"    => mb_strimwidth($forum->getConteudo(), 0, 50, '...'),
        "autores" => $autorForum ? [$autorForum->getId()] : [],
        "classe"  => "absolute right-2 top-2 z-10"
    ];
    include(__DIR__ . "/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif; ?>