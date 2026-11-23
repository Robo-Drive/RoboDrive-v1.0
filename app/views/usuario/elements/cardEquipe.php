
<?php if(isset($equipe)): ?>

<?php
    $nomeEquipe = htmlspecialchars(html_entity_decode($equipe->getNome() ?? 'Equipe'));
    $descEquipe = htmlspecialchars(html_entity_decode($equipe->getDescricao() ?? 'Equipe de robótica RoboDrive'));
    $idEquipe = (int) $equipe->getId();
?>

<a href="<?= URL_BASE ?>/equipe/perfil?id=<?= $idEquipe ?>"
   class="relative block w-full sm:w-[300px] group cursor-pointer"
   aria-label="Acessar equipe <?= $nomeEquipe ?>">

    <div class="relative min-h-[210px] overflow-hidden
                rounded-[18px_6px_18px_6px]
                border border-[#07556A]
                bg-[#06141C] p-6
                shadow-[4px_4px_0_#000505]
                transition-all duration-300
                group-hover:-translate-y-1
                group-hover:border-[#13F3F7]/60
                group-hover:shadow-[0_0_18px_#13F3F722]">

        <!-- FUNDO TECNOLÓGICO -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">

            <!-- Gradiente -->
            <div class="absolute inset-0
                        bg-[radial-gradient(ellipse_at_top_right,_#07556A55,_transparent_65%)]">
            </div>

            <!-- Circuitos -->
            <div class="absolute top-10 right-0 w-20 h-px bg-[#13F3F7]/15"></div>
            <div class="absolute top-10 right-20 w-px h-12 bg-[#13F3F7]/15"></div>
            <div class="absolute top-[88px] right-20 w-12 h-px bg-[#13F3F7]/15"></div>

            <div class="absolute bottom-8 left-0 w-16 h-px bg-[#13F3F7]/15"></div>
            <div class="absolute bottom-8 left-16 w-px h-10 bg-[#13F3F7]/15"></div>

            <!-- Representação de uma equipe -->
            <div class="absolute right-5 bottom-5
                        flex items-end gap-2 opacity-[0.13]">

                <!-- Pessoa 1 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full border-[3px]
                                border-[#13F3F7]"></div>
                    <div class="mt-1 w-10 h-14 rounded-t-full
                                border-[3px] border-[#13F3F7]"></div>
                </div>

                <!-- Pessoa 2 -->
                <div class="flex flex-col items-center -translate-y-3">
                    <div class="w-8 h-8 rounded-full border-[3px]
                                border-[#13F3F7]"></div>
                    <div class="mt-1 w-11 h-16 rounded-t-full
                                border-[3px] border-[#13F3F7]"></div>
                </div>

                <!-- Pessoa 3 -->
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full border-[3px]
                                border-[#13F3F7]"></div>
                    <div class="mt-1 w-10 h-14 rounded-t-full
                                border-[3px] border-[#13F3F7]"></div>
                </div>

            </div>

        </div>

        <!-- CONTEÚDO -->
        <div class="relative z-[2] flex flex-col min-h-[160px]">

            <!-- Identificação -->
            <div class="flex items-center gap-2 mb-5">

                <span class="w-2 h-2 rounded-full bg-[#13F3F7]
                             shadow-[0_0_7px_#13F3F7]">
                </span>

                <span class="text-[.6rem] font-mono tracking-[.2em]
                             text-[#91B5BD]">
                    ROBO DRIVE / TEAM
                </span>

            </div>

            <!-- Nome -->
            <h3 class="font-['Orbitron'] text-lg font-bold
                       leading-snug text-[#F2FEFE]
                       line-clamp-2
                       transition-colors duration-300
                       group-hover:text-[#13F3F7]">

                <?= $nomeEquipe ?>

            </h3>

            <!-- Descrição -->
            <p class="mt-3 text-sm leading-relaxed
                      text-[#91B5BD] line-clamp-3">

                <?= $descEquipe ?>

            </p>

            <!-- Rodapé -->
            <div class="flex items-center justify-between mt-auto pt-5">

                <span class="text-[.65rem] font-mono text-[#91B5BD]">
                    TEAM_<?= str_pad($idEquipe, 4, '0', STR_PAD_LEFT) ?>
                </span>

                <span class="text-xs font-semibold tracking-wide
                             text-[#13F3F7]
                             transition-all duration-300
                             group-hover:translate-x-1">

                    Conhecer equipe →

                </span>

            </div>

        </div>

        <!-- DETALHE INFERIOR -->
        <div class="absolute bottom-0 left-6 right-6 h-px
                    bg-gradient-to-r from-transparent
                    via-[#13F3F7]/30 to-transparent">
        </div>

    </div>

    <!-- DENÚNCIA -->
    <?php
        $denunciaAlvo = [
            "tipo" => "equipe",
            "id" => $idEquipe,
            "nome" => $equipe->getNome(),
            "autores" => [$usuario->getId()],
            "classe" => "absolute right-2 top-2 z-20"
        ];

        include(__DIR__ . "/../../elements/denunciaBotao.php");
    ?>

</a>

<?php endif; ?>
