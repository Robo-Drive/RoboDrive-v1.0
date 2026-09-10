<?php if(isset($equipe)):?>
<div class="rd-card rd-card-accent w-full max-w-xl">
    <div class="flex flex-col items-center gap-2 text-center">
        <p class="rd-eyebrow">EQUIPE</p>
        <h1 class="rd-heading text-3xl"><?= $equipe->getNome() ?></h1>
    </div>

    <div class="mt-8">
        <div class="border-[3px] border-[#07556A] bg-[#082B3A] p-4">
            <p class="text-[.65rem] font-bold uppercase tracking-[.2em] text-[#91B5BD]">ID da equipe</p>
            <p class="mt-1 text-lg text-[#F2FEFE]">#<?= $equipe->getId() ?></p>
        </div>
    </div>

    <div class="mt-8 flex flex-wrap justify-center gap-4">
        <form action="<?= URL_BASE ?>/equipe/editar" method="post">
            <input type="hidden" name="id" value="<?= $equipe->getId() ?>">
            <button type="submit" class="rd-btn rd-btn-primary">Editar equipe</button>
        </form>
        <a href="<?= URL_BASE ?>/equipe" class="rd-btn rd-btn-secondary">Voltar</a>
    </div>
</div>
<?php endif;?>
