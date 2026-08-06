<?php
$titulo = "Criar postagem";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="col-span-10 row-span-12 flex flex-col items-center justify-center place-items-center bg-cover bg-center bg-no-repeat text-white" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <div class="flex flex-col items-center bg-black/80 px-8 py-7 border border-white gap-[50px]">
            <div class="flex gap-1 font-bold font-['Orbitron']">
                <h1 class="text-3xl font-bold text-[#FF1A1A]">CRIAR</h1>
                <h1 class="text-3xl font-bold text-white">POSTAGEM</h1>
            </div>
            <div class="flex flex-col items-center">
                <form action="<?= URL_BASE?>/projeto/salvar" method="post"">
                    <?php include_once(__DIR__."/elements/form.php")?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once(__DIR__."/../elements/footer.php");
