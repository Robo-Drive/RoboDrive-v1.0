<?php
$titulo = "Listagem de Componentes";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">BIBLIOTECA</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">COMPONENTES</h1>
            </div>
            <a href="<?= URL_BASE ?>/componente/cadastro" class="rd-btn rd-btn-primary w-fit">Adicionar componente</a>
        </section>

        <!-- BUSCA POR NOME -->
        <form data-busca method="get" action="<?= URL_BASE ?>/componente/listar" class="flex gap-3 px-5 pb-3">
            <input
                type="text"
                name="busca"
                value="<?= htmlspecialchars($busca_atual ?? '') ?>"
                placeholder="Pesquisar componentes pelo nome..."
                class="rd-input w-full flex-1"
                autocomplete="off"
            >
            <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">Buscar</button>
        </form>

        <section data-resultados class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-4">
            <?php if(isset($componentes) && count($componentes) > 0):?>
                <?php foreach($componentes as $componente):?>
                    <?php include(__DIR__."/elements/card.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty w-full"><?= !empty($busca_atual) ? "Nenhum componente encontrado." : "Nenhum componente cadastrado ainda" ?></p>
            <?php endif; ?>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/denunciaModal.php");
?>
<script src="<?= js_url("busca.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
