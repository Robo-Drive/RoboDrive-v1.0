<?php
$titulo = "Edição de Usuários";

if(isset($usuario)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden">

        <div class="mb-8 text-center">
            <p class="rd-eyebrow">CONFIGURAÇÕES</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EDITAR <span>PERFIL</span></h1>
        </div>

        <form action="<?= URL_BASE ?>/usuario/atualizar" autocomplete="off" method="post" enctype="multipart/form-data" class="rd-form-card rd-form-card-wide w-full">
            <?php include_once(__DIR__."/elements/form.php") ?>
        </form>

        <form action="<?= URL_BASE ?>/usuario/desativar" autocomplete="off" method="post" class="mt-6">
            <input type="hidden" name="id" value="<?= is_object($usuario) ? $usuario->getId() : ($usuario['id'] ?? '') ?>">
            <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">desativar conta</button>
        </form>

    </div>
</div>

<?php include_once(__DIR__."/../elements/modalRecorte.php") ?>

<script src="<?= js_url("password.js") ?>"></script>
<script src="<?= js_url("images.js") ?>"></script>
<script src="<?= js_url("cropper.min.js") ?>"></script>
<script src="<?= js_url("cropperImagem.js") ?>"></script>
<script src="<?= js_url("contadorBiografia.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
