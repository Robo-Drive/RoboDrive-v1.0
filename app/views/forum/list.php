<?php
$titulo = "Fórum";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    
    <main class="rd-content rd-scroll-hidden p-4 sm:p-8 overflow-y-auto w-full">
        <div class="max-w-5xl mx-auto space-y-6">

            <!-- CABEÇALHO -->
            <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b-2 border-[#07556A] pb-4">
                <div>
                    <p class="rd-eyebrow">COMUNIDADE</p>
                    <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">FÓRUM <span>GERAL</span></h1>
                    <p class="text-xs text-[#91B5BD] mt-1">Discussões, dúvidas e projetos da comunidade RoboDrive</p>
                </div>
                <a href="<?= URL_BASE ?>/forum/cadastro" class="rd-btn rd-btn-primary w-fit">
                    + Nova publicação
                </a>
            </section>

            <!-- FEED DE POSTAGENS (ESTILO LINHA / LISTA DA EQUIPE) -->
            <section class="space-y-4">
                <?php if(isset($foruns) && count($foruns) > 0): ?>
                    <?php foreach($foruns as $forum): ?>
                        <?php include(__DIR__."/elements/card.php") ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">
                        Nenhuma postagem realizada no fórum até o momento.
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </main>
</div>
<?php
include_once(__DIR__."/../elements/adminModais.php");
include_once(__DIR__."/../elements/denunciaModal.php");
include_once(__DIR__."/../elements/footer.php");
?>