<?php
$titulo = "Forum";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">COMUNIDADE</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">FÓRUM <span>ROBODRIVE</span></h1>
            </div>
            <a href="<?= URL_BASE ?>/forum/cadastro" class="rd-btn rd-btn-primary w-fit">Nova postagem</a>
        </section>

        <section class="rd-section flex flex-col gap-4">
            <?php if(isset($foruns) && count($foruns) > 0):?>
                <?php foreach($foruns as $forum):?>
                    <?php include(__DIR__."/elements/card.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty">Nenhuma postagem por aqui ainda. Seja o primeiro a compartilhar algo!</p>
            <?php endif; ?>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
