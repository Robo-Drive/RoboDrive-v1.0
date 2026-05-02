<?php
$titulo = "Edição de Usuários";
$header = "Edição do usuário";
$menu = [
    [
        "rota" => URL_BASE."/usuario/listar",
        "nome" => "Lista"
    ]
];
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <form action="<?= URL_BASE?>/usuario/atualizar" method="post" class="bg-gray-800 px-5 py-7 rounded-3xl">
        <?php include_once(__DIR__."/elements/form.php")?>
    </form>
</div>
<?php
$marquee = "Edição de usuários do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");