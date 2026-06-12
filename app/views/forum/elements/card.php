<?php if(isset($forum)):?>
<div class="bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-xl border border-zinc-700">
    <div class="flex flex-col items-center">

        <h1 class="mt-4 text-3xl font-bold text-white">
            <?= $forum->getConteudo() ?>
        </h1>

    </div>

    <div class="mt-8 space-y-4">


        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">ID do Forum</p>
            <p class="text-white text-lg">
                #<?= $forum->getId() ?>
            </p>
        </div>

    </div>

    <div class="mt-8 flex justify-center gap-4">
        <form action="<?= URL_BASE ?>/forum/editar" method="post" class="w-full h-full">
            <input  class="w-full h-full" type="hidden" name="id" value="<?= $forum->getId() ?>">
            <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                Editar forum
            </button>
        </form>

        <a href="<?= URL_BASE ?>/forum/listar"
           class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2 rounded-xl transition">
            Voltar
        </a>
    </div>
</div>
<?php endif;?>