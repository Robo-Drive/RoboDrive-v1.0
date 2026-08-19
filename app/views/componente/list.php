<?php
$titulo = "Listagem de Componentes";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <div class="col-span-10 row-span-12 flex flex-col items-center  place-items-center bg-cover bg-center bg-no-repeat text-white" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <div class="p-4 flex items-center justify-center">
            <h1 class="text-3xl text-white">Componentes</h1>
            <a href="<?= URL_BASE ?>/componente/cadastro" class="absolute right-5 top-1/8 -translate-y-1/2 text-white border px-4 py-1 hover:border-[#00F5F5]">
                Adicionar componente
            </a>
        </div>
        <hr>
        <div class="flex gap-2 p-4 overflow-y-auto flex-wrap">
            <?php if(isset($componentes)):?>
                <?php foreach($componentes as $componente):?>
                    <?php include(__DIR__."/elements/card.php")?>
                <?php endforeach;?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
