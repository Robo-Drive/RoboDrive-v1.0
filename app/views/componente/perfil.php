<?php
$titulo = "Perfil do componente";
if(isset($componente)):
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content-centered rd-scroll-hidden">
        <div class="-mx-6 flex w-[calc(100%+3rem)] flex-col items-center justify-center gap-5 p-5">
            <div class="flex w-full flex-col items-center justify-center text-center mb-4">
                <p class="rd-eyebrow">O Que Deseja Fazer com este</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">COMPONENTE?</h1>
            </div>

            <div class="flex w-full justify-center">
                <div class="w-full md:w-[calc(50%-0.625rem)] lg:w-[calc(25%-0.9375rem)]">
                    <?php include_once(__DIR__ . "/elements/card.php")?>
                </div>
            </div>

            <div class="flex h-auto w-auto flex-wrap justify-center gap-4">
                <form action="<?= URL_BASE ?>/componente/editar" method="post">
                    <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                    <button type="submit" class="rd-btn rd-btn-secondary">Editar componente</button>
                </form>
                <form action="<?= URL_BASE ?>/componente/excluir" method="post">
                    <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                    <button type="submit" class="rd-btn rd-btn-danger">Excluir componente</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
