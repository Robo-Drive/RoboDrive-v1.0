<?php if(isset($projeto)):?>
<div class="p-8 col-span-10 row-span-12 border border-zinc-700 overflow-y-auto">
    <div class="w-full flex items-center justify-center h-[5dvh]">
        <h1 class="text-3xl font-bold text-white">
            <?= $projeto->getNome() ?>
        </h1>
    </div>
    <hr>
    <div class="mt-8 space-y-4">
        <div class="border border-[#00F5F5] p-4">
            <p class="text-zinc-400 text-sm">Descrição:</p>
            <p class="text-white text-lg">
                <?= $projeto->getDescricao() ?>
            </p>
        </div>
        <div class="border border-[#00F5F5] p-4 text-white">
            <p class="text-zinc-400 text-sm">Componentes:</p>
            <div class="w-full h-full flex gap-2 p-2">
                <?php foreach($projeto->getComponentes() as $componente): ?>
                    <?php include(__DIR__."/cardComponente.php");?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="border border-[#00F5F5] p-4 text-white">
            <p class="text-zinc-400 text-sm">Desenvolvedores:</p>
            <div class="w-full h-full flex gap-2 p-2">
                <?php if(isset($usuarios)): ?>
                    <?php foreach($usuarios as $usuario): ?>
                        <?php include(__DIR__."/cardUsuario.php");?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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