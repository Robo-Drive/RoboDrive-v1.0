<?php
$titulo = "Equipes - Administração";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="main usa overflow-y-auto col-span-12 lg:col-span-10 row-span-12 bg-black">
        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">ADMINISTRAÇÃO</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EQUIPES</h1>
            </div>
            <div class="flex items-center gap-3">
                <form method="get" action="<?= URL_BASE ?>/admin/equipe/listar" class="flex items-center gap-2">
                    <input type="hidden" name="busca" value="<?= htmlspecialchars($busca_atual ?? '') ?>">
                    <label for="status" class="text-xs text-[#91B5BD] uppercase font-bold">Status:</label>
                    <select name="status" id="status" onchange="this.form.submit()" class="rd-select !h-9 text-xs">
                        <option value="1" <?= ($statusFiltro ?? '1') === '1' ? 'selected' : '' ?>>Ativos</option>
                        <option value="0" <?= ($statusFiltro ?? '1') === '0' ? 'selected' : '' ?>>Desativados</option>
                        <option value="todos" <?= ($statusFiltro ?? '1') === 'todos' ? 'selected' : '' ?>>Todos</option>
                    </select>
                </form>
                <a href="<?= URL_BASE ?>/equipe/cadastro" class="rd-btn rd-btn-primary w-fit">Adicionar equipe</a>
            </div>
        </section>

        <!-- PESQUISA -->
        <section class="px-5 pb-3">
            <form data-busca method="get" action="<?= URL_BASE ?>/admin/equipe/listar" class="flex gap-3">
                <input type="hidden" name="status" value="<?= htmlspecialchars($statusFiltro ?? '1') ?>">
                <div class="flex-1">
                    <input
                        type="text"
                        name="busca"
                        value="<?= htmlspecialchars($busca_atual ?? '') ?>"
                        placeholder="Pesquisar equipes pelo nome..."
                        class="rd-input w-full"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">Buscar</button>
            </form>
        </section>

        <section data-resultados class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-4">
            <?php if(isset($equipes) && count($equipes) > 0):?>
                <?php foreach($equipes as $equipe):?>
                    <?php include(__DIR__."/elements/cardLista.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty w-full">Nenhuma equipe encontrada.</p>
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
