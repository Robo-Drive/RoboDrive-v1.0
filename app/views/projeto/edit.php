<?php
$titulo = "Edição de Projetos";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden relative z-10">

        <div class="mb-8 text-center">
            <p class="rd-eyebrow">PROJETOS</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EDITAR <span>PROJETO</span></h1>
        </div>

        <form action="<?= URL_BASE?>/projeto/atualizar" autocomplete="off" method="post" enctype="multipart/form-data" class="rd-form-card rd-form-card-wide w-full">
            <?php include_once(__DIR__."/elements/form.php")?>
        </form>

    </div>
</div>

<script src="<?= js_url("password.js") ?>"></script>
<script src="<?= js_url("categoria.js") ?>"></script>
<script src="<?= js_url("componente.js") ?>"></script>
<script src="<?= js_url("inputFiles.js") ?>"></script>
<?php include_once(__DIR__."/../elements/modalRecorte.php") ?>
<script src="<?= js_url("images.js") ?>"></script>
<script src="<?= js_url("cropper.min.js") ?>"></script>
<script src="<?= js_url("cropperImagem.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");