<?php if(isset($usuario)):?>
    <a href="<?= URL_BASE ?>/usuario/perfil?id=<?= $usuario->getId() ?>" class="border border-[#FF1A1A] p-4 w-[400px]">
        <h1><?= $usuario->getNome() ?></h1>
        <p class="text-zinc-500"><?= $usuario->getTipo() ?></p>
    </a>
<?php endif;?>