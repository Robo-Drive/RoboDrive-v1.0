<?php if(isset($componente)):?>
<div class="border p-4 w-[500px] h-[400px] gap-3">
    <h1 class="text-white font-bold text-center">
        <?= $componente->getNome()?>
    </h1>
    <hr>
    <a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="flex justify-between p-2 text-white">
        <p><?= $componente->getDescricao() ?></p>
        <img src="<?= $componente->getImagem() ?>" alt="Imagem do <?= $componente->getNome() ?>" width="250px">
    </a>
</div>
<?php endif;?>