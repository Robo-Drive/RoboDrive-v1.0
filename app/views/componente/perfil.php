<?php
$titulo = "Perfil do componente";
if(isset($componente)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12 bg-black">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="col-span-10 row-span-12 flex flex-col items-center justify-center place-items-center bg-cover bg-center bg-no-repeat text-white overflow-y-auto xz" style="background-image: url('<?= IMG_URL_BASE ?>/robodrive-fundo.png');">
        <form action="<?= URL_BASE ?>/componente/editar" method="post" class="absolute right-5 top-20 text-white border px-4 py-1 hover:border-[#00F5F5]">
            <button type="submit">Editar componente</button>
            <input type="hidden" name="id" value="<?= $componente->getId() ?>">
        </form>
        <form action="<?= URL_BASE ?>/componente/excluir" method="post"
            class="absolute right-5 bottom-5 text-white border px-4 py-1 hover:border-[#FF0000]">
            <button type="submit">Excluir componente</button>
            <input type="hidden" name="id" value="<?= $componente->getId() ?>">
        </form>
        <?php include_once(__DIR__."/elements/card.php")?>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;