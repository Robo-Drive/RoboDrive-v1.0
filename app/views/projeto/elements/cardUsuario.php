<?php if(isset($usuario)):?>
    <a href="<?= URL_BASE ?>/usuario/perfil?id=<?= $usuario->getId() ?>" class="border border-[#13F3F7] p-4 w-[400px]">
        <h1><?= texto($usuario->getNome()) ?></h1>
        <p class="text-[#91B5BD]"><?= $usuario->getPapelProjeto() ?></p>
    </a>
<?php endif;?>