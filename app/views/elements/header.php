<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
    <link href="<?= css_url("style.css") ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= css_url("output.css") ?>">
    <link rel="stylesheet" href="<?= css_url("cropper.min.css") ?>">
    <link rel="icon" type="image/png" href="<?= IMG_URL_BASE ?>/robodrive-logo.png">
    <title><?= $titulo ?></title>
    <script src="<?= js_url("tailwind.js") ?>"></script>
</head>
<body class="h-screen w-screen bg-[#000505] font-['Space_Grotesk']">
<header class="relative flex h-[10dvh] w-full items-center justify-between border-b-[3px] border-[#07556A] bg-[#000505] px-4 sm:px-8">
    <button
        type="button"
        id="botaoMenu"
        aria-label="Abrir menu"
        aria-expanded="false"
        aria-controls="sidebar"
        class="rd-btn-icon rd-burger peer relative hidden shrink-0 items-center justify-center lg:!hidden"
    >
        <span class="rd-burger-bar rd-burger-bar-top"></span>
        <span class="rd-burger-bar rd-burger-bar-mid"></span>
        <span class="rd-burger-bar rd-burger-bar-bottom"></span>
    </button>
        <?php
        $uriAtual = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

        $rotasBloqueadas = [
            '/usuario/perfil',
            '/'
        ];

        $palavrasPermitidas = [
            'cadastro',
            'create',
            'edit',
            'editar',
            'entrar',
            'recuperar',
            'redefinir'
        ];

        $mostrarBotaoVoltar = false;

        if (!in_array($uriAtual, $rotasBloqueadas, true)) {
            foreach ($palavrasPermitidas as $termo) {
                if (str_contains($uriAtual, $termo)) {
                    $mostrarBotaoVoltar = true;
                    break;
                }
            }
        }
    ?>

    <div class="flex flex-wrap items-center gap-3">
        <?php if ($mostrarBotaoVoltar): ?>
            <!-- BOTÃO VOLTAR ESTILIZADO ROBODRIVE -->
            <button 
                type="button" 
                onclick="window.history.length > 1 ? window.history.back() : window.location.href='<?= URL_BASE ?>'" 
                title="Voltar para a página anterior"
                aria-label="Voltar"
                class="rd-btn rd-btn-ghost rd-btn-sm h-11 w-11 !px-0 sm:h-auto sm:w-auto sm:!px-4"
            >
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true"><polyline points="15 5 8 12 15 19"/></svg>
                <span class="hidden sm:inline">Voltar</span>
            </button>
        <?php endif; ?>

        <a href="<?= URL_BASE ?>" class="flex items-center gap-0 no-underline">
            <h1 class="font-['Orbitron'] text-2xl font-black text-[#13F3F7] sm:text-3xl">Robo</h1>
            <h1 class="font-['Orbitron'] text-2xl font-black text-[#F2FEFE] sm:text-3xl">Drive</h1>
        </a>
    </div>
    <?php if(isset($_SESSION["usuario_logado"])): ?>
        <a href="<?= URL_BASE ?>/usuario/perfil" class="flex items-center gap-3 border-[3px] border-[#07556A] px-3 py-1.5 no-underline transition-colors duration-200 hover:border-[#13F3F7]">
            <img
                src="<?= $_SESSION["usuario_logado"]->getImagem() ? URL_BASE."/arquivo?arquivo=".$_SESSION["usuario_logado"]->getImagem() : IMG_URL_BASE."/perfil.png" ?>"
                alt="Foto de perfil"
                class="aspect-square h-7 w-7 border-2 border-[#13F3F7] object-cover shadow-[0_0_10px_rgba(19,243,247,.35)]"
            >
            <span class="hidden text-xs font-bold uppercase tracking-[.08em] text-[#F2FEFE] sm:inline"><?= texto($_SESSION["usuario_logado"]->getNomeUsuario()) ?></span>
        </a>
    <?php endif; ?>
</header>
    <!--npm init -y
npm install -D tailwindcss @tailwindcss/clix-->