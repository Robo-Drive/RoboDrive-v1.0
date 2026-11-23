
<?php if(isset($projeto)): ?>
<?php
    $idProjeto = (int) $projeto->getId();
    $nomeProjeto = htmlspecialchars($projeto->getNome() ?? 'Projeto');
    $descProjeto = htmlspecialchars($projeto->getDescricao() ?? 'Projeto de robótica');
    $dataProjeto = $projeto->getCriadoEm()->format("d/m/Y");
?>

<div class="relative w-full sm:w-[300px] group">

    <!-- PLACA ARDUINO UNO -->
    <div class="relative h-[245px] w-full
                bg-[#07556A] p-[3px]
                shadow-[5px_5px_0_#000505]
                transition-all duration-300
                group-hover:-translate-y-1
                group-hover:shadow-[0_0_18px_#13F3F755]">

        <!-- Formato da PCB -->
        <div class="relative h-full w-full overflow-hidden
                    bg-[#06141C] border border-[#13F3F7]/30">

            <!-- Trilhas eletrônicas de fundo -->
            <div class="absolute inset-0 opacity-40 pointer-events-none">

                <div class="absolute top-[35px] left-[45px] w-[65px] h-px bg-[#13F3F7]"></div>
                <div class="absolute top-[35px] left-[110px] h-[35px] w-px bg-[#13F3F7]"></div>
                <div class="absolute top-[70px] left-[110px] w-[35px] h-px bg-[#13F3F7]"></div>

                <div class="absolute top-[110px] left-[30px] w-[55px] h-px bg-[#13F3F7]"></div>
                <div class="absolute top-[110px] left-[85px] h-[45px] w-px bg-[#13F3F7]"></div>

                <div class="absolute bottom-[45px] right-[35px] w-[65px] h-px bg-[#13F3F7]"></div>
                <div class="absolute bottom-[45px] right-[100px] h-[35px] w-px bg-[#13F3F7]"></div>

                <div class="absolute top-[55px] right-[25px] w-[45px] h-px bg-[#13F3F7]"></div>
                <div class="absolute top-[55px] right-[70px] h-[35px] w-px bg-[#13F3F7]"></div>

                <div class="absolute bottom-[75px] left-[25px] w-[35px] h-px bg-[#13F3F7]"></div>
                <div class="absolute bottom-[75px] left-[60px] h-[30px] w-px bg-[#13F3F7]"></div>

                <!-- Nós das trilhas -->
                <div class="absolute top-[32px] left-[107px] w-1.5 h-1.5 rounded-full bg-[#13F3F7]"></div>
                <div class="absolute top-[107px] left-[82px] w-1.5 h-1.5 rounded-full bg-[#13F3F7]"></div>
                <div class="absolute bottom-[42px] right-[97px] w-1.5 h-1.5 rounded-full bg-[#13F3F7]"></div>
            </div>

            <!-- FUROS DE FIXAÇÃO -->
            <div class="absolute top-2 left-2 z-[2] w-5 h-5 rounded-full
                        border-[3px] border-[#07556A] bg-[#000505]
                        shadow-[inset_0_0_4px_#13F3F7]"></div>

            <div class="absolute top-2 right-2 z-[2] w-5 h-5 rounded-full
                        border-[3px] border-[#07556A] bg-[#000505]
                        shadow-[inset_0_0_4px_#13F3F7]"></div>

            <div class="absolute bottom-2 left-2 z-[2] w-5 h-5 rounded-full
                        border-[3px] border-[#07556A] bg-[#000505]
                        shadow-[inset_0_0_4px_#13F3F7]"></div>

            <div class="absolute bottom-2 right-2 z-[2] w-5 h-5 rounded-full
                        border-[3px] border-[#07556A] bg-[#000505]
                        shadow-[inset_0_0_4px_#13F3F7]"></div>

            <!-- CONECTOR USB-B -->
            <div class="absolute left-0 top-[68px] z-[3] w-[25px] h-[38px]
                        border-2 border-[#91B5BD] bg-[#26383D]
                        shadow-[2px_2px_0_#000505]">
                <div class="absolute top-2 left-1 w-[19px] h-5
                            border border-[#91B5BD] bg-[#000505]"></div>
                <div class="absolute top-3 left-2 w-[15px] h-3 bg-[#07556A]"></div>
            </div>

            <!-- CONECTOR DE ALIMENTAÇÃO -->
            <div class="absolute left-[3px] top-[20px] z-[3] w-[27px] h-[27px]
                        rounded-sm border-2 border-[#91B5BD] bg-[#26383D]
                        flex items-center justify-center">
                <div class="w-3.5 h-3.5 rounded-full border-2
                            border-[#91B5BD] bg-[#000505]"></div>
            </div>

            <!-- MICROCONTROLADOR ATMEGA -->
            <div class="absolute left-[42%] top-[48px] z-[2]
                        w-[70px] h-[32px] bg-[#000505]
                        border-2 border-[#4E6B72]">

                <div class="absolute inset-1 border border-[#07556A]"></div>

                <span class="absolute inset-0 flex items-center justify-center
                             text-[.48rem] font-mono font-bold text-[#13F3F7]">
                    ATMEGA328P
                </span>

                <!-- Terminais do chip -->
                <?php for($i = 0; $i < 7; $i++): ?>
                    <span class="absolute -left-[4px] w-[3px] h-[2px]
                                 bg-[#B7A66A]"
                          style="top: <?= 3 + ($i * 4) ?>px"></span>
                    <span class="absolute -right-[4px] w-[3px] h-[2px]
                                 bg-[#B7A66A]"
                          style="top: <?= 3 + ($i * 4) ?>px"></span>
                <?php endfor; ?>
            </div>

            <!-- CAPACITOR -->
            <div class="absolute top-[52px] right-[35px] z-[2]
                        w-5 h-5 rounded-full border-2
                        border-[#91B5BD] bg-[#07556A]">
                <div class="absolute inset-1 rounded-full bg-[#000505]"></div>
            </div>

            <!-- CRISTAL OSCILADOR -->
            <div class="absolute bottom-[70px] left-[38%] z-[2]
                        w-7 h-3 border border-[#91B5BD]
                        bg-[#26383D] flex items-center justify-center">
                <div class="w-3 h-1 bg-[#B7A66A]"></div>
            </div>

            <!-- IDENTIFICAÇÃO DO PROJETO -->
            <div class="absolute top-[88px] left-[42px] right-[35px] z-[4]
                        border-t border-[#07556A] pt-2">

                <div class="flex items-center justify-between gap-2">
                    <span class="text-[.48rem] font-mono tracking-[.15em] text-[#91B5BD]">
                        PROJECT_<?= str_pad($idProjeto, 4, '0', STR_PAD_LEFT) ?>
                    </span>

                    <span class="flex items-center gap-1 text-[.48rem] font-mono text-[#13F3F7]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#13F3F7]"></span>
                        READY
                    </span>
                </div>

                <h3 class="mt-1 font-['Orbitron'] text-sm font-black
                           text-[#F2FEFE] truncate
                           group-hover:text-[#13F3F7] transition-colors">
                    <?= $nomeProjeto ?>
                </h3>

                <p class="mt-1 text-[.62rem] leading-relaxed text-[#91B5BD]
                          line-clamp-2 min-h-[30px]">
                    <?= $descProjeto ?>
                </p>

                <div class="flex justify-between items-center mt-1">
                    <span class="text-[.48rem] font-mono text-[#91B5BD]">
                        <?= $dataProjeto ?>
                    </span>

                    <span class="text-[.5rem] font-mono font-bold text-[#13F3F7]">
                        VIEW PROJECT →
                    </span>
                </div>
            </div>

            <!-- PINOS DIGITAIS SUPERIORES -->
            <div class="absolute top-0 left-[35px] right-[30px] z-[3]
                        flex justify-around">

                <?php for($i = 0; $i < 14; $i++): ?>
                    <div class="flex flex-col items-center">
                        <span class="text-[.38rem] text-[#91B5BD] font-mono">
                            <?= $i ?>
                        </span>
                        <div class="w-2 h-2 border border-[#B7A66A]
                                    bg-[#000505] group-hover:border-[#13F3F7]
                                    transition-colors"></div>
                    </div>
                <?php endfor; ?>

            </div>

            <!-- PINOS ANALÓGICOS INFERIORES -->
            <div class="absolute bottom-0 left-[35px] right-[30px] z-[3]
                        flex justify-around">

                <?php for($i = 0; $i < 6; $i++): ?>
                    <div class="flex flex-col items-center">
                        <div class="w-2 h-2 border border-[#B7A66A]
                                    bg-[#000505] group-hover:border-[#13F3F7]
                                    transition-colors"></div>
                        <span class="text-[.38rem] text-[#91B5BD] font-mono">
                            A<?= $i ?>
                        </span>
                    </div>
                <?php endfor; ?>

            </div>

            <!-- LEDS INDICADORES -->
            <div class="absolute bottom-[48px] right-[18px] z-[3] flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#13F3F7]
                             shadow-[0_0_5px_#13F3F7]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#07556A]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#07556A]"></span>
            </div>

            <!-- LOGOTIPO -->
            <span class="absolute bottom-[20px] left-[18px] z-[2]
                         text-[.45rem] font-['Orbitron'] font-bold
                         tracking-widest text-[#07556A]">
                ROBO DRIVE
            </span>

        </div>
    </div>

    <!-- LINK DE ACESSO À PLACA INTEIRA -->
    <a href="<?= URL_BASE ?>/projeto/perfil?id=<?= $idProjeto ?>"
       class="absolute inset-0 z-10"
       aria-label="Ver projeto <?= $nomeProjeto ?>"></a>

    <!-- DENÚNCIA: INDEPENDENTE DO LINK PRINCIPAL -->
    <?php
        $denunciaAlvo = [
            "tipo" => "projeto",
            "id" => $idProjeto,
            "nome" => $projeto->getNome(),
            //"autores" => [$usuario->getId()],
            "classe" => "absolute right-1 top-1 z-20"
        ];

        include(__DIR__."/../../elements/denunciaBotao.php");
    ?>

</div>
<?php endif; ?>