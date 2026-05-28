<?php
$titulo = "Perfil da equipe";
if(isset($equipe)):
$header = "Equipe";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <?php include_once(__DIR__."/elements/card.php")?>
</div>
<?php
$marquee = "{$equipe->getNome()}";
include_once(__DIR__."/../elements/footer.php");
endif;