<?php if(isset($componente)):?>
<div class="border p-4 w-[500px] h-[300px] gap-3 bg-black/70">
    <h1 class="text-white font-bold text-center">
        <?= $componente->getNome()?>
    </h1>
    <hr>
    <a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="flex justify-between p-2 text-white">
        <p><?= $componente->getDescricao() ?></p>
        <img src="<?= URL_BASE."/arquivo?arquivo=".$componente->getImagem() ?>" alt="<?= $componente->getNome() ?>" class="w-32 h-32 object-cover rounded-lg">

    </a>
    <p class="text-zinc-400">Cadastrado por: <?= $componente->getUsuario()->getNomeUsuario() ?></p>
</div>
<?php endif;?>