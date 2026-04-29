<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
</head>
<body class="w-screen h-screen">
<header>
    <div class="w-full h-[<?= isset($menu) ? 8 : 10 ?>dvh] bg-black flex justify-center items-center">
        <h1 class="text-white text-3xl font-bold"><?= $header ?></h1>
        <?php if(isset($_SESSION["usuario_logado"])): ?>
            <form action="<?= URL_BASE ?>/logout" method="post" class="absolute right-6">
                <button type="submit" class="bg-red-700 px-3 py-2 rounded-2xl houver:text-white">Sair</button>
            </form>
        <?php endif; ?>
    </div>
    <?php if(isset($menu)): ?>
        <div class="w-full h-[2dvh] bg-gray-700 flex justify-evenly items-center">
            <?php foreach($menu as $i): ?>
                <a href="<?= $i["rota"] ?>" class="text-white"><?= $i["nome"] ?></a>
            <?php endforeach;?>
        </div>
    <?php endif; ?>
</header>