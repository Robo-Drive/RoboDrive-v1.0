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
    <title>ROBODRIVE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { grotesk: ['Space Grotesk', 'sans-serif'] },
          colors: {
            red:  '#FF2D2D',
            blue: '#0066FF',
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Space Grotesk', sans-serif; }
    .grid-bg {
      background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
      background-size: 80px 80px;
    }
    .grid-bg-sm {
      background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
      background-size: 60px 60px;
    }
  </style>
</head>
<body class="w-screen h-screen bg-black text-white overflow-x-hidden">

  <!-- SHOWZINHO -->
  <section class="relative min-h-screen flex items-center border-b-4 border-white overflow-hidden">
    <div class="grid-bg absolute inset-0 opacity-5"></div>
    <div class="absolute top-20 right-8 w-64 h-64 border-4 border-[#FF2D2D] rotate-12 opacity-20"></div>
    <div class="absolute bottom-20 right-32 w-40 h-40 border-4 border-white -rotate-6 opacity-15"></div>
    <div class="absolute top-40 right-48 w-20 h-20 bg-[#FF2D2D] opacity-30"></div>

    <div class="relative z-10 w-full max-w-screen-xl mx-auto px-6 py-20">
      <div class="max-w-4xl">

        <h1 class="font-black leading-none tracking-tighter mb-8" style="font-size: clamp(3rem, 10vw, 8rem)">
          REPOSITÓRIO<br>
          DE<span class="text-[#FF2D2D]"> PROJETOS</span><br>
          DE ROBÓTICA
        </h1>

        <div class="border-l-8 border-[#FF2D2D] pl-6 mb-10 max-w-2xl">
          <p class="text-[#d4d4d4] font-normal" style="font-size: clamp(1.1rem, 2.5vw, 1.5rem)">
            Centralize, organize e compartilhe projetos de robótica educacional.
            Pare de perder código em pen drives e grupos de WhatsApp.
          </p>
        </div>

        <div class="flex flex-wrap gap-4">
          <a href="#" class="inline-flex items-center gap-3 px-10 py-5 bg-[#FF2D2D] border-4 border-white font-black text-xl tracking-tight hover:brightness-110 transition-all">
            COMEÇAR AGORA
            <svg class="w-6 h-6 stroke-white fill-none stroke-[3]" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#" class="inline-flex items-center gap-3 px-10 py-5 border-4 border-white font-black text-xl tracking-tight hover:bg-white hover:text-black transition-all">
            VER PROJETOS PÚBLICOS
          </a>
        </div>

      </div>
    </div>

    <div class="absolute bottom-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
  </section>

  <!-- STATUS -->
  <section class="relative border-b-4 border-white overflow-hidden">
    <div class="absolute inset-0 bg-[#FF2D2D] opacity-15"></div>
    <div class="relative z-10 grid grid-cols-3 divide-x-4 divide-white max-w-screen-xl mx-auto">
      <div class="px-8 py-10 text-center">
        <div class="font-black tracking-tighter text-[#FF2D2D] leading-none mb-1" style="font-size: clamp(2rem, 6vw, 4rem)">24+</div>
        <div class="text-xs font-bold tracking-widest text-[#a3a3a3]">PROJETOS</div>
      </div>
      <div class="px-8 py-10 text-center">
        <div class="font-black tracking-tighter text-[#FF2D2D] leading-none mb-1" style="font-size: clamp(2rem, 6vw, 4rem)">8</div>
        <div class="text-xs font-bold tracking-widest text-[#a3a3a3]">EQUIPES</div>
      </div>
      <div class="px-8 py-10 text-center">
        <div class="font-black tracking-tighter text-[#FF2D2D] leading-none mb-1" style="font-size: clamp(2rem, 6vw, 4rem)">156</div>
        <div class="text-xs font-bold tracking-widest text-[#a3a3a3]">COMPONENTES</div>
      </div>
    </div>
  </section>

  <!-- FUNCIONALIDADES -->
  <section class="border-b-4 border-white">
    <div class="max-w-screen-xl mx-auto px-6 py-20">

      <h2 class="font-black leading-none tracking-tighter mb-12" style="font-size: clamp(2.5rem, 7vw, 5rem)">
        O QUE O<br>
        <span class="font-['Orbitron']">ROBO<span class="text-[#FF2D2D]">DRIVE</span></span><br>
        FAZ
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 border-4 border-white">

        <div class="relative p-8 md:p-12 border-b-4 border-r-4 border-white overflow-hidden hover:bg-[#FF2D2D]/5 transition-colors">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="inline-flex p-4 border-4 border-[#FF2D2D] text-[#FF2D2D] mb-6">
            <svg class="w-12 h-12 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </div>
          <h3 class="text-3xl font-black tracking-tighter mb-4">CÓDIGO</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Armazene e versione códigos dos seus robôs. Nunca mais perca uma linha de código importante entre turmas.</p>
        </div>

        <div class="relative p-8 md:p-12 border-b-4 border-white overflow-hidden hover:bg-[#FF2D2D]/5 transition-colors">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="inline-flex p-4 border-4 border-[#FF2D2D] text-[#FF2D2D] mb-6">
            <svg class="w-12 h-12 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
          </div>
          <h3 class="text-3xl font-black tracking-tighter mb-4">COMPONENTES</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Catalogue componentes reutilizáveis com imagens e especificações técnicas. Construa uma biblioteca do seu campus.</p>
        </div>

        <div class="relative p-8 md:p-12 border-r-4 border-white overflow-hidden hover:bg-[#FF2D2D]/5 transition-colors">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="inline-flex p-4 border-4 border-[#FF2D2D] text-[#FF2D2D] mb-6">
            <svg class="w-12 h-12 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3 class="text-3xl font-black tracking-tighter mb-4">EQUIPES</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Colabore com sua equipe e campus. Controle de acesso por roles — professor, aluno e admin.</p>
        </div>

        <div class="relative p-8 md:p-12 overflow-hidden hover:bg-[#FF2D2D]/5 transition-colors">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="inline-flex p-4 border-4 border-[#FF2D2D] text-[#FF2D2D] mb-6">
            <svg class="w-12 h-12 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          <h3 class="text-3xl font-black tracking-tighter mb-4">FÓRUM</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Discuta, pergunte e compartilhe conhecimento. Conecte equipes de diferentes campi do IFPR.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- SOBRE -->
  <section class="relative border-b-4 border-white overflow-hidden">
    <div class="absolute top-12 right-12 w-48 h-48 border-4 border-[#FF2D2D] rotate-12 opacity-20"></div>
    <div class="absolute bottom-12 right-32 w-24 h-24 bg-[#FF2D2D] opacity-10"></div>

    <div class="relative z-10 max-w-screen-xl mx-auto px-6 py-20">
      <div class="max-w-3xl">

        <div class="inline-block mb-8 px-4 py-2 border-2 border-[#FF2D2D] text-xs font-bold tracking-widest bg-[#FF2D2D]/10">
          SOBRE O PROJETO
        </div>

        <h2 class="font-black leading-none tracking-tighter mb-10" style="font-size: clamp(2rem, 6vw, 4.5rem)">
          POR QUE O<br>
          <span class="font-['Orbitron']">ROBO<span class="text-[#FF2D2D]">DRIVE</span></span><br>
          EXISTE?
        </h2>

        <div class="space-y-6 mb-12">
          <p class="text-[#d4d4d4] font-normal leading-relaxed" style="font-size: clamp(1rem, 2vw, 1.25rem)">
            O RoboDrive nasceu para resolver o problema da dispersão de conhecimento em projetos de robótica educacional.
          </p>
          <p class="text-[#a3a3a3] font-normal leading-relaxed" style="font-size: clamp(1rem, 2vw, 1.25rem)">
            Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos pessoais e mensagens, perde-se a capacidade de reaproveitar esse conhecimento em turmas futuras.
          </p>
        </div>

        <div class="flex flex-wrap gap-4">
          <span class="px-8 py-4 bg-[#FF2D2D] border-4 border-white font-black text-lg tracking-tight">CENTRALIZE</span>
          <span class="px-8 py-4 bg-[#0066FF] border-4 border-white font-black text-lg tracking-tight">ORGANIZE</span>
          <span class="px-8 py-4 bg-[#FF2D2D] border-4 border-white font-black text-lg tracking-tight">REUTILIZE</span>
        </div>

      </div>
    </div>
  </section>

  <!-- COMO FUNCIONA -->
  <section class="border-b-4 border-white">
    <div class="max-w-screen-xl mx-auto px-6 py-20">

      <h2 class="font-black tracking-tighter text-center mb-16" style="font-size: clamp(2rem, 6vw, 4rem)">
        COMO FUNCIONA
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 border-4 border-white">

        <div class="relative p-8 md:p-10 border-b-4 md:border-b-0 md:border-r-4 border-white overflow-hidden">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="font-black tracking-tighter text-[#FF2D2D] opacity-15 leading-none mb-4" style="font-size:3.75rem">01</div>
          <div class="inline-flex p-3 border-2 border-[#FF2D2D] text-[#FF2D2D] mb-4">
            <svg class="w-8 h-8 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <h3 class="text-2xl font-black tracking-tighter mb-3">CADASTRE-SE</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Crie sua conta com e-mail do IFPR. Professores validam e adicionam alunos às equipes.</p>
        </div>

        <div class="relative p-8 md:p-10 border-b-4 md:border-b-0 md:border-r-4 border-white overflow-hidden">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="font-black tracking-tighter text-[#FF2D2D] opacity-15 leading-none mb-4" style="font-size:3.75rem">02</div>
          <div class="inline-flex p-3 border-2 border-[#FF2D2D] text-[#FF2D2D] mb-4">
            <svg class="w-8 h-8 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
          </div>
          <h3 class="text-2xl font-black tracking-tighter mb-3">CRIE PROJETOS</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Documente robôs com código, componentes, imagens e descrições técnicas detalhadas.</p>
        </div>

        <div class="relative p-8 md:p-10 overflow-hidden">
          <div class="absolute top-0 left-0 w-full h-1 bg-[#FF2D2D]"></div>
          <div class="font-black tracking-tighter text-[#FF2D2D] opacity-15 leading-none mb-4" style="font-size:3.75rem">03</div>
          <div class="inline-flex p-3 border-2 border-[#FF2D2D] text-[#FF2D2D] mb-4">
            <svg class="w-8 h-8 stroke-current fill-none" style="stroke-width:2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <h3 class="text-2xl font-black tracking-tighter mb-3">COLABORE</h3>
          <p class="text-[#a3a3a3] leading-relaxed">Compartilhe com sua equipe ou publique para toda a comunidade IFPR.</p>
        </div>

      </div>
    </div>
  </section>

  <!-- ENTRAR NO SISTEMA -->
  <section class="relative overflow-hidden">
    <div class="grid-bg-sm absolute inset-0 opacity-5"></div>
    <div class="absolute top-8 left-8 w-32 h-32 border-4 border-[#FF2D2D] rotate-45 opacity-20"></div>
    <div class="absolute bottom-8 right-8 w-48 h-48 border-4 border-white -rotate-12 opacity-10"></div>

    <div class="relative z-10 max-w-screen-xl mx-auto px-6 py-24 text-center">
      <h2 class="font-black leading-none tracking-tighter mb-6" style="font-size: clamp(2.5rem, 8vw, 6rem)">
        PRONTO PARA<br>
        <span class="text-[#FF2D2D]">COMEÇAR?</span>
      </h2>
      <p class="text-xl text-[#a3a3a3] font-normal max-w-xl mx-auto mb-12">
        Junte-se ao repositório central de robótica do IFPR.
      </p>
      <div class="flex flex-wrap justify-center gap-6">
        <a href="#" class="inline-flex items-center gap-3 px-12 py-6 bg-[#FF2D2D] border-4 border-white font-black text-xl tracking-tight hover:brightness-110 hover:scale-105 transition-all">
          CRIAR CONTA GRÁTIS
          <svg class="w-6 h-6 stroke-white fill-none stroke-[3]" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="#" class="inline-flex items-center gap-3 px-12 py-6 border-4 border-white font-black text-xl tracking-tight hover:bg-white hover:text-black transition-all">
          JÁ TENHO CONTA
        </a>
      </div>
    </div>

    <div class="w-full h-1 bg-[#FF2D2D]"></div>
  </section>

</body>
</html>