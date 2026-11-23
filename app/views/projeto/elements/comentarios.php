<?php
// Lista de comentários do projeto. Usada pela página do projeto e pela atualização automática.
?>
            <?php if (!empty($comentarios)): ?>
                <?php foreach ($comentarios as $coment): ?>
                    <div class="border border-[#07556A] bg-[#06141C] p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-wrap items-center gap-3">
                                <img 
                                    src="<?= $coment->getUsuario()->getImagem() ? URL_BASE . '/arquivo?arquivo=' . urlencode($coment->getUsuario()->getImagem()) : IMG_URL_BASE . '/perfil.png' ?>" 
                                    alt="Foto" 
                                    class="w-8 h-8 object-cover border-2 border-[#13F3F7] shadow-[0_0_10px_rgba(19,243,247,.35)]"
                                >
                                <div>
                                    <p class="text-xs font-bold text-[#F2FEFE]">
                                        @<?= texto($coment->getUsuario()->getNomeUsuario()) ?>
                                    </p>
                                    <span class="text-[.6rem] text-[#91B5BD] font-mono">
                                        <?= $coment->getCriadoEm() ? $coment->getCriadoEm()->format('d/m/Y H:i') : '' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <?php if ($coment->getUsuario()->getId() === $usuarioLogadoId || $eAdmin): ?>
                                    <?php if ($eAdmin && $coment->getUsuario()->getId() !== $usuarioLogadoId): ?>
                                        <button
                                            type="button"
                                            title="desativar"
                                            class="rd-btn rd-btn-danger rd-btn-sm"
                                            data-desativar
                                            data-tipo="comentario"
                                            data-origem="projeto"
                                            data-id="<?= $coment->getId() ?>"
                                            data-nome="<?= texto(mb_strimwidth(html_entity_decode($coment->getConteudo()), 0, 50, '...')) ?>"
                                            data-responsavel="@<?= texto($coment->getUsuario()->getNomeUsuario()) ?>"
                                        >
                                            desativar
                                        </button>
                                    <?php else: ?>
                                        <form action="<?= URL_BASE ?>/comentario/desativar" method="post" data-confirmar="Deseja desativar este comentário?" >
                                            <input type="hidden" name="id" value="<?= $coment->getId() ?>">
                                            <input type="hidden" name="tipo" value="projeto">
                                            <button type="submit" title="desativar" class="rd-btn rd-btn-danger rd-btn-sm">
                                                desativar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php
                                // Botão de denúncia do comentário
                                $denunciaAlvo = [
                                    "tipo"    => "comentario",
                                    "id"      => $coment->getId(),
                                    "nome"    => mb_strimwidth($coment->getConteudo(), 0, 50, '...'),
                                    "autores" => [$coment->getUsuario()->getId()],
                                    "classe"  => "shrink-0"
                                ];
                                include(__DIR__ . "/../../elements/denunciaBotao.php");
                                ?>
                            </div>
                        </div>

                        <p class="text-sm text-[#F2FEFE] leading-relaxed whitespace-pre-line pl-11">
                            <?= texto($coment->getConteudo()) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-xs text-[#91B5BD] text-center py-4">Nenhum comentário neste projeto ainda.</p>
            <?php endif; ?>
