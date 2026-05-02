<?php
$titulo = "Perfil do projeto";
if(isset($projeto)):
$header = "Projeto";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <?php include_once(__DIR__."/elements/card.php")?>
</div>
<?php
$marquee = "{$projeto->getNome()}";
include_once(__DIR__."/../elements/footer.php");
endif;