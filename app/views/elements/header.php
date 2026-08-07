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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen w-full bg-black font-['Space_Grotesk'] text-white overflow-x-hidden flex flex-col">
<header class="w-full">
    <div class="relative z-[10] w-full min-h-[50px] py-3 bg-transparent flex justify-center items-center">
        <h1 class="text-2xl sm:text-3xl font-bold font-['Orbitron'] text-[#FF1A1A]">Robo</h1>
        <h1 class="text-2xl sm:text-3xl font-bold font-['Orbitron'] text-white">Drive</h1>
    </div>
</header>