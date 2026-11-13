<?php if(isset($equipe)): ?>
<?php
    $nomeEquipe = htmlspecialchars(html_entity_decode($equipe->getNome() ?? 'Equipe'));
    $descEquipe = htmlspecialchars(html_entity_decode($equipe->getDescricao() ?? 'Equipe de robótica RoboDrive'));
?>
<div class="relative border-2 border-[#07556A] bg-[#06141C] p-4 hover:border-[#13F3F7] transition flex flex-col justify-between w-full sm:w-[280px] group shadow-[4px_4px_0_#000505]">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <span class="flex h-7 w-7 items-center justify-center border border-[#07556A] bg-[#000505] text-[#13F3F7] text-xs font-bold">
                ⚡
            </span>
            <span class="text-[.65rem] font-bold uppercase tracking-wider text-[#91B5BD] font-mono">
                EQUIPE
            </span>
        </div>

        <a href="<?= URL_BASE ?>/equipe/perfil?id=<?= $equipe->getId() ?>" class="block">
            <h3 class="font-['Orbitron'] text-sm font-black text-[#F2FEFE] group-hover:text-[#13F3F7] transition truncate">
                <?= $nomeEquipe ?>
            </h3>
        </a>

        <p class="text-xs text-[#91B5BD] line-clamp-2 mt-1 leading-relaxed">
            <?= $descEquipe ?>
        </p>
    </div>

    <div class="mt-3 pt-2.5 border-t border-[#07556A]/60 flex items-center justify-between">
        <span class="text-[.65rem] text-[#4E6B72] uppercase font-mono">Membro ativo</span>
        <a href="<?= URL_BASE ?>/equipe/perfil?id=<?= $equipe->getId() ?>" class="text-xs font-bold text-[#13F3F7] hover:underline">
            Acessar equipe →
        </a>
    </div>
</div>
<?php endif; ?>