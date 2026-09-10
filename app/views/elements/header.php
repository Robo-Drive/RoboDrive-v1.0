<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
    <link href="<?= CSS_URL_BASE ?>/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= IMG_URL_BASE ?>/robodrive-logo.png">
    <title><?= $titulo ?></title>
    <script src="<?= JS_URL_BASE ?>/tailwind.js"></script>
</head>
<body class="h-screen w-screen bg-[#000505] font-['Space_Grotesk']">
<header class="flex h-[10dvh] w-full items-center justify-between border-b-[3px] border-[#07556A] bg-[#000505] px-4 sm:px-8">
    <a href="<?= URL_BASE ?>" class="flex items-center gap-0 no-underline">
        <h1 class="font-['Orbitron'] text-2xl font-black text-[#13F3F7] sm:text-3xl">Robo</h1>
        <h1 class="font-['Orbitron'] text-2xl font-black text-[#F2FEFE] sm:text-3xl">Drive</h1>
    </a>

    <?php if(isset($_SESSION["usuario_logado"])): ?>
        <a href="<?= URL_BASE ?>/usuario/perfil" class="flex items-center gap-3 border-[3px] border-[#07556A] px-3 py-1.5 no-underline transition-colors duration-200 hover:border-[#13F3F7]">
            <img
                src="<?= $_SESSION["usuario_logado"]->getImagem() ? URL_BASE."/arquivo?arquivo=".$_SESSION["usuario_logado"]->getImagem() : IMG_URL_BASE."/perfil.png" ?>"
                alt="Foto de perfil"
                class="h-7 w-7 object-cover"
            >
            <span class="hidden text-xs font-bold uppercase tracking-[.08em] text-[#F2FEFE] sm:inline"><?= $_SESSION["usuario_logado"]->getNomeUsuario() ?></span>
        </a>
    <?php endif; ?>
</header>
