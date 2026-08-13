<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@400;700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= IMG_URL_BASE ?>/robodrive-logo.png">
    <title><?= $titulo ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative min-h-screen w-full overflow-x-hidden flex flex-col bg-[#000505] font-['Space_Grotesk'] text-[#F2FEFE] selection:bg-[rgba(19,243,247,.32)] selection:text-[#000505] before:content-[''] before:fixed before:inset-0 before:pointer-events-none before:-z-10 before:bg-[radial-gradient(circle_at_top_left,rgba(19,243,247,.14),transparent_34%),radial-gradient(circle_at_top_right,rgba(240,237,6,.08),transparent_26%),linear-gradient(180deg,#000505_0%,#06141C_100%)] after:content-[''] after:fixed after:inset-0 after:pointer-events-none after:-z-10 after:bg-[linear-gradient(180deg,rgba(0,5,5,.12),rgba(0,5,5,.5)_42%,rgba(0,5,5,.78))] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-[#000505] [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-[linear-gradient(180deg,#07556A,#13F3F7)] [&_.bg-black]:!bg-[#000505] [&_.bg-black\/80]:!bg-[#06141C] [&_.bg-black\/90]:!bg-[#06141C] [&_.bg-white]:!bg-[#F2FEFE] [&_.bg-blue-500]:!bg-[#13F3F7] [&_.bg-blue-600]:!bg-[#0795A5] [&_.bg-red-500]:!bg-[#13F3F7] [&_.text-white]:!text-[#F2FEFE] [&_.text-black]:!text-[#000505] [&_.text-zinc-400]:!text-[#91B5BD] [&_.text-zinc-500]:!text-[#6E8B92] [&_.text-zinc-600]:!text-[#4E6B72] [&_.text-red-500]:!text-[#13F3F7] [&_.border-white]:!border-[#13F3F7]/42 [&_.border-black]:!border-[#000505] [&_.border-blue-500]:!border-[#13F3F7] [&_.border-blue-600]:!border-[#0795A5] [&_.border-red-500]:!border-[#13F3F7] [&_[class*='text-[#FF2D2D]']]:!text-[#13F3F7] [&_[class*='bg-[#FF2D2D]']]:!bg-[#13F3F7] [&_[class*='border-[#FF2D2D]']]:!border-[#13F3F7] [&_[class*='decoration-[#FF2D2D]']]:!decoration-[#13F3F7] [&_[class*='text-[#FF1A1A]']]:!text-[#13F3F7] [&_[class*='bg-[#FF1A1A]']]:!bg-[#13F3F7] [&_[class*='border-[#FF1A1A]']]:!border-[#13F3F7] [&_[class*='decoration-[#FF1A1A]']]:!decoration-[#13F3F7] [&_[class*='border-[#404040]']]:!border-[#07556A] [&_[class*='bg-[#404040]']]:!bg-[#07556A] [&_[class*='text-[#404040]']]:!text-[#91B5BD] [&_[class*='border-[#1a1a1a]']]:!border-[#07556A] [&_[class*='bg-[rgba(255,45,45,.15)]']]:!bg-[rgba(19,243,247,.12)] [&_[class*='bg-[rgba(255,26,26,.15)]']]:!bg-[rgba(19,243,247,.12)] [&_[class*='border-[rgba(255,45,45,.15)]']]:!border-[rgba(19,243,247,.18)]">
<header class="w-full">
    <div class="relative z-[10] w-full min-h-[50px] py-3 bg-black/70 backdrop-blur-md flex justify-center items-center border-b border-white/10">
        <h1 class="text-2xl sm:text-3xl font-bold font-['Orbitron'] text-[#13F3F7] drop-shadow-[0_0_12px_rgba(19,243,247,.35)]">Robo</h1>
        <h1 class="text-2xl sm:text-3xl font-bold font-['Orbitron'] text-[#F0ED06]">Drive</h1>
    </div>
</header>