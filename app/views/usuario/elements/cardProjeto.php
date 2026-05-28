<?php if(isset($projeto)):?>
<a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>">
    <div class="w-[500px] h-[200px] border hover:border-[#FF1A1A] px-4">
        <h1 class="p-4 font-bold text-2xl text-center"><?= $projeto->getNome() ?></h1>
        <hr>
        <p><?= $projeto->getDescricao() ?></p>
        <p class="text-zinc-500">Criado em:<?= $projeto->getCriadoEm()->format("d/m/Y");?></p>
</a>
</div>
<?php endif;?>