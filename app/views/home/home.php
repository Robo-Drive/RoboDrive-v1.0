<!DOCTYPE html>
<html lang="pt-BR" class="[scrollbar-width:none]">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ROBODRIVE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet" />
</head>

<body class="relative overflow-x-hidden bg-black font-['Space_Grotesk'] text-white [&::-webkit-scrollbar]:hidden">

  <!-- ═══════════════════════════════════════
       0. WALLPAPER — plano de fudo animado
  ═══════════════════════════════════════ -->
  <section>
    <div class="fixed inset-0 z-0 h-screen w-screen overflow-hidden bg-black pointer-events-none" aria-hidden="true">
      <canvas id="myCanvas" class="h-100 w-100"></canvas>
    </div>

    <div class="fixed inset-0 z-[1] pointer-events-none [background:linear-gradient(180deg,rgba(0,0,0,0.18)_0%,rgba(0,0,0,0.48)_45%,rgba(0,0,0,0.72)_100%),radial-gradient(circle_at_top_right,rgba(255,45,45,0.12),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(255,45,45,0.08),transparent_28%)]" aria-hidden="true"></div>
  </section>

  <div class="relative z-[2]">

  <!-- ═══════════════════════════════════════
       1. HERO — fullscreen imersivo
  ═══════════════════════════════════════ -->
  <section class="relative flex min-h-[100svh] flex-col justify-end overflow-hidden border-b-[3px] border-white">

    <!-- grid background -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.04)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.04)_1px,transparent_1px)] bg-[size:72px_72px]"></div>

    <!-- noise layer -->
    <div class="absolute inset-0 opacity-[0.03] [background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22/></svg>')]"></div>

    <!-- eyebrow -->
    <div class="absolute top-10 left-10 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">
      IFPR &nbsp;·&nbsp; SISTEMA DE GESTÃO &nbsp;·&nbsp; 2025
    </div>

    <!-- counter top-right -->
    <div class="absolute top-[2.2rem] right-10 text-right">
      <div class="mb-1 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">PROJETOS ATIVOS</div>
      <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#FF2D2D]">24+</div>
    </div>

    <!-- main content -->
    <div class="relative z-[1] px-10 pb-20">
      <div class="mb-5 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#FF2D2D]">
        ● PLATAFORMA EDUCACIONAL DE ROBÓTICA
      </div>

      <h1 class="mb-8 max-w-[14ch] font-['Orbitron'] text-[clamp(3.5rem,11vw,10rem)] leading-[0.92] tracking-[-0.03em]">
        ROBO<span class="text-[#FF2D2D]">DRIVE</span>
      </h1>

      <div class="flex flex-wrap items-end justify-between gap-8">
        <p class="max-w-[42ch] border-l-4 border-[#FF2D2D] pl-5 text-[clamp(1rem,2vw,1.4rem)] leading-[1.55] text-zinc-400">
          Repositório central de projetos de robótica educacional do IFPR. Centralize código, componentes, equipes e conhecimento — tudo em um lugar.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= URL_BASE ?>/login" class="inline-flex items-center gap-3 border-[3px] border-white bg-[#FF2D2D] px-10 py-[1.1rem] text-base font-black uppercase tracking-[.06em] text-white no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] hover:brightness-[1.15]">
            COMEÇAR AGORA
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#" class="inline-flex items-center gap-3 border-[3px] border-white px-10 py-[1.1rem] text-base font-black uppercase tracking-[.06em] text-white no-underline transition-[background,color] duration-200 hover:bg-white hover:text-black">VER PROJETOS PÚBLICOS</a>
        </div>
      </div>
    </div>

    <div class="h-[3px] w-full bg-[#FF2D2D]"></div>
  </section>


  <!-- ═══════════════════════════════════════
       2. MARQUEE TICKER
  ═══════════════════════════════════════ -->
  
  <div class="marquee--primary relative ml-[calc(50%-50vw)] flex w-screen select-none gap-4 overflow-hidden [mask-image:linear-gradient(90deg,transparent_0%,#000_8%,#000_92%,transparent_100%)] [mask-repeat:no-repeat] [mask-size:100%_100%] [-webkit-mask-image:linear-gradient(90deg,transparent_0%,#000_8%,#000_92%,transparent_100%)] [-webkit-mask-repeat:no-repeat] [-webkit-mask-size:100%_100%]" aria-label="Marquee de navegação rápida">
    <div class="marquee__track flex w-max items-center [will-change:transform]">
      <ul class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-white">
        <li class="text-white"><span class="text-[#FF2D2D]">Robótica</span> educacional</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Código</span> versionado</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Componentes</span> catalogados</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Equipes</span> organizadas</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Fórum</span> colaborativo</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Projetos</span> públicos</li>
      </ul>

      <ul aria-hidden="true" class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-white">
        <li class="text-white"><span class="text-[#FF2D2D]">Robótica</span> educacional</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Código</span> versionado</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Componentes</span> catalogados</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Equipes</span> organizadas</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Fórum</span> colaborativo</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Projetos</span> públicos</li>
      </ul>
    </div>
  </div>
  
  <div class="h-[3px] w-full bg-white"></div>


  <!-- ═══════════════════════════════════════
       3. STATS — números grandes
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-white">
    <div class="grid grid-cols-4">

      <div class="border-r-[3px] border-white px-8 py-14 text-center transition-colors duration-200 hover:bg-[rgba(255,45,45,.08)]">
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">PROJETOS</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(4rem,8vw,7rem)] font-black leading-none text-[#FF2D2D]">50+</div>
      </div>

      <div class="border-r-[3px] border-white px-8 py-14 text-center transition-colors duration-200 hover:bg-[rgba(255,45,45,.08)]">
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">EQUIPES</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(4rem,8vw,7rem)] font-black leading-none text-white">8</div>
      </div>

      <div class="border-r-[3px] border-white px-8 py-14 text-center transition-colors duration-200 hover:bg-[rgba(255,45,45,.08)]">
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">COMPONENTES</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(4rem,8vw,7rem)] font-black leading-none text-[#FF2D2D]">156</div>
      </div>

      <div class="px-8 py-14 text-center transition-colors duration-200 hover:bg-[rgba(255,45,45,.08)]">
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">USUARIOS</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(4rem,8vw,7rem)] font-black leading-none text-white">186</div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       4. FUNCIONALIDADES — feature grid
  ═══════════════════════════════════════ -->
  <section class="mt-10">

    <!-- section header -->
    <div class="flex items-end justify-between gap-8 px-10 pb-10 pt-16">
      <div>
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">O QUE FAZEMOS?</div>
        <h2 class="text-[clamp(2.5rem,6vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
          TUDO QUE<br>
          <span class="text-[#FF2D2D]">VOCÊ</span> PRECISA
        </h2>
      </div>
      <p class="max-w-[36ch] pb-1 text-base leading-[1.6] text-zinc-400">
        Quatro pilares que resolvem o problema da dispersão de conhecimento em projetos de robótica educacional.
      </p>
    </div>

    <!-- 2×2 grid -->
    <div class="grid grid-cols-2 gap-8 px-10 border-b-[3px] border-white">

      <!-- CÓDIGO -->
      <div class="group relative overflow-hidden border-[3px] border-b-[3px] border-white p-12 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-8 inline-flex border-[3px] border-[#FF2D2D] p-4 text-[#FF2D2D]">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <div class="mb-4 flex items-start justify-between">
          <h3 class="text-[2rem] font-black tracking-[-.03em]">CÓDIGO</h3>
          <span class="border-2 border-[#333] px-3 py-1 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">01</span>
        </div>
        <p class="leading-[1.65] text-zinc-500">Armazene e versione códigos dos seus robôs. Nunca mais perca uma linha de código importante entre turmas. Suporte a Arduino, Python e C++.</p>
      </div>

      <!-- COMPONENTES -->
      <div class="group relative overflow-hidden border-[3px] border-b-[3px] border-white p-12 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-8 inline-flex border-[3px] border-[#FF2D2D] p-4 text-[#FF2D2D]">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
        </div>
        <div class="mb-4 flex items-start justify-between">
          <h3 class="text-[2rem] font-black tracking-[-.03em]">COMPONENTES</h3>
          <span class="border-2 border-[#333] px-3 py-1 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">02</span>
        </div>
        <p class="leading-[1.65] text-zinc-500">Catalogue sensores, motores e atuadores com imagens e especificações técnicas. Construa uma biblioteca reutilizável para todo o campus.</p>
      </div>

      <!-- EQUIPES -->
      <div class="group relative overflow-hidden border-[3px] border-b-[0px] border-white p-12 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-8 inline-flex border-[3px] border-[#FF2D2D] p-4 text-[#FF2D2D]">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="mb-4 flex items-start justify-between">
          <h3 class="text-[2rem] font-black tracking-[-.03em]">EQUIPES</h3>
          <span class="border-2 border-[#333] px-3 py-1 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">03</span>
        </div>
        <p class="leading-[1.65] text-zinc-500">Gerencie equipes por campus. Controle de acesso granular por roles — professor controla tudo, aluno contribui, visitante visualiza.</p>
      </div>

      <!-- FÓRUM -->
      <div class="group relative overflow-hidden border-[3px] border-b-[0px] border-white p-12 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-8 inline-flex border-[3px] border-[#FF2D2D] p-4 text-[#FF2D2D]">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="mb-4 flex items-start justify-between">
          <h3 class="text-[2rem] font-black tracking-[-.03em]">FÓRUM</h3>
          <span class="border-2 border-[#333] px-3 py-1 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">04</span>
        </div>
        <p class="leading-[1.65] text-zinc-500">Discuta, pergunte e compartilhe conhecimento. Conecte equipes de diferentes campi do IFPR. Dúvidas de hardware, software e estratégias de competição.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       5. PROJETOS — card grid (estilo F1 highlights)
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-white bg-transparent">

    <div class="flex items-end justify-between gap-8 border-b-[3px] border-[#404040] px-10 pb-10 pt-16">
      <div>
        <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">PROJETOS RECENTES</div>
        <h2 class="text-[clamp(2.5rem,6vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
          DA<br>COMUNIDADE
        </h2>
      </div>
      <a href="#" class="border-b-2 border-[#404040] pb-1 text-sm font-bold uppercase tracking-[.08em] text-zinc-400 no-underline transition-colors duration-200 hover:border-white hover:text-white">
        VER TODOS →
      </a>
    </div>

    <!-- project list rows (F1-style) -->
    <div>

      <div class="group relative grid cursor-pointer grid-cols-[4rem_1fr_auto] items-center gap-8 overflow-hidden border-b border-[#1a1a1a] px-10 py-7 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#333]">01</div>
        <div>
          <div class="mb-1.5 text-[1.2rem] font-black tracking-[-.02em]">Robô Seguidor de Linha</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">Equipe Alpha &nbsp;·&nbsp; Cascavel &nbsp;·&nbsp; Arduino</div>
        </div>
        <div class="flex items-center gap-4">
          <span class="border-2 border-[#FF2D2D] bg-[rgba(255,45,45,.15)] px-4 py-1.5 text-[.7rem] font-bold uppercase tracking-[.1em] text-[#FF2D2D]">PÚBLICO</span>
          <span class="text-[.85rem] font-bold text-zinc-500">12 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-[4rem_1fr_auto] items-center gap-8 overflow-hidden border-b border-[#1a1a1a] px-10 py-7 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#333]">02</div>
        <div>
          <div class="mb-1.5 text-[1.2rem] font-black tracking-[-.02em]">Braço Robótico Arduino</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">Equipe Beta &nbsp;·&nbsp; Londrina &nbsp;·&nbsp; Servo Motors</div>
        </div>
        <div class="flex items-center gap-4">
          <span class="border-2 border-[#0066FF] bg-[rgba(0,102,255,.12)] px-4 py-1.5 text-[.7rem] font-bold uppercase tracking-[.1em] text-[#0066FF]">EQUIPE</span>
          <span class="text-[.85rem] font-bold text-zinc-500">8 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-[4rem_1fr_auto] items-center gap-8 overflow-hidden border-b border-[#1a1a1a] px-10 py-7 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#333]">03</div>
        <div>
          <div class="mb-1.5 text-[1.2rem] font-black tracking-[-.02em]">Robô Sumô 500g</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">Equipe Gamma &nbsp;·&nbsp; Curitiba &nbsp;·&nbsp; C++</div>
        </div>
        <div class="flex items-center gap-4">
          <span class="border-2 border-[#FF2D2D] bg-[rgba(255,45,45,.15)] px-4 py-1.5 text-[.7rem] font-bold uppercase tracking-[.1em] text-[#FF2D2D]">PÚBLICO</span>
          <span class="text-[.85rem] font-bold text-zinc-500">19 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-[4rem_1fr_auto] items-center gap-8 overflow-hidden border-b border-[#1a1a1a] px-10 py-7 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#333]">04</div>
        <div>
          <div class="mb-1.5 text-[1.2rem] font-black tracking-[-.02em]">Controlador PID para Seguidor</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">Equipe Alpha &nbsp;·&nbsp; Cascavel &nbsp;·&nbsp; Python</div>
        </div>
        <div class="flex items-center gap-4">
          <span class="border-2 border-[#404040] bg-[#1a1a1a] px-4 py-1.5 text-[.7rem] font-bold uppercase tracking-[.1em] text-zinc-500">PRIVADO</span>
          <span class="text-[.85rem] font-bold text-zinc-500">6 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-[4rem_1fr_auto] items-center gap-8 overflow-hidden px-10 py-7 transition-colors duration-[250ms] hover:bg-[rgba(255,45,45,.06)]">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#FF2D2D] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-[2.5rem] font-black leading-none text-[#333]">05</div>
        <div>
          <div class="mb-1.5 text-[1.2rem] font-black tracking-[-.02em]">Visão Computacional com OpenCV</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">Equipe Delta &nbsp;·&nbsp; Londrina &nbsp;·&nbsp; Python</div>
        </div>
        <div class="flex items-center gap-4">
          <span class="border-2 border-[#FF2D2D] bg-[rgba(255,45,45,.15)] px-4 py-1.5 text-[.7rem] font-bold uppercase tracking-[.1em] text-[#FF2D2D]">PÚBLICO</span>
          <span class="text-[.85rem] font-bold text-zinc-500">11 componentes</span>
        </div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       6. SOBRE — why it exists (two-col)
  ═══════════════════════════════════════ -->
  <section class="grid grid-cols-2 border-b-[3px] border-white">

    <!-- left col: big title -->
    <div class="relative overflow-hidden border-r-[3px] border-white px-12 py-20">
      <div class="absolute -bottom-16 -right-16 h-[20rem] w-[20rem] rotate-[20deg] border-[3px] border-[rgba(255,45,45,.15)]"></div>
      <div class="mb-6 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">SOBRE O PROJETO</div>
      <h2 class="relative z-[1] text-[clamp(3rem,7vw,6rem)] font-black tracking-[-.04em] leading-[.92]">
        POR QUE<br>O
        <span class="font-['Orbitron'] text-[#FF2D2D]">ROBO<wbr>DRIVE</span><br>
        EXISTE?
      </h2>
    </div>

    <!-- right col: text + tags -->
    <div class="flex flex-col justify-between gap-12 px-12 py-20">
      <div class="flex flex-col gap-6">
        <p class="text-[1.15rem] leading-[1.7] text-zinc-300">
          O RoboDrive nasceu para resolver o problema da <strong class="text-white">dispersão de conhecimento</strong> em projetos de robótica educacional no IFPR.
        </p>
        <p class="text-base leading-[1.7] text-zinc-500">
          Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos pessoais, grupos de WhatsApp e pen drives, perde-se a capacidade de reaproveitar esse conhecimento em turmas futuras — e todo o esforço se perde com a formatura.
        </p>
        <p class="text-base leading-[1.7] text-zinc-500">
          Com o RoboDrive, você pode facilmente gerenciar equipes, ou entrar em uma e documentar projetos, sua equipe constrói um acervo técnico duradouro.
        </p>
      </div>

      <div class="flex flex-wrap gap-3">
        <span class="border-[3px] border-white bg-[#FF2D2D] px-8 py-3.5 text-[.9rem] font-black uppercase tracking-[.06em]">CENTRALIZE</span>
        <span class="border-[3px] border-white bg-[#0066FF] px-8 py-3.5 text-[.9rem] font-black uppercase tracking-[.06em]">ORGANIZE</span>
        <span class="border-[3px] border-white bg-[#FF2D2D] px-8 py-3.5 text-[.9rem] font-black uppercase tracking-[.06em]">REUTILIZE</span>
      </div>
    </div>

  </section>


    <!-- ═══════════════════════════════════════
      7. COMO FUNCIONA — 3 steps horizontal
    ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-white section-07">

    <div class="border-b-[3px] border-[#222] px-10 pb-10 pt-16">
      <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">PRIMEIROS PASSOS</div>
      <h2 class="text-[clamp(2.5rem,6vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
        COMO <span class="text-[#FF2D2D]">FUNCIONA</span>
      </h2>
    </div>

    <div class="grid grid-cols-3">

      <div class="relative border-r-[3px] border-[#222] px-10 py-12">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#FF2D2D]"></div>
        <div class="mb-4 font-['Barlow_Condensed'] text-[5rem] font-black leading-none text-[#FF2D2D] opacity-[0.25]">01</div>
        <div class="mb-6 inline-flex border-2 border-[#FF2D2D] p-3.5 text-[#FF2D2D]">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="mb-3.5 text-[1.6rem] font-black tracking-[-.03em]">CADASTRE-SE</h3>
        <p class="text-[.95rem] leading-[1.7] text-zinc-500">Crie sua conta com e-mail institucional do IFPR. Professores validam membros e gerenciam equipes por campus.</p>
      </div>

      <div class="relative border-r-[3px] border-[#222] px-10 py-12">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#FF2D2D]"></div>
        <div class="mb-4 font-['Barlow_Condensed'] text-[5rem] font-black leading-none text-[#FF2D2D] opacity-[0.25]">02</div>
        <div class="mb-6 inline-flex border-2 border-[#FF2D2D] p-3.5 text-[#FF2D2D]">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
        </div>
        <h3 class="mb-3.5 text-[1.6rem] font-black tracking-[-.03em]">CRIE PROJETOS</h3>
        <p class="text-[.95rem] leading-[1.7] text-zinc-500">Documente robôs com código, componentes, imagens e descrições técnicas. Defina a visibilidade: equipe, campus ou público.</p>
      </div>

      <div class="relative px-10 py-12">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#FF2D2D]"></div>
        <div class="mb-4 font-['Barlow_Condensed'] text-[5rem] font-black leading-none text-[#FF2D2D] opacity-[0.25]">03</div>
        <div class="mb-6 inline-flex border-2 border-[#FF2D2D] p-3.5 text-[#FF2D2D]">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3 class="mb-3.5 text-[1.6rem] font-black tracking-[-.03em]">COLABORE</h3>
        <p class="text-[.95rem] leading-[1.7] text-zinc-500">Compartilhe com sua equipe, publique para o campus ou abra para toda a comunidade IFPR. Use o fórum para tirar dúvidas.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       8. ROLES — quem usa
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-white">

    <div class="border-b-[3px] border-white px-10 pb-10 pt-16">
      <div class="mb-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">CONTROLE DE ACESSO</div>
      <h2 class="text-[clamp(2.5rem,6vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
        QUEM <span class="text-[#FF2D2D]">USA</span>
      </h2>
    </div>

    <div class="grid grid-cols-3">

      <div class="relative border-r-[3px] border-white p-12">
        <div class="mb-6 inline-block border-2 border-[#FF2D2D] px-3.5 py-1 text-[.7rem] font-bold uppercase tracking-[.15em] text-[#FF2D2D]">COORDENADOR</div>
        <h3 class="mb-4 text-[1.75rem] font-black tracking-[-.03em]">Controle Total</h3>
        <ul class="list-none p-0 text-[.95rem] leading-[2] text-zinc-500">
          <li>→ &nbsp;Criar e gerenciar equipes</li>
          <li>→ &nbsp;Validar alunos no campus</li>
          <li>→ &nbsp;Definir visibilidade de projetos</li>
          <li>→ &nbsp;Acesso a todos os projetos da equipe</li>
          <li>→ &nbsp;Moderar fórum</li>
        </ul>
      </div>

      <div class="relative border-r-[3px] border-white p-12">
        <div class="mb-6 inline-block border-2 border-[#0066FF] px-3.5 py-1 text-[.7rem] font-bold uppercase tracking-[.15em] text-[#0066FF]">CONTRIBUIDOR</div>
        <h3 class="mb-4 text-[1.75rem] font-black tracking-[-.03em]">Faz a Mágica Acontecer</h3>
        <ul class="list-none p-0 text-[.95rem] leading-[2] text-zinc-500">
          <li>→ &nbsp;Criar e documentar projetos</li>
          <li>→ &nbsp;Usar componentes da biblioteca</li>
          <li>→ &nbsp;Colaborar com a equipe</li>
          <li>→ &nbsp;Participar do fórum</li>
          <li>→ &nbsp;Ver projetos públicos de outros campi</li>
        </ul>
      </div>

      <div class="relative p-12">
        <div class="mb-6 inline-block border-2 border-[#404040] px-3.5 py-1 text-[.7rem] font-bold uppercase tracking-[.15em] text-zinc-500">VISITANTE</div>
        <h3 class="mb-4 text-[1.75rem] font-black tracking-[-.03em]">Leitor</h3>
        <ul class="list-none p-0 text-[.95rem] leading-[2] text-zinc-600">
          <li>→ &nbsp;Ver projetos públicos</li>
          <li>→ &nbsp;Explorar componentes públicos</li>
          <li>→ &nbsp;Ler threads do fórum</li>
          <li class="text-[#404040]">✕ &nbsp;Criar projetos</li>
          <li class="text-[#404040]">✕ &nbsp;Participar de equipes</li>
        </ul>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       9. MARQUEE 2 — segundo ticker (branco)
  ═══════════════════════════════════════ -->
  <div class="marquee--primary relative ml-[calc(50%-50vw)] mt-1 mb-1 flex w-screen select-none gap-4 overflow-hidden [mask-image:linear-gradient(90deg,transparent_0%,#000_8%,#000_92%,transparent_100%)] [mask-repeat:no-repeat] [mask-size:100%_100%] [-webkit-mask-image:linear-gradient(90deg,transparent_0%,#000_8%,#000_92%,transparent_100%)] [-webkit-mask-repeat:no-repeat] [-webkit-mask-size:100%_100%]" aria-label="Marquee de navegação rápida">
    <div class="marquee__track flex w-max items-center [will-change:transform]">
      <ul class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-white">
        <li class="text-white"><span class="text-[#FF2D2D]">Professor</span> orientador</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Aluno</span> desenvolvedor</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Equipe</span> colaborativa</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Campus</span> integrado</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Repositório</span> central</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Projetos</span> publicados</li>
      </ul>

      <ul aria-hidden="true" class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-white">
        <li class="text-white"><span class="text-[#FF2D2D]">Professor</span> orientador</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Aluno</span> desenvolvedor</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Equipe</span> colaborativa</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Campus</span> integrado</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Repositório</span> central</li>
        <li class="text-white"><span class="text-[#FF2D2D]">Projetos</span> publicados</li>
      </ul>
    </div>
  </div>

  <div class="h-[3px] w-full bg-white"></div>

  <!-- ═══════════════════════════════════════
       10. CTA FINAL — fullscreen call to action
  ═══════════════════════════════════════ -->
  <section class="relative flex min-h-[60vh] flex-col justify-center overflow-hidden">

    <div class="relative z-[1] px-10 py-24 text-center">
      <div class="mb-6 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">FAÇA PARTE DA COMUNIDADE</div>
      <h2 class="mb-10 text-[clamp(3.5rem,10vw,9rem)] font-black tracking-[-.04em] leading-[.9]">
        PRONTO PARA<br>
        <span class="text-[#FF2D2D]">COMEÇAR?</span>
      </h2>
      <p class="mx-auto mb-12 max-w-[38ch] text-[1.15rem] leading-[1.65] text-zinc-500">
        Junte-se ao repositório central de robótica e pare de perder conhecimento.
      </p>
      <div class="flex flex-wrap justify-center gap-5">
        <a href="<?= URL_BASE ?>/cadastro" class="inline-flex items-center gap-3 border-[3px] border-white bg-[#FF2D2D] px-10 py-[1.1rem] text-base font-black uppercase tracking-[.06em] text-white no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] hover:brightness-[1.15]">
          CRIAR CONTA GRÁTIS
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="<?= URL_BASE ?>/login" class="inline-flex items-center gap-3 border-[3px] border-white px-10 py-[1.1rem] text-base font-black uppercase tracking-[.06em] text-white no-underline transition-[background,color] duration-200 hover:bg-white hover:text-black">JÁ TENHO CONTA</a>
      </div>
    </div>

    <div class="h-[3px] w-full bg-[#FF2D2D]"></div>
  </section>

  </div>

</body>
</html>

<script>
  const marquees = document.querySelectorAll('.marquee--primary');

  marquees.forEach((marquee) => {
    const track = marquee.querySelector('.marquee__track');
    const firstList = marquee.querySelector('.marquee__content');
    const secondList = firstList ? firstList.nextElementSibling : null;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const speed = 100;

    let direction = 1;
    let offset = 0;
    let lastTime = null;

    const cloneListItems = (list) => {
      if (!list) {
        return [];
      }

      return Array.from(list.children).map((item) => item.cloneNode(true));
    };

    const ensureCoverage = () => {
      if (!firstList || !secondList) {
        return;
      }

      const templateItems = cloneListItems(firstList);

      while (firstList.scrollWidth < window.innerWidth * 1.5) {
        templateItems.forEach((item) => {
          firstList.appendChild(item.cloneNode(true));
          secondList.appendChild(item.cloneNode(true));
        });
      }
    };

    const setDirection = (reverse) => {
      direction = reverse ? -1 : 1;
    };

    const animate = (time) => {
      if (!track) {
        return;
      }

      if (lastTime === null) {
        lastTime = time;
      }

      const deltaSeconds = (time - lastTime) / 1000;
      lastTime = time;

      if (!prefersReducedMotion.matches) {
        const loopWidth = track.scrollWidth / 2;

        if (loopWidth > 0) {
          offset = (offset + (direction * speed * deltaSeconds)) % loopWidth;

          if (offset < 0) {
            offset += loopWidth;
          }

          track.style.transform = `translate3d(${-offset}px, 0, 0)`;
        }
      }

      requestAnimationFrame(animate);
    };

    ensureCoverage();
    setDirection(false);

    window.addEventListener('wheel', (event) => {
      setDirection(event.deltaY > 0);
    }, { passive: true });

    window.addEventListener('resize', () => {
      offset = 0;
      lastTime = null;
      if (track) {
        track.style.transform = 'translate3d(0, 0, 0)';
      }
      ensureCoverage();
    });

    requestAnimationFrame(animate);
  });
</script>
<script src="<?= JS_URL_BASE ?>/wallpaper.js"></script>
