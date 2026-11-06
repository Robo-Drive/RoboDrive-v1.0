<?php
$titulo = "Projetos públicos";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="main usa overflow-y-auto col-span-12 lg:col-span-10 row-span-12 bg-black">
        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">BIBLIOTECA</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">PROJETOS PÚBLICOS</h1>
            </div>
            <a href="<?= URL_BASE ?>/projeto/cadastro" class="rd-btn rd-btn-primary w-fit">Adicionar projeto</a>
        </section>

        <section class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-4">
            <?php if(isset($projetos) && count($projetos) > 0):?>
                <?php foreach($projetos as $projeto):?>
                    <?php include(__DIR__."/elements/cardLista.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty w-full">Nenhum projeto cadastrado ainda</p>
            <?php endif; ?>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/denunciaModal.php");
include_once(__DIR__."/../elements/footer.php");
