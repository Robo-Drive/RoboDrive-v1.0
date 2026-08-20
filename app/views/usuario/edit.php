<?php
$titulo = "Edição de Usuários";

if(isset($usuario)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="col-span-10 row-span-12 flex flex-col items-center justify-center place-items-center bg-cover bg-center bg-no-repeat text-white" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <div class="flex flex-col items-center bg-black/80 w-full h-full border border-white gap-[50px]">
            <div class="flex gap-1 font-bold font-['Orbitron']">
                <h1 class="text-3xl font-bold text-[#00F5F5]">EDITAR</h1>
                <h1 class="text-3xl font-bold text-white">PERFIL</h1>
            </div>
                <form action="<?= URL_BASE ?>/usuario/atualizar" method="post" enctype="multipart/form-data">
                    <?php include_once(__DIR__."/elements/form.php") ?>
                </form>
            </div>
            <form action="<?= URL_BASE ?>/usuario/excluir" method="post"
                class="absolute right-5 bottom-5 text-white border px-4 py-1 hover:border-[#FF0000]">
                <button type="submit">Excluir usuario</button>
                <input type="hidden" name="id" value="<?= is_object($usuario) ? $usuario->getId() : ($usuario['id'] ?? '') ?>">
            </form>
        </div>
    </div>
</div>

<script src="<?= JS_URL_BASE ?>/password.js"></script>
<script src="<?= JS_URL_BASE ?>/images.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
