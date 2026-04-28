<?php
$titulo = "Perfil do usuario";
if(isset($usuario)):
$header = $usuario->getNome();
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full flex flex-col justify-center items-center">
    <div class="bg-zinc-900 rounded-2xl shadow-2xl p-8 w-full max-w-xl border border-zinc-700">

    <div class="flex flex-col items-center">
        <img 
            src="<?= $usuario->getImagem() ?>" 
            alt="Foto de perfil"
            class="w-32 h-32 rounded-xl object-contain border-4 border-blue-500 shadow-lg"
        >

        <h1 class="mt-4 text-3xl font-bold text-white">
            <?= $usuario->getNome() ?>
        </h1>

        <span class="mt-2 px-4 py-1 bg-blue-600 text-white rounded-full text-sm">
            <?= ucfirst($usuario->getRegra()) ?>
        </span>
    </div>

    <div class="mt-8 space-y-4">

        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">Email</p>
            <p class="text-white text-lg">
                <?= $usuario->getEmail() ?>
            </p>
        </div>

        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">ID do usuário</p>
            <p class="text-white text-lg">
                #<?= $usuario->getId() ?>
            </p>
        </div>

        <div class="bg-zinc-800 rounded-xl p-4">
            <p class="text-zinc-400 text-sm">Tipo de acesso</p>
            <p class="text-white text-lg">
                <?= ucfirst($usuario->getRegra()) ?>
            </p>
        </div>

    </div>

    <div class="mt-8 flex justify-center gap-4">
        <form action="<?= URL_BASE ?>/usuario/editar" method="post" class="w-full h-full">
            <input  class="w-full h-full" type="hidden" name="id" value="<?= $usuario->getId() ?>">
            <button type=submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                Editar Perfil
            </button>
        </form>

        <a href="<?= URL_BASE ?>/usuario/listar"
           class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2 rounded-xl transition">
            Voltar
        </a>
    </div>

</div>
</div>
<?php
endif;
$marquee = "Cadastro de usuários do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");