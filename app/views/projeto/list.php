<?php
$titulo = "Projetos públicos";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="main usa overflow-y-auto col-span-12 lg:col-span-10 row-span-12 bg-[#000505]">
        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">BIBLIOTECA</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]"><?= (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/admin')) ? 'TODOS OS PROJETOS' : 'PROJETOS PÚBLICOS' ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <?php if (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/admin')): ?>
                    <form method="get" action="<?= URL_BASE ?>/projeto/listar/admin" class="flex items-center gap-2">
                        <label for="status" class="text-xs text-[#91B5BD] uppercase font-bold">Status:</label>
                        <select name="status" id="status" onchange="this.form.submit()" class="rd-select !h-9 text-xs">
                            <option value="1" <?= ($statusFiltro ?? '1') === '1' ? 'selected' : '' ?>>Ativos</option>
                            <option value="0" <?= ($statusFiltro ?? '1') === '0' ? 'selected' : '' ?>>Desativados</option>
                            <option value="todos" <?= ($statusFiltro ?? '1') === 'todos' ? 'selected' : '' ?>>Todos</option>
                        </select>
                    </form>
                <?php endif; ?>
                <?php if(isset($_SESSION["usuario_logado"])): ?>
                    <a href="<?= URL_BASE ?>/projeto/cadastro" class="rd-btn rd-btn-primary w-fit">Adicionar projeto</a>
                <?php else: ?>
                    <a href="<?= URL_BASE ?>/login" class="rd-btn rd-btn-primary w-fit">Entrar para criar projeto</a>
                <?php endif; ?>
            </div>
        </section>

        <!-- FILTRO DE BUSCA RÁPIDA -->
        <section class="px-5 pb-3">
            <form id="formFiltroProjetos" action="<?= URL_BASE ?>/projeto/listar" method="get" class="flex gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="inputBusca"
                        name="busca" 
                        value="<?= htmlspecialchars($busca_atual ?? '') ?>" 
                        placeholder="Pesquisar projetos por nome ou descrição..." 
                        class="rd-input w-full"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">
                    Buscar
                </button>
            </form>
        </section>

        <!-- CONTAINER DOS CARDS -->
        <section id="gridProjetos" class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 lg:grid-cols-4">
            <?php if(isset($projetos) && count($projetos) > 0):?>
                <?php foreach($projetos as $projeto):?>
                    <?php include(__DIR__."/elements/cardLista.php")?>
                <?php endforeach;?>
            <?php else: ?>
                <p class="rd-empty w-full col-span-full">Nenhum projeto encontrado</p>
            <?php endif; ?>
        </section>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBusca = document.getElementById("inputBusca");
    const gridProjetos = document.getElementById("gridProjetos");
    let timeoutId = null;

    function executarBuscaAjax() {
        const busca = inputBusca.value.trim();
        const params = new URLSearchParams();
        if (busca) params.append("busca", busca);
        params.append("ajax", "1");

        fetch("<?= URL_BASE ?>/projeto/listar?" + params.toString(), {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            gridProjetos.innerHTML = html;
        });
    }

    inputBusca.addEventListener("input", function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(executarBuscaAjax, 300);
    });

    document.getElementById("formFiltroProjetos").addEventListener("submit", function(e) {
        e.preventDefault();
        executarBuscaAjax();
    });
});
</script>

<?php
include_once(__DIR__."/../elements/denunciaModal.php");
include_once(__DIR__."/../elements/footer.php");
?>