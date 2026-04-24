<?php
$titulo = "Login";
$header = "Faça seu login";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <form action="<?= URL_BASE ?>/logar" method="post" class="bg-gray-800 px-5 py-7 rounded-3xl">
        <?php include_once(__DIR__."/elements/form.php") ?>    
    </form>
    <?php if(isset($erros)):?>
        <p><?= $erros ?></p>
    <?php endif;?>
</div>
<?php
$marquee = "Página de login do Robo Drive";
include_once(__DIR__."/../elements/footer.php");
