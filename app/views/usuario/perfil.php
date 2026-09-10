<?php
$titulo = "RoboDrive/".$_SESSION["usuario_logado"]->getNome();
if(isset($usuario)):
$header = "RoboDrive";

include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>

    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section">
            <p class="rd-eyebrow">PAINEL</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,3rem)]">MEU <span>PERFIL</span></h1>
            <div class="mt-8">
                <?php include_once(__DIR__."/elements/card.php")?>
            </div>
        </section>

        <section class="rd-section">
            <p class="rd-eyebrow">COLABORAÇÃO</p>
            <h2 class="rd-heading text-[clamp(1.5rem,3.5vw,2.5rem)]">MINHAS <span>EQUIPES</span></h2>
            <div class="mt-8">
                <?php include(__DIR__."/elements/cardEquipe.php")?>
            </div>
        </section>

        <section class="rd-section">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="rd-eyebrow">TRABALHOS</p>
                    <h2 class="rd-heading text-[clamp(1.5rem,3.5vw,2.5rem)]">MEUS <span>PROJETOS</span></h2>
                </div>
                <a href="<?= URL_BASE ?>/projeto/cadastro" class="rd-btn rd-btn-primary w-fit">
                    Adicionar projeto
                </a>
            </div>

            <div class="flex flex-wrap gap-4 sm:gap-6">
                <?php if(isset($projetos) && count($projetos) > 0):?>
                    <?php foreach($projetos as $projeto):?>
                        <?php include(__DIR__."/elements/cardProjeto.php")?>
                    <?php endforeach;?>
                <?php else: ?>
                    <p class="rd-empty">Nenhum projeto cadastrado ainda</p>
                <?php endif;?>
            </div>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
endif;
