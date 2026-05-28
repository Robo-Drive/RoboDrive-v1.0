<?php
$titulo = "Cadastro";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full flex flex-col items-center justify-center place-items-center bg-cover bg-center bg-no-repeat text-white"style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
    <div class="flex flex-col items-center bg-black/80 px-8 py-7 border border-white gap-[50px]">
        <div class="flex gap-1 font-bold font-['Orbitron']">
            <h1 class="text-3xl font-bold text-[#FF1A1A]">FAZER</h1>
            <h1 class="text-3xl font-bold text-white">CADASTRO</h1>
        </div>
        <div class="flex flex-col items-center">
            <form action="<?= URL_BASE ?>/usuario/salvar" method="post" >
                <?php include_once(__DIR__."/elements/form.php") ?>
            </form>
    
            <a href="<?= URL_BASE."/login" ?>" class="mt-4 text-white hover:text-[#FF1A1A] transition">
                Entre
            </a>
        </div>
    </div>
</div>

<script src="<?= JS_URL_BASE ?>/script.js"></script><?php
include_once(__DIR__."/../elements/footer.php");
