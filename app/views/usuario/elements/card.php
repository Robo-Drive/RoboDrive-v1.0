<?php if(isset($usuario)):?>
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
                <p class="mt-1 text-sm text-[#91B5BD]"><?= $usuario->getEmail() ?></p>
            </div>

            <?php if($usuario->getBiografia() != null):?>
                <div class="border-l-4 border-[#13F3F7] bg-[#082B3A] pl-4 text-left">
                    <p class="mb-1 text-[.6rem] font-bold uppercase tracking-[.2em] text-[#91B5BD]">Bio</p>
                    <p class="text-sm leading-relaxed text-[#F2FEFE]"><?= $usuario->getBiografia() ?></p>
                </div>
            <?php endif;?>
        </div>

    </div>
</div>
<?php endif;?>
