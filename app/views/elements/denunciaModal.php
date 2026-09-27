<?php if(isset($_SESSION["usuario_logado"])): ?>

<dialog id="modalDenunciar" aria-labelledby="modalDenunciarTitulo" class="rd-scroll-hidden m-auto max-h-[90dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-0 text-[#F2FEFE] shadow-[8px_8px_0_#07556A] backdrop:bg-[#000505]/85">
    <div class="h-[3px] w-full bg-[#F0ED06]"></div>
    <form action="<?= URL_BASE ?>/denuncia/salvar" method="post" class="flex flex-col">
        <input type="hidden" name="tipo" data-campo="tipo">
        <input type="hidden" name="id" data-campo="id">

        <div class="flex items-start justify-between gap-4 border-b-[3px] border-[#07556A] p-5 sm:p-6">
            <div>
                <p class="rd-eyebrow">Denúncia</p>
                <h2 id="modalDenunciarTitulo" class="rd-heading text-xl uppercase sm:text-2xl">DENUNCIAR <span data-campo="rotulo"></span></h2>
            </div>
            <button type="button" data-fechar-modal aria-label="Fechar" class="flex h-10 w-10 shrink-0 items-center justify-center border-[3px] border-[#07556A] transition-colors duration-200 hover:border-[#13F3F7]">
                <img src="<?= IMG_URL_BASE ?>/close-icon.png" alt="" class="h-4 w-4">
            </button>
        </div>

        <div class="flex flex-col gap-8 p-5 sm:p-6">
            <div class="border-[3px] border-[#07556A] bg-[#082B3A] p-4">
                <span data-campo="rotulo" class="rd-badge"></span>
                <p data-campo="nome" class="mt-3 line-clamp-2 break-words font-['Orbitron'] text-base font-black text-[#F2FEFE]"></p>
            </div>

            <div class="rd-field">
                <label for="denunciaMotivo" class="rd-label">Motivo</label>
                <select id="denunciaMotivo" name="motivo" required class="rd-select">
                    <option value="">Selecione o motivo</option>
                    <option value="Spam">Spam</option>
                    <option value="Conteúdo ofensivo">Conteúdo ofensivo</option>
                    <option value="Plágio">Plágio</option>
                    <option value="Informação falsa ou enganosa">Informação falsa ou enganosa</option>
                    <option value="Conteúdo impróprio">Conteúdo impróprio</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <div class="rd-field">
                <label for="denunciaDescricao" class="rd-label">Detalhes (opcional)</label>
                <textarea id="denunciaDescricao" name="descricao" rows="4" maxlength="2000" placeholder="Conte o que aconteceu para ajudar a administração a analisar..." class="rd-textarea"></textarea>
                <p class="mt-2 text-right text-[.65rem] font-bold tracking-[.1em] text-[#4E6B72]"><span data-contador>0</span>/2000</p>
            </div>

            <p class="border-l-4 border-[#F0ED06] pl-4 text-sm leading-relaxed text-[#91B5BD]">
                Sua denúncia será analisada pela administração do RoboDrive. Se confirmada, o conteúdo poderá ser desativado.
            </p>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t-[3px] border-[#07556A] p-5 sm:flex-row sm:justify-end sm:p-6">
            <button type="button" data-fechar-modal class="rd-btn rd-btn-ghost">Cancelar</button>
            <button type="submit" class="rd-btn rd-btn-primary">Enviar denúncia</button>
        </div>
    </form>
</dialog>

<script src="<?= JS_URL_BASE ?>/denunciar.js"></script>

<?php endif; ?>
