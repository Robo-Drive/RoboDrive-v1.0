<?php if(isset($_SESSION["usuario_logado"]) && $_SESSION["usuario_logado"]->getRegra() == "admin"): ?>

<dialog id="modalComunicado" aria-labelledby="modalComunicadoTitulo" class="rd-scroll-hidden m-auto max-h-[90dvh] w-[calc(100%-2rem)] max-w-2xl overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-0 text-[#F2FEFE] shadow-[8px_8px_0_#07556A] backdrop:bg-[#000505]/85">
    <div class="h-[3px] w-full bg-[#13F3F7]"></div>
    <form action="<?= URL_BASE ?>/usuario/comunicado" method="post" class="flex flex-col">
        <input type="hidden" name="id" data-campo="id">

        <div class="flex items-start justify-between gap-4 border-b-[3px] border-[#07556A] p-5 sm:p-6">
            <div>
                <p class="rd-eyebrow">Administração</p>
                <h2 id="modalComunicadoTitulo" class="rd-heading text-xl sm:text-2xl">ENVIAR <span>COMUNICADO</span></h2>
            </div>
            <button type="button" data-fechar-modal aria-label="Fechar" class="flex h-10 w-10 shrink-0 items-center justify-center border-[3px] border-[#07556A] transition-colors duration-200 hover:border-[#13F3F7]">
                <img src="<?= IMG_URL_BASE ?>/close-icon.png" alt="" class="h-4 w-4">
            </button>
        </div>

        <div class="flex flex-col gap-8 p-5 sm:p-6">
            <dl class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2 text-sm">
                <dt class="text-[.65rem] font-bold uppercase leading-5 tracking-[.15em] text-[#4E6B72]">Para</dt>
                <dd class="min-w-0 break-words"><span data-campo="nome" class="font-bold"></span> <span class="text-[#91B5BD]">&lt;<span data-campo="email"></span>&gt;</span></dd>
                <dt class="text-[.65rem] font-bold uppercase leading-5 tracking-[.15em] text-[#4E6B72]">Assunto</dt>
                <dd class="text-[#91B5BD]">[RoboDrive] Comunicado da administração</dd>
            </dl>

            <div class="rd-field">
                <label for="comunicadoMensagem" class="rd-label">Mensagem</label>
                <textarea id="comunicadoMensagem" name="mensagem" rows="6" required minlength="10" maxlength="2000" data-contar data-preview="comunicadoPreview" placeholder="Escreva aqui o conteúdo do comunicado..." class="rd-textarea"></textarea>
                <p class="mt-2 text-right text-[.65rem] font-bold tracking-[.1em] text-[#4E6B72]"><span data-contador>0</span>/2000</p>
            </div>

            <div>
                <p class="rd-eyebrow">Pré-visualização do e-mail</p>
                <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-4 text-sm leading-relaxed sm:p-5">
                    <p class="text-[#91B5BD]">Olá, <strong data-campo="nome" class="text-[#F2FEFE]"></strong>,</p>
                    <p id="comunicadoPreview" data-vazio="Sua mensagem aparecerá aqui." class="my-4 whitespace-pre-line break-words text-[#F2FEFE]"></p>
                    <div class="border-t border-[#07556A] pt-3 text-xs text-[#91B5BD]">
                        <p>Atenciosamente,<br><strong class="text-[#F2FEFE]">Administração RoboDrive</strong></p>
                        <p class="mt-2 text-[.7rem] text-[#4E6B72]">Este é um comunicado institucional da plataforma RoboDrive. Em caso de dúvidas, responda a este e-mail.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t-[3px] border-[#07556A] p-5 sm:flex-row sm:justify-end sm:p-6">
            <button type="button" data-fechar-modal class="rd-btn rd-btn-ghost">Cancelar</button>
            <button type="submit" class="rd-btn rd-btn-primary">Enviar comunicado</button>
        </div>
    </form>
</dialog>

<dialog id="modalDesativar" aria-labelledby="modalDesativarTitulo" class="rd-scroll-hidden m-auto max-h-[90dvh] w-[calc(100%-2rem)] max-w-2xl overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-0 text-[#F2FEFE] shadow-[8px_8px_0_#07556A] backdrop:bg-[#000505]/85">
    <div class="h-[3px] w-full bg-[#F2A058]"></div>
    <form method="post" data-rota-base="<?= URL_BASE ?>" class="flex flex-col">
        <input type="hidden" name="id" data-campo="id">

        <div class="flex items-start justify-between gap-4 border-b-[3px] border-[#07556A] p-5 sm:p-6">
            <div>
                <p class="rd-eyebrow">Administração</p>
                <h2 id="modalDesativarTitulo" class="rd-heading text-xl uppercase sm:text-2xl">DESATIVAR <span data-campo="rotulo" class="!text-[#F2A058]"></span></h2>
            </div>
            <button type="button" data-fechar-modal aria-label="Fechar" class="flex h-10 w-10 shrink-0 items-center justify-center border-[3px] border-[#07556A] transition-colors duration-200 hover:border-[#13F3F7]">
                <img src="<?= IMG_URL_BASE ?>/close-icon.png" alt="" class="h-4 w-4">
            </button>
        </div>

        <div class="flex flex-col gap-8 p-5 sm:p-6">
            <div class="border-[3px] border-[#07556A] bg-[#082B3A] p-4">
                <span data-campo="rotulo" class="rd-badge"></span>
                <p data-campo="nome" class="mt-3 line-clamp-2 break-words font-['Orbitron'] text-base font-black text-[#F2FEFE]"></p>
                <p class="mt-1 text-xs text-[#91B5BD]">Notificar: <span data-campo="destinatario" class="font-bold text-[#F2FEFE]"></span></p>
            </div>

            <p class="border-l-4 border-[#F2A058] pl-4 text-sm leading-relaxed text-[#91B5BD]">
                O conteúdo deixará de ficar disponível na plataforma. O responsável receberá um e-mail com a justificativa abaixo e poderá recorrer da decisão respondendo à mensagem.
            </p>

            <div class="rd-field">
                <label for="desativarJustificativa" class="rd-label">Justificativa</label>
                <textarea id="desativarJustificativa" name="justificativa" rows="5" required minlength="10" maxlength="2000" data-contar data-preview="desativarPreview" placeholder="Explique o motivo da desativação de forma clara, para que o usuário entenda o que ocorreu..." class="rd-textarea"></textarea>
                <p class="mt-2 text-right text-[.65rem] font-bold tracking-[.1em] text-[#4E6B72]"><span data-contador>0</span>/2000</p>
            </div>

            <div>
                <p class="rd-eyebrow">Pré-visualização do e-mail</p>
                <div class="border-2 border-dashed border-[#07556A] bg-[#000505] p-4 text-sm leading-relaxed sm:p-5">
                    <p class="mb-3 text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">Assunto: <span data-campo="assunto" class="normal-case tracking-normal text-[#91B5BD]"></span></p>
                    <p class="text-[#91B5BD]">Olá<span data-campo="saudacao" class="font-bold text-[#F2FEFE]"></span>,</p>
                    <p class="mt-3 text-[#91B5BD]">Informamos que <span data-campo="descricao"></span> pela administração do RoboDrive pelo seguinte motivo:</p>
                    <p id="desativarPreview" data-vazio="A justificativa aparecerá aqui." class="my-4 whitespace-pre-line break-words border-l-4 border-[#07556A] pl-3 text-[#F2FEFE]"></p>
                    <p class="text-[#91B5BD]">Caso discorde desta decisão, responda a este e-mail para solicitar a revisão.</p>
                    <div class="mt-4 border-t border-[#07556A] pt-3 text-xs text-[#91B5BD]">
                        <p>Atenciosamente,<br><strong class="text-[#F2FEFE]">Administração RoboDrive</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t-[3px] border-[#07556A] p-5 sm:flex-row sm:justify-end sm:p-6">
            <button type="button" data-fechar-modal class="rd-btn rd-btn-ghost">Cancelar</button>
            <button type="submit" class="rd-btn border-[#F2A058] bg-[#F2A058] text-[#000505] hover:border-[#F7BD8A] hover:bg-[#F7BD8A]">Desativar e notificar</button>
        </div>
    </form>
</dialog>

<script src="<?= JS_URL_BASE ?>/admin.js"></script>

<?php endif; ?>
