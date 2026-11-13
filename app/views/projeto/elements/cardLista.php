<?php if(isset($projeto)): ?>
<?php
    $nomeProj = htmlspecialchars(html_entity_decode($projeto->getNome() ?? 'Projeto'));
    $descProj = htmlspecialchars(html_entity_decode($projeto->getDescricao() ?? 'Sem descrição cadastrada.'));
    $catNome = htmlspecialchars(html_entity_decode($projeto->getCategoria()?->getNome() ?? 'Geral'));
    $dataCriacao = $projeto->getCriadoEm() ? $projeto->getCriadoEm()->format("d/m/Y") : '';
?>
<div class="relative border-2 border-[#07556A] bg-[#06141C] p-5 hover:border-[#13F3F7] transition flex flex-col justify-between group shadow-[4px_4px_0_#000505]">
    <div>
        <!-- TOPO: CATEGORIA E DENÚNCIA -->
        <div class="flex items-center justify-between gap-2 mb-3 pr-8">
            <span class="text-[.6rem] font-bold uppercase tracking-wider px-2 py-0.5 border border-[#0795A5] text-[#13F3F7] bg-[#07556A]/20 font-mono">
                <?= $catNome ?>
            </span>
            <span class="text-[.6rem] text-[#91B5BD] uppercase font-mono">
                <?= htmlspecialchars(ucfirst($projeto->getVisibilidade() ?? 'Público')) ?>
            </span>
        </div>

        <!-- TÍTULO COM LINK -->
        <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>" class="block">
            <h3 class="font-['Orbitron'] text-base font-black text-[#F2FEFE] group-hover:text-[#13F3F7] transition truncate">
                <?= $nomeProj ?>
            </h3>
        </a>

        <!-- DESCRIÇÃO -->
        <p class="text-xs text-[#91B5BD] line-clamp-3 mt-2 leading-relaxed">
            <?= $descProj ?>
        </p>
    </div>

    <!-- RODAPÉ: DATA E BOTÃO VER PROJETO -->
    <div class="mt-4 pt-3 border-t border-[#07556A]/60 flex items-center justify-between">
        <span class="text-[.65rem] font-bold uppercase tracking-[.1em] text-[#4E6B72]">
            Criado em <?= $dataCriacao ?>
        </span>
        <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $projeto->getId() ?>" class="text-xs font-bold text-[#13F3F7] hover:underline flex items-center gap-1">
            Ver projeto →
        </a>
    </div>

    <!-- BOTÃO DE DENÚNCIA -->
    <?php
    $denunciaAlvo = [
        "tipo"    => "projeto",
        "id"      => $projeto->getId(),
        "nome"    => $projeto->getNome(),
        "classe"  => "absolute right-2 top-2 z-10"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif; ?>