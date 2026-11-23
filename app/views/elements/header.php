<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
    <link href="<?= CSS_URL_BASE ?>/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL_BASE ?>/output.css">
    <link rel="stylesheet" href="<?= CSS_URL_BASE ?>/cropper.min.css">
    <link rel="icon" type="image/png" href="<?= IMG_URL_BASE ?>/robodrive-logo.png">
    <title><?= $titulo ?></title>
    <script src="<?= JS_URL_BASE ?>/tailwind.js"></script>
</head>
<body class="h-screen w-screen bg-[#000505] font-['Space_Grotesk']">
<header class="relative flex h-[10dvh] w-full items-center justify-between border-b-[3px] border-[#07556A] bg-[#000505] px-4 sm:px-8">
    <button
        type="button"
        id="botaoMenu"
        aria-label="Abrir menu"
        aria-expanded="false"
        aria-controls="sidebar"
        class="peer group relative hidden h-11 w-11 shrink-0 items-center justify-center border-[3px] border-[#07556A] transition-colors duration-200 hover:border-[#13F3F7] aria-expanded:border-[#13F3F7] lg:!hidden"
    >
        <span class="absolute h-[3px] w-5 -translate-y-[7px] bg-[#F2FEFE] transition-transform duration-200 ease-out group-hover:bg-[#13F3F7] group-aria-expanded:translate-y-0 group-aria-expanded:rotate-45 group-aria-expanded:bg-[#13F3F7]"></span>
        <span class="absolute h-[3px] w-5 bg-[#F2FEFE] transition-opacity duration-150 group-hover:bg-[#13F3F7] group-aria-expanded:opacity-0"></span>
        <span class="absolute h-[3px] w-5 translate-y-[7px] bg-[#F2FEFE] transition-transform duration-200 ease-out group-hover:bg-[#13F3F7] group-aria-expanded:translate-y-0 group-aria-expanded:-rotate-45 group-aria-expanded:bg-[#13F3F7]"></span>
    </button>
        <?php
        // Identifica a rota atual sem os parâmetros de query string (?id=...)
        $uriAtual = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

        // Rotas onde o botão NUNCA deve aparecer
        $rotasBloqueadas = [
            '/usuario/perfil',
            '/'
        ];

        // Palavras-chave permitidas (perfis e formulários)
        $palavrasPermitidas = [
            'perfil',
            'cadastro',
            'create',
            'edit',
            'editar',
            'entrar',
            'recuperar',
            'redefinir'
        ];

        $mostrarBotaoVoltar = false;

        // Se NÃO estiver na lista bloqueada, verifica se é perfil ou formulário
        if (!in_array($uriAtual, $rotasBloqueadas, true)) {
            foreach ($palavrasPermitidas as $termo) {
                if (str_contains($uriAtual, $termo)) {
                    $mostrarBotaoVoltar = true;
                    break;
                }
            }
        }
    ?>

    <div class="flex items-center gap-3">
        <?php if ($mostrarBotaoVoltar): ?>
            <!-- BOTÃO VOLTAR ESTILIZADO ROBODRIVE -->
            <button 
                type="button" 
                onclick="window.history.length > 1 ? window.history.back() : window.location.href='<?= URL_BASE ?>'" 
                title="Voltar para a página anterior"
                aria-label="Voltar"
                class="group flex h-9 items-center gap-1.5 border-2 border-[#07556A] bg-[#000505] px-3 font-['Orbitron'] text-[.7rem] font-bold uppercase tracking-wider text-[#91B5BD] transition-all duration-200 hover:-translate-y-0.5 hover:border-[#13F3F7] hover:bg-[#07556A]/20 hover:text-[#13F3F7] cursor-pointer"
            >
                <span class="text-sm transition-transform duration-200 group-hover:-translate-x-1">‹</span>
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
                class="aspect-square h-7 w-7 rounded-full border-2 border-[#13F3F7] object-cover shadow-[0_0_10px_rgba(19,243,247,.35)]"
            >
            <span class="hidden text-xs font-bold uppercase tracking-[.08em] text-[#F2FEFE] sm:inline"><?= $_SESSION["usuario_logado"]->getNomeUsuario() ?></span>
        </a>
    <?php endif; ?>
</header>
    <!--npm init -y
npm install -D tailwindcss @tailwindcss/clix-->