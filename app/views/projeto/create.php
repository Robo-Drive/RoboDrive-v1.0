<?php
$titulo = "Cadastro de Projetos";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered rd-scroll-hidden">
        <div class="mb-8 text-center">
            <p class="rd-eyebrow">PROJETOS</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">CADASTRAR <span>PROJETO</span></h1>
        </div>
        <form action="<?= URL_BASE?>/projeto/salvar" method="post" enctype="multipart/form-data" class="rd-form-card rd-form-card-wide">
            <?php include_once(__DIR__."/elements/form.php")?>
        </form>
    </div>
</div>

<script src="<?= JS_URL_BASE ?>/files.js"></script>
<script src="<?= JS_URL_BASE ?>/images.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
