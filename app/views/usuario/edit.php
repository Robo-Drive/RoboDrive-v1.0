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

<div id="modalRecorte" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#000505]/85 p-4">
    <div id="dialogRecorte" role="dialog" aria-modal="true" aria-labelledby="tituloRecorte" tabindex="-1" class="flex max-h-full w-full max-w-lg flex-col gap-4 overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-4 focus:outline-none sm:p-6 [&_.cropper-view-box]:outline-[#13F3F7] [&_.cropper-point]:bg-[#13F3F7] [&_.cropper-line]:bg-[#13F3F7]">
        <h2 id="tituloRecorte" class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">Recortar foto de perfil</h2>
        <div class="h-72 w-full overflow-hidden bg-[#000505] sm:h-96">
            <img id="imgRecorte" alt="Pré-visualização do recorte" class="block max-w-full">
        </div>
        <input id="zoomRecorte" type="range" aria-label="Zoom" min="0" max="1" step="0.01" value="0" class="h-11 w-full accent-[#13F3F7] focus-visible:outline-2 focus-visible:outline-[#54FBFE]">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" id="btnDescartarRecorte" class="rd-btn rd-btn-ghost">Descartar</button>
            <button type="button" id="btnUsarRecorte" class="rd-btn rd-btn-primary">Usar foto</button>
        </div>
    </div>
</div>

<script src="<?= js_url("password.js") ?>"></script>
<script src="<?= js_url("images.js") ?>"></script>
<script src="<?= js_url("cropper.min.js") ?>"></script>
<script src="<?= js_url("cropperPerfil.js") ?>"></script>
<script src="<?= js_url("contadorBiografia.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
