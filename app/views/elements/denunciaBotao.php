<?php
$GLOBALS["denunciaBotoesExibidos"] ??= [];
$denunciaChave = isset($denunciaAlvo) ? $denunciaAlvo["tipo"] . ":" . $denunciaAlvo["id"] : null;
$denunciaJaExibido = $denunciaChave !== null && isset($GLOBALS["denunciaBotoesExibidos"][$denunciaChave]);

if(isset($denunciaAlvo) && !$denunciaJaExibido && isset($_SESSION["usuario_logado"]) && !in_array($_SESSION["usuario_logado"]->getId(), $denunciaAlvo["autores"] ?? [])):
$GLOBALS["denunciaBotoesExibidos"][$denunciaChave] = true;
?>
<div class="<?= $denunciaAlvo["classe"] ?? "" ?>">
    <button
        type="button"
        aria-label="Denunciar"
        class="rd-btn-icon group relative"
        data-denunciar
        data-tipo="<?= $denunciaAlvo["tipo"] ?>"
        data-id="<?= $denunciaAlvo["id"] ?>"
        data-nome="<?= texto($denunciaAlvo["nome"]) ?>"
    >
        <img src="<?= IMG_URL_BASE ?>/denuncia-icon.svg" alt="" class="h-5 w-5 opacity-70 transition-opacity duration-200 group-hover:opacity-100 group-focus-visible:opacity-100">
        <span aria-hidden="true" class="pointer-events-none absolute right-0 top-full z-40 mt-1 whitespace-nowrap border-2 border-[#F0ED06] bg-[#000505] px-2 py-1 text-[.6rem] font-bold uppercase tracking-[.12em] text-[#F0ED06] opacity-0 transition-opacity duration-150 group-hover:opacity-100 group-hover:delay-300 group-focus-visible:opacity-100">Denunciar</span>
    </button>
</div>
<?php endif; ?>
