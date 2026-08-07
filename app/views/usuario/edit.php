<?php
$titulo = "Edição de Usuários";
$header = "Edição do usuário";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="col-span-10 row-span-12 flex flex-col items-center justify-center place-items-center bg-cover bg-center bg-no-repeat text-white" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <div class="flex flex-col items-center bg-black/80 w-full h-full border border-white gap-[50px]">
            <div class="flex gap-1 font-bold font-['Orbitron']">
                <h1 class="text-3xl font-bold text-[#FF1A1A]">EDITAR</h1>
                <h1 class="text-3xl font-bold text-white">PERFIL</h1>
            </div>
                <form action="<?= URL_BASE ?>/usuario/atualizar>" method="post">
                    <?php include_once(__DIR__."/elements/form.php") ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= JS_URL_BASE ?>/password.js"></script>
<script src="<?= JS_URL_BASE ?>/images.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
