<?php
$titulo = "Projetos públicos";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="main usa overflow-y-auto col-span-10 row-span-12 bg-black">
        <div class="p-5 text-2xl font-bold w-full text-center text-white">
            <h1>Projetos</h1>
        </div>
        <div class="p-5 w-full text-white border border-zinc-700 flex flex-wrap gap-2">
            <?php if(isset($projetos)):?>
                <?php foreach($projetos as $projeto):?>
                    <?php include(__DIR__."/elements/cardLista.php")?>
                <?php endforeach;?>
            <?php endif;?>
        </div>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
