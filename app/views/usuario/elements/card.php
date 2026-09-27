<?php if(isset($usuario)):
$visaoAdmin = $_SESSION["usuario_logado"]->getRegra() == "admin" && $usuario->getId() != $_SESSION["usuario_logado"]->getId();
?>
<div class="rd-card rd-card-accent">
    <div class="flex flex-col items-center gap-8 md:flex-row md:items-start">

        <div class="flex shrink-0 flex-col items-center gap-4">
            <img
                src="<?= $usuario->getImagem() ? URL_BASE."/arquivo?arquivo=".$usuario->getImagem() : IMG_URL_BASE."/perfil.png" ?>"
                alt="Foto de perfil"
                class="h-32 w-32 border-[3px] border-[#13F3F7] object-cover shadow-[0_0_24px_rgba(19,243,247,.35)] sm:h-40 sm:w-40"
            >
            <div class="text-center">
                <p class="rd-eyebrow">USUÁRIO</p>
                <p class="font-['Orbitron'] text-lg font-black text-[#13F3F7]">@<?= $usuario->getNomeUsuario() ?></p>
            </div>
        </div>

        <div class="flex w-full flex-1 flex-col gap-5 text-center md:text-left">
            <div>
                <h2 class="font-['Orbitron'] text-2xl font-black tracking-[-.02em] text-[#F2FEFE] sm:text-3xl"><?= $usuario->getNome() ?></h2>
                <?php if($visaoAdmin): ?>
                    <button
                        type="button"
                        title="Enviar comunicado por e-mail"
                        class="mt-1 inline-flex items-center gap-2 text-sm text-[#91B5BD] underline decoration-[#07556A] decoration-dashed decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#13F3F7] hover:decoration-[#13F3F7]"
                        data-comunicado
                        data-id="<?= $usuario->getId() ?>"
                        data-nome="<?= $usuario->getNome() ?>"
                        data-email="<?= $usuario->getEmail() ?>"
                    >
                        <?= $usuario->getEmail() ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" class="h-4 w-4 shrink-0" aria-hidden="true"><path d="M3 5h18v14H3z"/><path d="m3 6 9 7 9-7"/></svg>
                    </button>
                <?php else: ?>
                    <p class="mt-1 text-sm text-[#91B5BD]"><?= $usuario->getEmail() ?></p>
                <?php endif; ?>
            </div>

            <?php if($usuario->getBiografia() != null):?>
                <div class="border-l-4 border-[#13F3F7] bg-[#082B3A] pl-4 text-left">
                    <p class="mb-1 text-[.6rem] font-bold uppercase tracking-[.2em] text-[#91B5BD]">Bio</p>
                    <p class="text-sm leading-relaxed text-[#F2FEFE]"><?= $usuario->getBiografia() ?></p>
                </div>
            <?php endif;?>
        </div>

    </div>

    <?php if($visaoAdmin): ?>
        <div class="mt-8 flex flex-col gap-5 border-t-[3px] border-[#07556A] pt-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col items-center gap-3 md:items-start">
                <p class="rd-eyebrow mb-0">Visão de administrador</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="rd-badge <?= $usuario->getRegra() == 'admin' ? 'rd-badge-public' : 'rd-badge-team' ?>"><?= ucfirst($usuario->getRegra()) ?></span>
                    <span class="rd-badge <?= $usuario->isStatus() === false ? 'rd-badge-private' : 'rd-badge-team' ?>"><?= $usuario->isStatus() === false ? "Desativado" : "Ativo" ?></span>
                    <?php if($usuario->getCriadoEm()): ?>
                        <span class="rd-badge">Desde <?= $usuario->getCriadoEm()->format("d/m/Y") ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                <button
                    type="button"
                    class="rd-btn rd-btn-secondary rd-btn-sm"
                    data-comunicado
                    data-id="<?= $usuario->getId() ?>"
                    data-nome="<?= $usuario->getNome() ?>"
                    data-email="<?= $usuario->getEmail() ?>"
                >Enviar comunicado</button>
                <button
                    type="button"
                    class="rd-btn rd-btn-danger rd-btn-sm"
                    data-desativar
                    data-tipo="usuario"
                    data-id="<?= $usuario->getId() ?>"
                    data-nome="<?= $usuario->getNome() ?> (@<?= $usuario->getNomeUsuario() ?>)"
                    data-responsavel="<?= $usuario->getNome() ?>"
                >Desativar conta</button>
            </div>
        </div>
    <?php endif; ?>

    <?php
    $denunciaAlvo = [
        "tipo" => "usuario",
        "id" => $usuario->getId(),
        "nome" => $usuario->getNome()." (@".$usuario->getNomeUsuario().")",
        "autores" => [$usuario->getId()],
        "classe" => "absolute right-2 top-2 sm:right-3 sm:top-3"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif;?>
