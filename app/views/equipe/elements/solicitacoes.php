<?php
// Solicitações pendentes da equipe. Usada pela página da equipe e pela atualização automática.
?>
        <?php if (!empty($solicitacoesPendentes)): ?>
            <div class="border-2 border-[#F0ED06] bg-[#06141C] p-4 space-y-3">
                <h3 class="text-xs font-black uppercase text-[#F0ED06] font-['Orbitron']">
                    Solicitações de Entrada Pendentes (<?= count($solicitacoesPendentes) ?>)
                </h3>
                <div class="divide-y divide-[#07556A]">
                    <?php foreach ($solicitacoesPendentes as $pendente): ?>
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-[#F2FEFE]"><?= texto($pendente['nome']) ?></p>
                                <p class="text-[.65rem] text-[#91B5BD]">@<?= texto($pendente['nome_usuario']) ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="<?= URL_BASE ?>/equipe/aprovar-solicitacao" method="post">
                                    <input type="hidden" name="equipe_id" value="<?= (int)$equipe->getId() ?>">
                                    <input type="hidden" name="usuario_id" value="<?= $pendente['id'] ?>">
                                    <button type="submit" class="rd-btn rd-btn-primary rd-btn-sm">Aceitar</button>
                                </form>
                                <form action="<?= URL_BASE ?>/equipe/rejeitar-solicitacao" method="post">
                                    <input type="hidden" name="equipe_id" value="<?= (int)$equipe->getId() ?>">
                                    <input type="hidden" name="usuario_id" value="<?= $pendente['id'] ?>">
                                    <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">Recusar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
