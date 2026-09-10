<?php if(isset($projeto)):?>
<a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>" class="rd-card rd-card-hover block w-full sm:w-[320px]">
    <h3 class="mb-2 truncate font-['Orbitron'] text-lg font-black text-[#F2FEFE]"><?= $projeto->getNome() ?></h3>
    <p class="mb-4 line-clamp-3 text-sm text-[#91B5BD]"><?= $projeto->getDescricao() ?></p>
    <p class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">Criado em <?= $projeto->getCriadoEm()->format("d/m/Y");?></p>
</a>
<?php endif;?>
