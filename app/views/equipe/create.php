
<?php
$titulo = "Criar equipe";

include_once(__DIR__ . "/../elements/header.php");
?>

<div class="rd-shell">

    <?php include_once(__DIR__ . "/../elements/sidebar.php"); ?>

    <div class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden">

        <!-- Cabeçalho -->
        <div class="mb-8 text-center">
            <p class="rd-eyebrow">EQUIPES</p>

            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">
                CADASTRAR <span>EQUIPE</span>
            </h1>
        </div>

        <!-- Formulário -->
        <form
            action="<?= URL_BASE ?>/equipe/salvar"
            method="POST"
            autocomplete="off"
            enctype="multipart/form-data"
            class="rd-form-card rd-form-card-wide w-full"
        >

            <?php include_once(__DIR__ . "/elements/form.php"); ?>

        </form>

    </div>

</div>

<!-- Scripts -->
<script src="<?= JS_URL_BASE ?>/equipeFormAjax.js"></script>
<script src="<?= JS_URL_BASE ?>/password.js"></script>

<?php
include_once(__DIR__ . "/../elements/footer.php");
?>
