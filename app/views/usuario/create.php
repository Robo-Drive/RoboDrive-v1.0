<?php
$titulo = "Cadastro de Usuários";
$header = "Cadastro de usuários";
$menu = [
    [
        "rota" => URL_BASE."/usuario/listar",
        "nome" => "Lista"
    ]
];
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <form action="<?= URL_BASE?>/usuario/salvar" method="post" class="bg-gray-800 px-5 py-7 rounded-3xl">
        <?php include_once(__DIR__."/elements/form.php")?>
    </form>
</div>
<?php
$marquee = "Cadastro de usuários do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");