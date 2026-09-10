<?php
$titulo = "Perfil do projeto";
if(isset($projeto)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <?php include_once(__DIR__."/elements/card.php")?>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
