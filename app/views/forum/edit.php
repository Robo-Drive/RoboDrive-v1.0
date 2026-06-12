<?php
$titulo = "Edição de Foruns";
$header = "Edição do forum";
$menu = [
    [
        "rota" => URL_BASE."/forum/listar",
        "nome" => "Lista"
    ]
];
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <form action="<?= URL_BASE?>/forum/atualizar" method="post" class="bg-gray-800 px-5 py-7 rounded-3xl">
        <?php include_once(__DIR__."/elements/form.php")?>
    </form>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");