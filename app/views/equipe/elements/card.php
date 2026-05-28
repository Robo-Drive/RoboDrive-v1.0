<?php if(isset($equipe)):?>
<div class="bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-xl border border-zinc-700">
    <div class="flex flex-col items-center">

        <h1 class="mt-4 text-3xl font-bold text-white">
            <?= $equipe->getNome() ?>
        </h1>

    </div>

    <div class="mt-8 space-y-4">


        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">ID do equipe</p>
            <p class="text-white text-lg">
                #<?= $equipe->getId() ?>
            </p>
        </div>

    </div>

    <div class="mt-8 flex justify-center gap-4">
        <form action="<?= URL_BASE ?>/equipe/editar" method="post" class="w-full h-full">
            <input  class="w-full h-full" type="hidden" name="id" value="<?= $equipe->getId() ?>">
            <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                Editar equipe
            </button>
        </form>

        <a href="<?= URL_BASE ?>/equipe/listar"
           class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2 rounded-xl transition">
            Voltar
        </a>
    </div>
</div>
<?php endif;?>