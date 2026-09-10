<?php
$titulo = "Edição de Projetos";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered rd-scroll-hidden">
        <div class="mb-8 text-center">
            <p class="rd-eyebrow">PROJETOS</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EDITAR <span>PROJETO</span></h1>
        </div>
        <form action="<?= URL_BASE?>/projeto/atualizar" method="post" class="rd-form-card rd-form-card-wide">
            <?php include_once(__DIR__."/elements/form.php")?>
        </form>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
