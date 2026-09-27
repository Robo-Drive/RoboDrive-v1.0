<?php if(isset($projeto)):?>
<div class="rd-card rd-card-hover w-full sm:w-[300px]">
    <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>" class="absolute inset-0" aria-label="Ver projeto <?= $projeto->getNome() ?>"></a>
    <h3 class="mb-2 truncate pr-8 font-['Orbitron'] text-lg font-black tracking-[-.01em] text-[#F2FEFE]"><?= $projeto->getNome() ?></h3>
    <p class="mb-4 line-clamp-2 text-sm text-[#91B5BD]"><?= $projeto->getDescricao() ?></p>
    <p class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">Criado em <?= $projeto->getCriadoEm()->format("d/m/Y");?></p>
    <?php
    $denunciaAlvo = [
        "tipo" => "projeto",
        "id" => $projeto->getId(),
        "nome" => $projeto->getNome(),
        "autores" => [$usuario->getId()],
        "classe" => "absolute right-1.5 top-1.5 z-10"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif;?>
