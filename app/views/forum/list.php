<?php
$titulo = "Forum";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <div class="col-span-10 row-span-12 flex flex-col items-center  place-items-center bg-cover bg-center bg-no-repeat text-white" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <div class="p-4 flex items-center justify-center">
            <h1 class="text-3xl text-white">Fórum</h1>
            </div>
        <hr>
        <div class="w-full flex flex-col items-center gap-4 overflow-y-auto">
            <?php if(isset($foruns)):?>
                <?php foreach($foruns as $forum):?>
                    <?php include(__DIR__."/elements/card.php")?>
                <?php endforeach;?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");