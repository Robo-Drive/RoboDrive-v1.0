<?php
$titulo = "Fórum - Administração";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    
    <main class="rd-content rd-scroll-hidden p-4 sm:p-8 overflow-y-auto w-full">
        <div class="max-w-5xl mx-auto space-y-6">

            <!-- CABEÇALHO -->
            <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b-2 border-[#07556A] pb-4">
                <div>
                    <p class="rd-eyebrow">ADMINISTRAÇÃO</p>
                    <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">TODAS AS <span>POSTAGENS</span></h1>
                    <p class="text-xs text-[#91B5BD] mt-1">Postagens ativas e desativadas do fórum</p>
                </div>
            </section>

            <!-- FILTRO DE STATUS E BUSCA POR CONTEÚDO -->
            <form data-busca method="get" action="<?= URL_BASE ?>/admin/forum/listar" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <label for="status" class="text-xs text-[#91B5BD] uppercase font-bold">Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="rd-select !h-9 text-xs sm:!w-44 sm:shrink-0 [color-scheme:dark] [&>option]:bg-[#000505] [&>option]:text-[#F2FEFE] !border-[#0795A5] focus-visible:ring-2 focus-visible:ring-[#54FBFE] focus-visible:ring-offset-2 focus-visible:ring-offset-[#000505]">
                    <option value="1" <?= ($statusFiltro ?? '1') === '1' ? 'selected' : '' ?>>Ativos</option>
                    <option value="0" <?= ($statusFiltro ?? '1') === '0' ? 'selected' : '' ?>>Desativados</option>
                    <option value="todos" <?= ($statusFiltro ?? '1') === 'todos' ? 'selected' : '' ?>>Todos</option>
                </select>
                <input
                    type="text"
                    name="busca"
                    value="<?= htmlspecialchars($busca_atual ?? '') ?>"
                    placeholder="Pesquisar postagens pelo conteúdo..."
                    class="rd-input w-full flex-1"
                    autocomplete="off"
                >
                <button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Buscar</button>
            </form>

            <!-- FEED DE POSTAGENS (ESTILO LINHA / LISTA DA EQUIPE) -->
            <section data-resultados class="space-y-4">
                <?php if(isset($foruns) && count($foruns) > 0): ?>
                    <?php foreach($foruns as $forum): ?>
                        <?php include(__DIR__."/elements/card.php") ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-8 text-center text-[#91B5BD] text-xs">
                        <?= !empty($busca_atual) ? "Nenhuma postagem encontrada para a busca." : "Nenhuma postagem encontrada." ?>
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </main>
</div>
<?php
include_once(__DIR__."/../elements/adminModais.php");
include_once(__DIR__."/../elements/denunciaModal.php");
?>
<script src="<?= js_url("busca.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
?>