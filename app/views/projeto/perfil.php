<?php
$titulo = "Perfil do projeto";
if(isset($projeto)):
$header = "Projeto";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12 bg-black">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <?php include_once(__DIR__."/elements/card.php")?>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;