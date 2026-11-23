<div id="modalRecorte" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#000505]/90 p-4">
    <div id="dialogRecorte" role="dialog" aria-modal="true" aria-labelledby="tituloRecorte" tabindex="-1" class="flex max-h-full w-full max-w-lg flex-col gap-4 overflow-y-auto border-[3px] border-[#07556A] bg-[#06141C] p-4 focus:outline-none sm:p-6 [&_.cropper-view-box]:outline-[#13F3F7] [&_.cropper-point]:bg-[#13F3F7] [&_.cropper-line]:bg-[#13F3F7]">
        <div class="space-y-1">
            <h2 id="tituloRecorte" class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">Recortar imagem</h2>
            <p id="dicaRecorte" class="text-xs text-[#91B5BD]"></p>
        </div>
        <div class="h-72 w-full overflow-hidden bg-[#000505] sm:h-96">
            <img id="imgRecorte" alt="Pré-visualização do recorte" class="block max-w-full">
        </div>
        <input id="zoomRecorte" type="range" aria-label="Zoom" min="0" max="1" step="0.01" value="0" class="h-11 w-full accent-[#13F3F7] focus-visible:outline-2 focus-visible:outline-[#54FBFE]">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" id="btnDescartarTodasRecorte" class="rd-btn rd-btn-ghost min-h-11" style="display:none;">Descartar todas</button>
            <button type="button" id="btnDescartarRecorte" class="rd-btn rd-btn-ghost min-h-11">Descartar</button>
            <button type="button" id="btnUsarRecorte" class="rd-btn rd-btn-primary min-h-11">Usar foto</button>
        </div>
    </div>
</div>
