<?php if (isset($componente)): ?>
<div class="bg-[#082B3A] relative aspect-video w-full overflow-hidden">
    <div class="absolute inset-0 z-20">
        <a href="<?= URL_BASE ?>/componente/perfil?id=<?= $componente->getId() ?>" class="absolute inset-0"></a>
    </div>

    <div class="absolute inset-0 z-0">
        <img src="<?= URL_BASE . "/arquivo?arquivo=" . $componente->getImagem() ?>" alt="<?= texto($componente->getNome()) ?>" class="h-full w-full border-[3px] border-[#07556A] object-cover opacity-20">
    </div>

    <div class="relative z-10 flex h-full w-full flex-col justify-between p-4">
        <h3 class="truncate pr-10 text-left font-['Orbitron'] text-lg font-black text-[#F2FEFE]"><?= texto($componente->getNome()) ?></h3>
        <p class="line-clamp-3 text-sm text-[#91B5BD]"><?= texto($componente->getDescricao()) ?></p>
        <p class="text-right text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]">Cadastrado por @<?= texto($componente->getUsuario()->getNomeUsuario()) ?></p>
    </div>

    <?php
    $denunciaAlvo = [
        "tipo" => "componente",
        "id" => $componente->getId(),
        "nome" => $componente->getNome(),
        "autores" => [$componente->getUsuario()->getId()],
        "classe" => "absolute right-1.5 top-1.5 z-30"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>

    <?php if ($componente->isStatus() === false): ?>
        <div class="absolute bottom-1.5 left-1.5 z-30 flex items-center gap-2">
            <span class="rd-badge rd-badge-private">Desativado</span>
            <?php
            $ativarAlvo = ["tipo" => "componente", "id" => (int) $componente->getId(), "ativo" => false];
            include(__DIR__."/../../elements/ativarBotao.php");
            ?>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
