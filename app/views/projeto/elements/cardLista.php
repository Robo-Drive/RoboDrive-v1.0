<?php if(isset($projeto)):?>
<div class="relative w-[500px] h-[200px] border hover:border-[#00F5F5] px-4">
    <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>" class="absolute inset-0" aria-label="Ver projeto <?= $projeto->getNome() ?>"></a>
    <h1 class="p-4 px-12 font-bold text-2xl text-center"><?= $projeto->getNome() ?></h1>
    <hr>
    <p><?= $projeto->getDescricao() ?></p>
    <p class="text-zinc-500">Criado em:<?= $projeto->getCriadoEm()->format("d/m/Y");?></p>
    <?php
    $denunciaAlvo = [
        "tipo" => "projeto",
        "id" => $projeto->getId(),
        "nome" => $projeto->getNome(),
        "classe" => "absolute right-1.5 top-1.5 z-10"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif;?>
