<?php
$titulo = "RoboDrive/".$_SESSION["usuario_logado"]->getNome();
if(isset($usuario)):
$header = "RoboDrive";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="main usa overflow-y-auto col-span-10 row-span-12 bg-black">
        <div class="col-span-10 row-span-5 flex flex-col items-center border border-[#00F5F5]">
            <div class="p-5 text-2xl font-bold w-full text-center text-white">
                <h1>Perfil</h1>
            </div>
            <?php include_once(__DIR__."/elements/card.php")?>
            
            
        </div>
        <div class="col-span-10 row-span-2 flex flex-col items-center border border-[#00F5F5] ">
            <div class="p-5 text-2xl font-bold w-full text-center text-white">
                <h1>Equipes</h1>
            </div>
            
            <?php include(__DIR__."/elements/cardEquipe.php")?>
                
        </div>
        <div class="col-span-10 row-span-2 flex flex-col items-center">
            <div class="relative p-5  w-full text-center text-white">
                <h1 class="text-2xl font-bold">Projetos</h1>

                <a href="<?= URL_BASE ?>/projeto/cadastro" class="absolute right-5 top-1/2 -translate-y-1/2 text-white border px-4 py-1 hover:border-[#00F5F5]">
                    Adicionar projeto
                </a>
            </div>
            <div class="p-5 w-full text-white border border-zinc-700 flex flex-wrap gap-2">
                <?php if(isset($projetos)):?>
                    <?php foreach($projetos as $projeto):?>
                        <?php include(__DIR__."/elements/cardProjeto.php")?>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
                
        </div>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;