<?php if(isset($forum)):?>
<div class="relative bg-black/80 p-4 pr-14 w-full border border-[#07556A] hover:border-[#13F3F7] transition">
    <p class="text-xs font-bold text-[#13F3F7]">@<?= htmlspecialchars($forum->getUsuario()->getNomeUsuario() ?? "Gasparzinho") ?></p>
    <p class="text-sm text-[#F2FEFE] mt-2 whitespace-pre-line"><?= htmlspecialchars($forum->getConteudo()) ?></p>
    
    <?php
    $adminAlvo = [
        "tipo"         => "forum",
        "id"           => $forum->getId(),
        "nome"         => $forum->getConteudo(),
        "visibilidade" => $forum->getVisibilidade(),
        "responsavel"  => $forum->getUsuario()->getNomeUsuario() ? "@".$forum->getUsuario()->getNomeUsuario() : null,
        "compacto"     => true
    ];
    if (file_exists(__DIR__."/../../elements/adminBarra.php")) {
        include(__DIR__."/../../elements/adminBarra.php");
    }

    $denunciaAlvo = [
        "tipo"    => "forum",
        "id"      => $forum->getId(),
        "nome"    => mb_strimwidth($forum->getConteudo(), 0, 50, '...'),
        "autores" => [$forum->getUsuario()->getId()],
        "classe"  => "absolute right-1.5 top-1.5 z-10"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif;?>