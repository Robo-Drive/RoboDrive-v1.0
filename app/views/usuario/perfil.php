<?php
$titulo = "RoboDrive/".$_SESSION["usuario_logado"]->getNomeUsuario();
if(isset($usuario)):
$header = "RoboDrive";
$perfilProprio = $usuario->getId() == $_SESSION["usuario_logado"]->getId();

include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>

    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section">
            <p class="rd-eyebrow">PAINEL</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,3rem)]"><?= $perfilProprio ? "MEU" : "PERFIL DO" ?> <span><?= $perfilProprio ? "PERFIL" : "USUÁRIO" ?></span></h1>
            <div class="mt-8">
                <?php include_once(__DIR__."/elements/card.php")?>
            </div>
        </section>

        <section class="rd-section">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="rd-eyebrow">COLABORAÇÃO</p>
                    <h2 class="rd-heading text-[clamp(1.5rem,3.5vw,2.5rem)]"><?= $perfilProprio ? "MINHAS" : "" ?> <span>EQUIPES</span></h2>
                </div>
                <?php if($perfilProprio): ?>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="<?= URL_BASE ?>/equipe/cadastro" class="rd-btn rd-btn-primary w-fit">
                            Adicionar equipe
                        </a>
                        <a href="<?= URL_BASE ?>/equipe/entrar" class="rd-btn rd-btn-secondary w-fit">
                            Entrar em equipe
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PESQUISA DE EQUIPES NO PERFIL -->
            <div class="mb-6 flex gap-3">
                <div class="flex-1">
                    <input
                        type="text"
                        id="buscaEquipesPerfil"
                        value="<?= htmlspecialchars($busca_equipe_atual ?? '') ?>"
                        placeholder="Pesquisar equipes pelo nome..."
                        class="rd-input w-full"
                        autocomplete="off"
                    >
                </div>
            </div>

            <div id="gridEquipesPerfil" data-atualizar class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                <?php if(isset($equipes) && count($equipes) > 0):?>
                    <?php foreach($equipes as $equipe):?>
                        <?php include(__DIR__."/elements/cardEquipe.php")?>
                    <?php endforeach;?>
                <?php else: ?>
                    <p class="rd-empty col-span-full">Não está em nenhuma equipe ainda</p>
                <?php endif;?>
            </div>
        </section>

                <section class="rd-section">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="rd-eyebrow">TRABALHOS</p>
                    <h2 class="rd-heading text-[clamp(1.5rem,3.5vw,2.5rem)]"><?= $perfilProprio ? "MEUS" : "" ?> <span>PROJETOS</span></h2>
                </div>
                <?php if($perfilProprio): ?>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" onclick="document.getElementById('modalGerenciarCategorias').classList.remove('hidden')" class="rd-btn rd-btn-ghost rd-btn-sm">
                            Gerenciar Categorias
                        </button>
                        <a href="<?= URL_BASE ?>/projeto/cadastro" class="rd-btn rd-btn-primary w-fit">
                            Adicionar projeto
                        </a>
                        <a href="<?= URL_BASE ?>/projeto/entrar" class="rd-btn rd-btn-secondary w-fit">
                            Entrar em projeto
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- FILTRO DE BUSCA E CATEGORIAS NO PERFIL -->
            <div class="mb-6 flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="buscaProjetosPerfil" 
                        placeholder="Pesquisar nos projetos deste perfil..." 
                        class="rd-input w-full"
                    >
                </div>
                <div class="sm:w-64">
                    <select id="categoriaPerfil" data-atualizar class="rd-select w-full">
                        <option value="">Todas as categorias</option>
                        <?php if(!empty($categorias)): ?>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat->getId() ?>">
                                    <?= texto($cat->getNome()) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- LISTAGEM DOS PROJETOS COM AJAX -->
            <div id="gridProjetosPerfil" data-atualizar class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                <?php if(isset($projetos) && count($projetos) > 0):?>
                    <?php foreach($projetos as $projeto):?>
                        <?php include(__DIR__."/elements/cardProjeto.php")?>
                    <?php endforeach;?>
                <?php else: ?>
                    <p class="rd-empty col-span-full">Nenhum projeto cadastrado ainda</p>
                <?php endif;?>
            </div>
        </section>

    </div>
</div>

<div id="gerenciarCategorias" data-atualizar>
<?php if($perfilProprio && !empty($categorias) && count($categorias) > 1): ?>
<!-- MODAL DE EXCLUIR CATEGORIA COM REATRIBUIÇÃO OBRIGATÓRIA -->
<div id="modalGerenciarCategorias" class="fixed inset-0 z-50 flex items-center justify-center bg-[#000505]/85 p-4 hidden">
    <div class="rd-card !p-6 max-w-md w-full border-2 border-[#07556A] bg-[#06141C]">
        <h3 class="font-['Orbitron'] text-lg font-bold text-[#13F3F7] mb-2">Apagar Categoria</h3>
        <p class="text-xs text-[#91B5BD] mb-4">Como os projetos não podem ficar sem categoria, selecione a categoria a ser removida e para qual categoria seus projetos serão transferidos:</p>
        
        <form action="<?= URL_BASE ?>/usuario/excluir-categoria" method="post" class="space-y-4">
            <div>
                <label class="rd-label">Categoria a ser excluída:</label>
                <select name="categoria_antiga_id" id="catAntiga" class="rd-select w-full" onchange="filtrarNovaCategoria()">
                    <option value="">Selecione</option>
                    <?php foreach($categorias as $c): ?>
                        <option value="<?= $c->getId() ?>"><?= texto($c->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="rd-label">Mover projetos para:</label>
                <select name="categoria_nova_id" id="catNova" class="rd-select w-full">
                    <option value="">Selecione</option>
                    <?php foreach($categorias as $c): ?>
                        <option value="<?= $c->getId() ?>"><?= texto($c->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalGerenciarCategorias').classList.add('hidden')" class="rd-btn rd-btn-ghost rd-btn-sm">
                    Cancelar
                </button>
                <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">
                    Transferir e Apagar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function filtrarNovaCategoria() {
    const antigaId = document.getElementById('catAntiga').value;
    const catNova = document.getElementById('catNova');
    for (let opt of catNova.options) {
        opt.disabled = (opt.value === antigaId && opt.value !== "");
    }
}
</script>
<?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBuscaEquipe = document.getElementById("buscaEquipesPerfil");
    const gridEquipes = document.getElementById("gridEquipesPerfil");
    let timeoutEquipe = null;

    function filtrarEquipesPerfil() {
        const params = new URLSearchParams();
        params.append("id", "<?= $usuario->getId() ?>");
        if (inputBuscaEquipe.value.trim()) params.append("busca_equipe", inputBuscaEquipe.value.trim());
        params.append("ajax_equipe", "1");

        const url = "<?= URL_BASE ?>/usuario/perfil?" + params.toString();
        gridEquipes.dataset.fonte = url;

        fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            gridEquipes.innerHTML = html;
        });
    }

    if (inputBuscaEquipe) {
        inputBuscaEquipe.addEventListener("input", function() {
            clearTimeout(timeoutEquipe);
            timeoutEquipe = setTimeout(filtrarEquipesPerfil, 300);
        });
    }

    const inputBusca = document.getElementById("buscaProjetosPerfil");
    const selectCategoria = document.getElementById("categoriaPerfil");
    const grid = document.getElementById("gridProjetosPerfil");
    let timeout = null;

    function filtrarProjetosPerfil() {
        const busca = inputBusca.value.trim();
        const categoria = selectCategoria.value;
        const params = new URLSearchParams();
        params.append("id", "<?= $usuario->getId() ?>");
        if (busca) params.append("busca", busca);
        if (categoria) params.append("categoria_id", categoria);
        params.append("ajax", "1");

        const url = "<?= URL_BASE ?>/usuario/perfil?" + params.toString();
        grid.dataset.fonte = url;

        fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            grid.innerHTML = html;
        });
    }

    if (inputBusca) {
        inputBusca.addEventListener("input", function() {
            clearTimeout(timeout);
            timeout = setTimeout(filtrarProjetosPerfil, 300);
        });
    }
    if (selectCategoria) {
        selectCategoria.addEventListener("change", filtrarProjetosPerfil);
    }
});
</script>
<?php
include_once(__DIR__."/../elements/adminModais.php");
include_once(__DIR__."/../elements/denunciaModal.php");
include_once(__DIR__."/../elements/footer.php");
endif;