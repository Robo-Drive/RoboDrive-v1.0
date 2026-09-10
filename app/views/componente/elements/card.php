<?php if (isset($componente)): ?>
<div class="bg-[#082B3A] relative aspect-video w-full overflow-hidden">
    <div class="absolute inset-0 z-20">
        <a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="absolute inset-0"></a>
    </div>

    <div class="absolute inset-0 z-0">
        <img src="<?= URL_BASE . "/arquivo?arquivo=" . $componente->getImagem() ?>" alt="<?= $componente->getNome() ?>" class="h-full w-full border-[3px] border-[#07556A] object-cover opacity-20">
    </div>

    <div class="relative z-10 flex h-full w-full flex-col justify-between p-4">
        <h3 class="truncate text-left font-['Orbitron'] text-lg font-black text-[#F2FEFE]"><?= $componente->getNome() ?></h3>
        <p class="line-clamp-3 text-sm text-[#91B5BD]"><?= $componente->getDescricao() ?></p>
        <p class="text-right text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">Cadastrado por @<?= $componente->getUsuario()->getNomeUsuario() ?></p>
    </div>
</div>
<?php endif; ?>