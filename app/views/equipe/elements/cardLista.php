<?php if(isset($equipe)): ?>
<?php
    $nomeEquipe = htmlspecialchars(html_entity_decode($equipe->getNome() ?? 'Equipe'));
    $descEquipe = htmlspecialchars(html_entity_decode($equipe->getDescricao() ?? 'Equipe de robótica RoboDrive'));
    $idEquipe = (int) $equipe->getId();
?>

<a href="<?= URL_BASE ?>/equipe/perfil?id=<?= $idEquipe ?>"
   class="relative block w-full sm:w-[280px] group cursor-pointer outline-none"
   aria-label="Acessar equipe <?= $nomeEquipe ?>">

    <!-- Terminais superiores -->
    <div class="flex justify-around px-5 h-4">
        <?php for($i = 0; $i < 8; $i++): ?>
            <div class="w-[5px] h-full bg-[#07556A] border-x border-[#13F3F7]/40
                        group-hover:bg-[#13F3F7] transition-colors duration-300"></div>
        <?php endfor; ?>
    </div>

    <!-- Corpo do microcontrolador -->
    <div class="relative border-2 border-[#07556A] bg-[#06141C]
                p-3 transition-all duration-300
                group-hover:border-[#13F3F7]
                group-hover:shadow-[0_0_18px_#13F3F744]
                group-hover:-translate-y-1
                shadow-[5px_5px_0_#000505]">

        <!-- Cantos técnicos -->
        <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-[#13F3F7]"></div>
        <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-[#13F3F7]"></div>
        <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-[#13F3F7]"></div>
        <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-[#13F3F7]"></div>

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between mb-3 px-1">
            <span class="text-[.6rem] font-mono tracking-[.2em] text-[#91B5BD] uppercase">
                ROBODRIVE // SYSTEM
            </span>

            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-[#13F3F7] shadow-[0_0_7px_#13F3F7]
                             group-hover:animate-pulse"></span>
                <span class="text-[.55rem] font-mono text-[#13F3F7]">ACTIVE</span>
            </div>
        </div>

        <!-- Chip principal -->
        <div class="relative border-2 border-[#07556A] bg-[#000A0F] p-3">

            <!-- Trilhas decorativas -->
            <div class="absolute -left-[7px] top-5 w-2 h-px bg-[#13F3F7]"></div>
            <div class="absolute -left-[7px] bottom-5 w-2 h-px bg-[#13F3F7]"></div>
            <div class="absolute -right-[7px] top-5 w-2 h-px bg-[#13F3F7]"></div>
            <div class="absolute -right-[7px] bottom-5 w-2 h-px bg-[#13F3F7]"></div>

            <!-- Identificação MCU -->
            <div class="flex items-center gap-3 mb-3">

                <div class="relative flex items-center justify-center
                            w-12 h-12 shrink-0 border-2 border-[#07556A]
                            bg-[#06141C] group-hover:border-[#13F3F7] transition">

                    <div class="absolute inset-1 border border-[#13F3F7]/30"></div>

                    <span class="font-['Orbitron'] text-sm font-black text-[#13F3F7]
                                 drop-shadow-[0_0_5px_#13F3F7]">
                        MCU
                    </span>
                </div>

                <div class="min-w-0 flex-1">
                    <span class="block text-[.55rem] font-mono text-[#4E6B72]
                                 tracking-[.15em] mb-1">
                        TEAM_ID_<?= str_pad($idEquipe, 4, '0', STR_PAD_LEFT) ?>
                    </span>

                    <h3 class="font-['Orbitron'] text-sm font-black text-[#F2FEFE]
                               group-hover:text-[#13F3F7] transition truncate">
                        <?= $nomeEquipe ?>
                    </h3>
                </div>
            </div>

            <!-- Separador eletrônico -->
            <div class="flex items-center gap-2 mb-2">
                <div class="h-px flex-1 bg-[#07556A]"></div>
                <span class="text-[.5rem] font-mono text-[#13F3F7]">DESCRIPTION</span>
                <div class="h-px flex-1 bg-[#07556A]"></div>
            </div>

            <!-- Descrição -->
            <p class="text-xs text-[#91B5BD] leading-relaxed line-clamp-2 min-h-[34px]">
                <?= $descEquipe ?>
            </p>

            <!-- Indicadores -->
            <div class="grid grid-cols-3 gap-1 mt-3 pt-2 border-t border-[#07556A]/50">

                <div>
                    <span class="block text-[.5rem] text-[#4E6B72] font-mono">TYPE</span>
                    <span class="text-[.6rem] text-[#91B5BD] font-mono">TEAM</span>
                </div>

                <div>
                    <span class="block text-[.5rem] text-[#4E6B72] font-mono">STATUS</span>
                    <span class="text-[.6rem] text-[#13F3F7] font-mono">READY</span>
                </div>

                <div>
                    <span class="block text-[.5rem] text-[#4E6B72] font-mono">ACCESS</span>
                    <span class="text-[.6rem] text-[#91B5BD] font-mono">OPEN</span>
                </div>

            </div>
        </div>

        <!-- Rodapé -->
        <div class="flex items-center justify-between mt-3 px-1">
            <span class="text-[.6rem] font-mono text-[#4E6B72] uppercase">
                Membro ativo
            </span>

            <span class="text-[.65rem] font-bold font-mono text-[#13F3F7]
                         group-hover:text-[#F2FEFE] transition">
                CONNECT →
            </span>
        </div>

        <!-- Detalhe de circuito -->
        <div class="absolute right-0 top-1/2 translate-x-full
                    w-2 h-px bg-[#13F3F7]/60"></div>

    </div>

    <!-- Terminais inferiores -->
    <div class="flex justify-around px-5 h-4">
        <?php for($i = 0; $i < 8; $i++): ?>
            <div class="w-[5px] h-full bg-[#07556A] border-x border-[#13F3F7]/40
                        group-hover:bg-[#13F3F7] transition-colors duration-300"></div>
        <?php endfor; ?>
    </div>

</a>
<?php endif; ?>