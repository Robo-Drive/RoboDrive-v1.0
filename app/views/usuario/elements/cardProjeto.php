
<?php if (isset($projeto)): ?>

<?php
    $idProjeto = (int) $projeto->getId();
    $nomeProjeto = htmlspecialchars($projeto->getNome() ?? 'Projeto');
    $descProjeto = htmlspecialchars($projeto->getDescricao() ?? 'Projeto de robótica');
    $dataProjeto = $projeto->getCriadoEm()->format("d/m/Y");
?>

<div class="relative w-full sm:w-[300px] group">

    <!-- CARTÃO -->
    <div class="relative min-h-[210px] overflow-hidden
                rounded-[18px_6px_18px_6px]
                border border-[#07556A]
                bg-[#06141C]
                p-6
                shadow-[4px_4px_0_#000505]
                transition-all duration-300
                group-hover:-translate-y-1
                group-hover:border-[#13F3F7]/60
                group-hover:shadow-[0_0_18px_#13F3F722]">

        <!-- FUNDO: SILHUETA DE PLACA -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">

            <!-- Trilhas eletrônicas -->
            <div class="absolute top-8 right-8 w-24 h-px bg-[#13F3F7] opacity-[0.12]"></div>
            <div class="absolute top-8 right-8 w-px h-16 bg-[#13F3F7] opacity-[0.12]"></div>
            <div class="absolute top-24 right-8 w-12 h-px bg-[#13F3F7] opacity-[0.12]"></div>

            <div class="absolute bottom-12 left-0 w-20 h-px bg-[#13F3F7] opacity-[0.12]"></div>
            <div class="absolute bottom-12 left-20 w-px h-12 bg-[#13F3F7] opacity-[0.12]"></div>

            <!-- Silhueta de uma placa Arduino -->
            <div class="absolute -right-8 -bottom-12
                        w-[190px] h-[145px]
                        rotate-[-12deg]
                        rounded-[22px_8px_22px_8px]
                        border-[8px] border-[#07556A]
                        opacity-[0.13]">

                <!-- Furos da placa -->
                <div class="absolute top-2 left-2 w-3 h-3 rounded-full border-2 border-[#13F3F7]"></div>
                <div class="absolute top-2 right-2 w-3 h-3 rounded-full border-2 border-[#13F3F7]"></div>
                <div class="absolute bottom-2 left-2 w-3 h-3 rounded-full border-2 border-[#13F3F7]"></div>
                <div class="absolute bottom-2 right-2 w-3 h-3 rounded-full border-2 border-[#13F3F7]"></div>

                <!-- Microcontrolador -->
                <div class="absolute top-1/2 left-1/2
                            -translate-x-1/2 -translate-y-1/2
                            w-12 h-7 border-2 border-[#13F3F7]
                            bg-[#07556A]">
                </div>
            </div>

            <!-- Brilho discreto -->
            <div class="absolute -top-20 -right-20
                        w-52 h-52 rounded-full
                        bg-[#13F3F7]/[0.04] blur-3xl">
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
                    ROBO DRIVE / PROJECT
                </span>

            </div>

            <!-- Nome -->
            <h3 class="font-['Orbitron'] text-lg font-bold
                       leading-snug text-[#F2FEFE]
                       line-clamp-2
                       transition-colors duration-300
                       group-hover:text-[#13F3F7]">

                <?= $nomeProjeto ?>

            </h3>

            <!-- Descrição -->
            <p class="mt-3 text-sm leading-relaxed
                      text-[#91B5BD]
                      line-clamp-3">

                <?= $descProjeto ?>

            </p>

            <!-- Rodapé -->
            <div class="flex items-center justify-between
                        mt-auto pt-5">

                <span class="text-[.65rem] font-mono text-[#4E6B72]">
                    <?= $dataProjeto ?>
                </span>

                <span class="text-xs font-semibold tracking-wide
                             text-[#13F3F7]
                             transition-all duration-300
                             group-hover:translate-x-1">

                    Explorar →

                </span>

            </div>

        </div>

        <!-- BORDA DECORATIVA -->
        <div class="absolute bottom-0 left-6 right-6
                    h-px bg-gradient-to-r
                    from-transparent via-[#13F3F7]/30 to-transparent">
        </div>

    </div>

    <!-- LINK DO PROJETO -->
    <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $idProjeto ?>"
       class="absolute inset-0 z-10"
       aria-label="Ver projeto <?= $nomeProjeto ?>">
    </a>

    <!-- DENÚNCIA -->
    <?php
        $denunciaAlvo = [
            "tipo" => "projeto",
            "id" => $idProjeto,
            "nome" => $projeto->getNome(),
            "autores" => [$usuario->getId()],
            "classe" => "absolute right-2 top-2 z-20"
        ];

        include(__DIR__ . "/../../elements/denunciaBotao.php");
    ?>

</div>

<?php endif; ?>
