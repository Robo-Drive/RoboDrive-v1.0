<?php
$titulo = "Perfil da equipe";
if(isset($equipe)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered rd-scroll-hidden">
        <?php include_once(__DIR__."/elements/card.php")?>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
