<?php
$titulo = "Edição de Componentes";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered rd-scroll-hidden">
        <div class="mb-8 text-center">
            <p class="rd-eyebrow">COMPONENTES</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EDITAR <span>COMPONENTE</span></h1>
        </div>
        <form action="<?= URL_BASE?>/componente/atualizar" method="post" enctype="multipart/form-data" class="rd-form-card">
            <?php include_once(__DIR__."/elements/form.php")?>
        </form>
    </div>
</div>

<script src="<?= JS_URL_BASE ?>/images.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
