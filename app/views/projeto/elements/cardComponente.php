<?php if(isset($componente)):?>
<a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="border p-4 w-[500px] gap-3">
    <h1 class="text-white font-bold text-center">
        <?= $componente->getNome()?>
    </h1>
    <hr>
    <div class="flex justify-between p-2">
        <div>
            <p><?= $componente->getDescricao() ?></p>
            <p>Quantidade: <?= $componente->getQuantidade() ?></p>
        </div>
        <img src="<?= $componente->getImagem() ?>" alt="Imagem do <?= $componente->getNome() ?>" width="250px">
    </div>
</a>
<?php endif;?>