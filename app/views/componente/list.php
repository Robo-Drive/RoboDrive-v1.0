<?php
$titulo = "Listagem de Componentes";
$header = "Componentes";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <div class="col-span-10 row-span-12 overflow-y-auto bg-black">
        <h1 class="text-center font-bold text-white text-2xl">Componentes</h1>
        <hr>
        <div class="flex gap-2 p-4">
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