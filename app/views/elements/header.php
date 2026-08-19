<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(file_exists(__DIR__."css/style.css")):?>
        <link rel="stylesheet" href="css/style.css">
    <?php endif;?>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="<?= CSS_URL_BASE ?>/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= IMG_URL_BASE ?>/robodrive-logo.png">
    <title><?= $titulo ?></title>
    <script src="<?= JS_URL_BASE ?>/tailwind.js"></script>
</head>
<body class="w-screen h-screen">
<header>
    <div class="w-full h-[10dvh] bg-black flex justify-center items-center">
        <h1 class="text-3xl font-bold font-['Orbitron'] text-[#00F5F5]">Robo</h1>
        <h1 class="text-3xl font-bold font-['Orbitron'] text-white">Drive</h1>
    </div>
    
</header>