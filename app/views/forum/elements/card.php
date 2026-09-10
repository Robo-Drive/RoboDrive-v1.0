<?php if(isset($forum)):?>
<article class="rd-card w-full">
    <div class="mb-3 flex items-center gap-3">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center border-[3px] border-[#13F3F7] font-['Orbitron'] text-sm font-black text-[#13F3F7]">
            <?= strtoupper(substr($forum->getUsuario()->getNomeUsuario() ?? "?", 0, 1)) ?>
        </div>
        <div>
            <p class="text-sm font-bold text-[#F2FEFE]">@<?= $forum->getUsuario()->getNomeUsuario() ?? "Gasparzinho" ?></p>
            <p class="text-[.65rem] uppercase tracking-[.1em] text-[#4E6B72]"><?= $forum->getCriadoEm()?->format('d/m/Y H:i') ?></p>
        </div>
    </div>
    <p class="whitespace-pre-line text-sm leading-relaxed text-[#F2FEFE]"><?= $forum->getConteudo() ?></p>
</article>
<?php endif;?>
