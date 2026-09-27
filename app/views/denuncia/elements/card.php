<?php if(isset($denuncia)):
$tipoDenuncia = $tiposDenuncia[$denuncia["tipo"]];
$primeiraDenuncia = new DateTimeImmutable($denuncia["primeira_denuncia"]);
$diasPendente = $primeiraDenuncia->diff(new DateTimeImmutable())->days;
?>
<article class="denuncia-item rd-card !p-5 sm:!p-6" data-tipo="<?= $denuncia["tipo"] ?>" data-quantidade="<?= $denuncia["quantidade"] ?>" data-primeira="<?= $primeiraDenuncia->getTimestamp() ?>">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-start">

        <div class="flex shrink-0 items-center gap-4 lg:w-28 lg:flex-col lg:items-stretch">
            <span class="denuncia-posicao font-['Orbitron'] text-xl font-black text-[#4E6B72]"></span>
            <div class="border-[3px] border-[#F0ED06] bg-[#F0ED06]/10 px-3 py-2 text-center">
                <p class="font-['Orbitron'] text-2xl font-black leading-none text-[#F0ED06]"><?= $denuncia["quantidade"] ?></p>
                <p class="mt-1 text-[.6rem] font-bold uppercase tracking-[.15em] text-[#F0ED06]"><?= $denuncia["quantidade"] == 1 ? "denúncia" : "denúncias" ?></p>
            </div>
        </div>

        <div class="flex min-w-0 flex-1 flex-col gap-3">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                <span class="rd-badge"><?= $tipoDenuncia["rotulo"] ?></span>
                <span class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">
                    Desde <?= $primeiraDenuncia->format("d/m/Y") ?> · <?= $diasPendente == 0 ? "hoje" : ($diasPendente == 1 ? "há 1 dia" : "há $diasPendente dias") ?>
                </span>
            </div>

            <?php if(in_array($denuncia["tipo"], ["forum", "comentario"])): ?>
                <blockquote class="line-clamp-3 break-words border-l-4 border-[#07556A] pl-3 text-sm leading-relaxed text-[#F2FEFE]">“<?= $denuncia["alvo_nome"] ?>”</blockquote>
            <?php else: ?>
                <h3 class="truncate font-['Orbitron'] text-lg font-black text-[#F2FEFE]"><?= $denuncia["alvo_nome"] ?></h3>
            <?php endif; ?>

            <p class="text-sm text-[#91B5BD]">
                <?= $denuncia["tipo"] == "usuario" ? "Conta" : "Responsável" ?>:
                <a href="<?= URL_BASE ?>/usuario/perfil?id=<?= $denuncia["responsavel_id"] ?>" class="font-bold text-[#F2FEFE] underline decoration-[#07556A] decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#13F3F7]">@<?= $denuncia["responsavel_usuario"] ?></a>
            </p>

            <?php if(!empty($denuncia["motivos"])): ?>
                <ul class="flex flex-wrap gap-2" aria-label="Motivos das denúncias">
                    <?php foreach($denuncia["motivos"] as $motivo => $quantidadeMotivo): ?>
                        <li class="border-2 border-[#07556A] bg-[#082B3A] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.08em] text-[#F2FEFE]">
                            <?= $motivo ?> <span class="text-[#91B5BD]">×<?= $quantidadeMotivo ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="grid shrink-0 grid-cols-2 gap-2 sm:flex sm:flex-wrap lg:grid lg:w-44 lg:grid-cols-1">
            <?php if($tipoDenuncia["rota"]): ?>
                <form action="<?= URL_BASE.$tipoDenuncia["rota"] ?>" method="<?= $tipoDenuncia["metodo"] ?>">
                    <input type="hidden" name="id" value="<?= $denuncia["alvo_id"] ?>">
                    <button type="submit" class="rd-btn rd-btn-ghost rd-btn-sm w-full">Ver conteúdo</button>
                </form>
            <?php endif; ?>
            <button
                type="button"
                class="rd-btn rd-btn-secondary rd-btn-sm w-full"
                data-comunicado
                data-id="<?= $denuncia["responsavel_id"] ?>"
                data-nome="<?= $denuncia["responsavel_nome"] ?>"
                data-email="<?= $denuncia["responsavel_email"] ?>"
            >Enviar e-mail</button>
            <form action="<?= URL_BASE ?>/denuncia/excluir" method="post" data-confirmar="Descartar as denúncias deste item? Ele continuará ativo na plataforma.">
                <input type="hidden" name="tipo" value="<?= $denuncia["tipo"] ?>">
                <input type="hidden" name="id" value="<?= $denuncia["alvo_id"] ?>">
                <button type="submit" class="rd-btn rd-btn-ghost rd-btn-sm w-full">Descartar</button>
            </form>
            <button
                type="button"
                class="rd-btn rd-btn-danger rd-btn-sm w-full"
                data-desativar
                data-tipo="<?= $denuncia["tipo"] ?>"
                data-id="<?= $denuncia["alvo_id"] ?>"
                data-nome="<?= $denuncia["alvo_nome"] ?>"
                data-responsavel="<?= $denuncia["responsavel_nome"] ?>"
            >Desativar</button>
        </div>

    </div>
</article>
<?php endif; ?>
