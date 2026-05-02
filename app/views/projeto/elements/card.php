<?php if(isset($projeto)):?>
<div class="bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-xl border border-zinc-700">
    <div class="flex flex-col items-center">

        <h1 class="mt-4 text-3xl font-bold text-white">
            <?= $projeto->getNome() ?>
        </h1>

        <span class="mt-2 px-4 py-1 bg-blue-600 text-white rounded-full text-sm">
            <?= ucfirst($projeto->getVisibilidade()) ?>
        </span>
    </div>

    <div class="mt-8 space-y-4">


        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">ID do projeto</p>
            <p class="text-white text-lg">
                #<?= $projeto->getId() ?>
            </p>
        </div>

        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">Tipo de visibilidade
            </p>
            <p class="text-white text-lg">
                <?= ucfirst($projeto->getVisibilidade()) ?>
            </p>
        </div>

    </div>

    <div class="mt-8 flex justify-center gap-4">
        <form action="<?= URL_BASE ?>/projeto/editar" method="post" class="w-full h-full">
            <input  class="w-full h-full" type="hidden" name="id" value="<?= $projeto->getId() ?>">
            <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                Editar projeto
            </button>
        </form>

        <a href="<?= URL_BASE ?>/projeto/listar"
           class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2 rounded-xl transition">
            Voltar
        </a>
    </div>
</div>
<?php endif;?>