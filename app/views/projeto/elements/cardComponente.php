<?php if(isset($componente)):?>
<a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="flex w-full flex-col gap-1 border-[3px] border-[#07556A] bg-[#082B3A] px-4 py-3 no-underline transition-colors duration-200 hover:border-[#13F3F7] sm:w-[220px]">
    <span class="font-['Orbitron'] text-sm font-black text-[#F2FEFE]"><?= $componente->getNome() ?></span>
    <span class="text-[.65rem] uppercase tracking-[.1em] text-[#91B5BD]">Quantidade: <?= $componente->getQuantidade() ?></span>
</a>
<?php endif;?>
