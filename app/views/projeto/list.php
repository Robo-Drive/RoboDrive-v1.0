<?php
$titulo = "Projetos públicos";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section">
            <p class="rd-eyebrow">COMUNIDADE ROBODRIVE</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">PROJETOS <span>PÚBLICOS</span></h1>
        </section>

        <section class="rd-section flex flex-wrap gap-6">
            <?php if(isset($projetos) && count($projetos) > 0):?>
                <?php foreach($projetos as $projeto):?>
                    <?php include(__DIR__."/elements/cardLista.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty w-full">Nenhum projeto público disponível no momento</p>
            <?php endif;?>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
