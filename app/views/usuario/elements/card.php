<?php if(isset($usuario)):?>
<div class="shadow-2xl grid grid-cols-12 grid-rows-12 h-full p-8 w-full border border-zinc-700">
    <div class="<?= $usuario->getBiografia() != null ? "col-span-3" : "col-span-12" ?> row-span-12 flex flex-col items-center gap-3">
        <img 
            src="<?= $usuario->getImagem() ? URL_BASE."/arquivo?arquivo=".$usuario->getImagem() : IMG_URL_BASE."/perfil.png" ?>" 
            alt="Foto de perfil"
            class="w-32 h-32 object-contain shadow-lg"
        >
        <h1 class="font-bold text-white text-3xl"><?= $usuario->getNomeUsuario() ?></h1>
        <div>
            <h1 class="text-white">Nome: <?= $usuario->getNome() ?></h1>
            <h1 class="text-white">Email: <?= $usuario->getEmail() ?></h1>
        </div>
    </div>
    <?php if($usuario->getBiografia() != null):?>
        <div class="col-span-6 row-span-12">
            <p class="text-white text-justify"><b class="text-white">Bio: </b><?= $usuario->getBiografia() ?></p>
        </div>
    <?php endif;?>
</div>
<?php endif;?>src="<?= (URL_BASE."/arquivo?arquivo=".$usuario->getImagem()) ?? (IMG_URL_BASE."/perfil.png") ?>"