<!DOCTYPE html>
<html lang="pt-BR" class="[scrollbar-width:none]">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ROBODRIVE</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative overflow-x-hidden bg-[#000505] font-['Space_Grotesk'] text-[#F2FEFE] selection:bg-[rgba(19,243,247,.32)] selection:text-[#000505] before:fixed before:inset-0 before:-z-10 before:pointer-events-none before:content-[''] before:bg-[radial-gradient(circle_at_top_right,rgba(240,237,6,.08),transparent_26%)] [&::-webkit-scrollbar]:hidden">

  <!-- ═══════════════════════════════════════
       0. WALLPAPER - plano de fudo animado
  ═══════════════════════════════════════ -->
  <section>
    <div class="fixed inset-0 z-0 h-screen w-screen overflow-hidden bg-[#000505] pointer-events-none" aria-hidden="true">
      <canvas id="myCanvas" class="h-100 w-100"></canvas>
    </div>
  </section>

  <div class="relative z-[2]">

  <!-- ═══════════════════════════════════════
       1. HERO - tela q o usuario primeiro ve
  ═══════════════════════════════════════ -->
  <section class="relative flex min-h-[100svh] flex-col justify-end overflow-hidden border-b-[3px] border-[#07556A]">

    <div class="absolute top-4 left-4 text-[.55rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:left-10 sm:top-10 sm:text-[.65rem] sm:tracking-[.25em]">
      IFPR &nbsp;·&nbsp; SISTEMA DE GESTÃO &nbsp;·&nbsp; <?php echo date("Y"); ?>
    </div>

    <!-- add dps quando estiver funcionando -->
    <!-- <div class="absolute top-4 right-4 sm:top-[2.2rem] sm:right-10 text-right">
      <div class="mb-1 text-[.55rem] sm:text-[.65rem] font-bold uppercase tracking-[.15em] sm:tracking-[.25em] text-[#91B5BD]">PROJETOS ATIVOS</div>
      <div class="font-['Barlow_Condensed'] text-[1.8rem] sm:text-[2.5rem] font-black leading-none text-[#13F3F7]">24+</div>
    </div> -->

    <!-- parte principal-->
    <div class="relative z-[1] px-4 sm:px-8 lg:px-10 pt-20 sm:pt-28 pb-12 sm:pb-20">
      <div class="mb-3 text-[.6rem] font-bold uppercase tracking-[.15em] text-[#13F3F7] sm:mb-5 sm:text-[.65rem] sm:tracking-[.25em]">
        ● PLATAFORMA EDUCACIONAL DE ROBÓTICA
      </div>

      <h1 class="mb-6 sm:mb-8 max-w-[14ch] font-['Orbitron'] text-[clamp(2.2rem,9vw,10rem)] leading-[0.92] tracking-[-0.03em]">
        ROBO<span class="text-[#13F3F7]">DRIVE</span>
      </h1>

      <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 sm:gap-8">
        <p class="max-w-[42ch] border-l-4 border-[#13F3F7] pl-4 text-sm leading-[1.55] text-[#91B5BD] sm:pl-5 sm:text-base lg:text-[clamp(1rem,2vw,1.4rem)]">
          Repositório central de projetos de robótica educacional do IFPR. Centralize código, componentes, equipes e conhecimento — tudo em um lugar.
        </p>
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 w-full lg:w-auto">
          <a href="<?= URL_BASE ?>/login" class="inline-flex w-full items-center justify-center gap-3 border-[3px] border-[#13F3F7] bg-[#13F3F7] px-6 py-3 text-center text-sm font-black uppercase tracking-[.06em] text-[#000505] no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] hover:bg-[#54FBFE] sm:w-auto sm:px-10 sm:py-[1.1rem] sm:text-base">
            COMEÇAR AGORA
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#" class="inline-flex w-full items-center justify-center gap-3 border-[3px] border-[#13F3F7] bg-transparent px-6 py-3 text-center text-sm font-black uppercase tracking-[.06em] text-[#13F3F7] no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] sm:w-auto sm:px-10 sm:py-[1.1rem] sm:text-base">VER PROJETOS PÚBLICOS</a>
        </div>
      </div>
    </div>

    <div class="h-[3px] w-full bg-[#13F3F7]"></div>
  </section>


  <!-- ═══════════════════════════════════════
       2. MARQUEE
  ═══════════════════════════════════════ -->
  
  <div class="marquee--primary relative ml-[calc(50%-50vw)] flex w-screen select-none gap-4 overflow-hidden" aria-label="Marquee de navegação rápida">
    <div class="marquee__track flex w-max items-center [will-change:transform]">
      <ul class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-[#F2FEFE]">
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Robótica</span> educacional</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Código</span> versionado</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Componentes</span> catalogados</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Equipes</span> organizadas</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Fórum</span> colaborativo</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Projetos</span> públicos</li>
      </ul>

      <ul aria-hidden="true" class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-6 [&>li]:py-[.95rem] [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-[#F2FEFE]">
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Robótica</span> educacional</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Código</span> versionado</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Componentes</span> catalogados</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Equipes</span> organizadas</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Fórum</span> colaborativo</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Projetos</span> públicos</li>
      </ul>
    </div>
  </div>
  
  <div class="h-[3px] w-full bg-[#07556A]"></div>


  <!-- ═══════════════════════════════════════
       3. STATUS - adicionar funcionalidades dps de linkar com o BDD
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-[#07556A]">
    <div class="grid grid-cols-2 lg:grid-cols-4">

      <div class="border-b-[3px] border-r-[3px] border-[#07556A] px-4 py-8 text-center transition-colors duration-200 hover:bg-[rgba(7,85,106,.08)] sm:px-8 sm:py-14 lg:border-b-0">
        <div class="mb-2 text-[.55rem] font-bold uppercase tracking-[.2em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem] sm:tracking-[.25em]">PROJETOS</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(2.5rem,6vw,7rem)] font-black leading-none text-[#13F3F7]">50+</div>
      </div>

      <div class="border-b-[3px] border-[#07556A] px-4 py-8 text-center transition-colors duration-200 hover:bg-[rgba(7,85,106,.08)] sm:px-8 sm:py-14 lg:border-b-0 lg:border-r-[3px]">
        <div class="mb-2 text-[.55rem] font-bold uppercase tracking-[.2em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem] sm:tracking-[.25em]">EQUIPES</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(2.5rem,6vw,7rem)] font-black leading-none text-[#F2FEFE]">8</div>
      </div>

      <div class="border-b-[3px] border-r-[3px] border-[#07556A] px-4 py-8 text-center transition-colors duration-200 hover:bg-[rgba(7,85,106,.08)] sm:px-8 sm:py-14 sm:border-b-0">
        <div class="mb-2 text-[.55rem] font-bold uppercase tracking-[.2em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem] sm:tracking-[.25em]">COMPONENTES</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(2.5rem,6vw,7rem)] font-black leading-none text-[#13F3F7]">156</div>
      </div>

      <div class="px-4 sm:px-8 py-8 sm:py-14 text-center transition-colors duration-200 hover:bg-[rgba(7,85,106,.08)]">
        <div class="mb-2 text-[.55rem] font-bold uppercase tracking-[.2em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem] sm:tracking-[.25em]">USUARIOS</div>
        <div class="font-['Barlow_Condensed'] text-[clamp(2.5rem,6vw,7rem)] font-black leading-none text-[#F2FEFE]">186</div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       4. FUNCIONALIDADES
  ═══════════════════════════════════════ -->
  <section class="mt-6 sm:mt-10">

    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 sm:gap-8 px-4 sm:px-8 lg:px-10 pb-6 sm:pb-10 pt-10 sm:pt-16">
      <div>
        <div class="mb-2 sm:mb-3 text-[.6rem] sm:text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">O QUE FAZEMOS?</div>
        <h2 class="text-[clamp(2rem,5vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
          TUDO QUE<br>
          <span class="text-[#13F3F7]">VOCÊ</span> PRECISA
        </h2>
      </div>
      <p class="max-w-[36ch] pb-1 text-sm sm:text-base leading-[1.6] text-[#91B5BD]">
        Quatro pilares que resolvem o problema da dispersão de conhecimento em projetos de robótica educacional.
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 border-b-[3px] border-[#07556A] px-4 pb-10 sm:gap-8 sm:px-8 lg:grid-cols-2 lg:px-10">

      <div class="group relative overflow-hidden border-[3px] border-[#07556A] p-6 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:p-8 lg:p-12">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-6 inline-flex border-[3px] border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-8 sm:p-4">
          <svg width="36" height="36" class="sm:w-[44px] sm:h-[44px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <div class="mb-3 sm:mb-4 flex items-start justify-between">
          <h3 class="text-xl sm:text-[2rem] font-black tracking-[-.03em]">CÓDIGO</h3>
          <span class="border-2 border-[#07556A] px-2.5 py-0.5 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:px-3 sm:py-1 sm:text-[.65rem]">01</span>
        </div>
        <p class="text-xs leading-[1.65] text-[#91B5BD] sm:text-sm md:text-base">Armazene e versione códigos dos seus robôs. Nunca mais perca uma linha de código importante entre turmas. Suporte a Arduino, Python e C++.</p>
      </div>

      <!-- COMPONENTES -->
      <div class="group relative overflow-hidden border-[3px] border-[#07556A] p-6 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:p-8 lg:p-12">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-6 inline-flex border-[3px] border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-8 sm:p-4">
          <svg width="36" height="36" class="sm:w-[44px] sm:h-[44px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
        </div>
        <div class="mb-3 sm:mb-4 flex items-start justify-between">
          <h3 class="text-xl sm:text-[2rem] font-black tracking-[-.03em]">COMPONENTES</h3>
          <span class="border-2 border-[#07556A] px-2.5 py-0.5 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:px-3 sm:py-1 sm:text-[.65rem]">02</span>
        </div>
        <p class="text-xs leading-[1.65] text-[#91B5BD] sm:text-sm md:text-base">Catalogue sensores, motores e atuadores com imagens e especificações técnicas. Construa uma biblioteca reutilizável para todo o campus.</p>
      </div>

      <!-- EQUIPES -->
      <div class="group relative overflow-hidden border-[3px] border-[#07556A] p-6 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:p-8 lg:p-12">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-6 inline-flex border-[3px] border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-8 sm:p-4">
          <svg width="36" height="36" class="sm:w-[44px] sm:h-[44px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="mb-3 sm:mb-4 flex items-start justify-between">
          <h3 class="text-xl sm:text-[2rem] font-black tracking-[-.03em]">EQUIPES</h3>
          <span class="border-2 border-[#07556A] px-2.5 py-0.5 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:px-3 sm:py-1 sm:text-[.65rem]">03</span>
        </div>
        <p class="text-xs leading-[1.65] text-[#91B5BD] sm:text-sm md:text-base">Gerencie equipes por campus. Controle de acesso granular por roles — professor controla tudo, aluno contribui, visitante visualiza.</p>
      </div>

      <!-- FÓRUM -->
      <div class="group relative overflow-hidden border-[3px] border-[#07556A] p-6 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:p-8 lg:p-12">
        <div class="absolute top-0 left-0 h-1 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="mb-6 inline-flex border-[3px] border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-8 sm:p-4">
          <svg width="36" height="36" class="sm:w-[44px] sm:h-[44px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="mb-3 sm:mb-4 flex items-start justify-between">
          <h3 class="text-xl sm:text-[2rem] font-black tracking-[-.03em]">FÓRUM</h3>
          <span class="border-2 border-[#07556A] px-2.5 py-0.5 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:px-3 sm:py-1 sm:text-[.65rem]">04</span>
        </div>
        <p class="text-xs leading-[1.65] text-[#91B5BD] sm:text-sm md:text-base">Discuta, pergunte e compartilhe conhecimento. Conecte equipes de diferentes campi do IFPR. Dúvidas de hardware, software e estratégias de competição.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       5. PROJETOS
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-[#07556A] bg-transparent">

    <div class="flex flex-col items-start justify-between gap-4 border-b-[3px] border-[#07556A] bg-[rgba(8,43,58,.25)] px-4 pb-6 pt-10 sm:flex-row sm:items-end sm:gap-8 sm:px-8 sm:pb-10 sm:pt-16 lg:px-10">
      <div>
        <div class="mb-2 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem]">PROJETOS RECENTES</div>
        <h2 class="text-[clamp(2rem,5vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
          DA<br>COMUNIDADE
        </h2>
      </div>
      <a href="#" class="border-b-2 border-[#07556A] pb-1 text-xs font-bold uppercase tracking-[.08em] text-[#91B5BD] no-underline transition-colors duration-200 hover:border-[#13F3F7] hover:text-[#54FBFE] sm:text-sm">
        VER TODOS →
      </a>
    </div>

    <div>
      <div class="group relative grid cursor-pointer grid-cols-1 items-start gap-3 overflow-hidden border-b border-[#07556A] px-4 py-5 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:grid-cols-[3rem_1fr_auto] sm:items-center sm:gap-6 sm:px-8 sm:py-7 md:grid-cols-[4rem_1fr_auto] md:gap-8 lg:px-10">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-2xl font-black leading-none text-[#4E6B72] sm:text-[2.5rem]">01</div>
        <div>
          <div class="mb-1 text-base sm:text-[1.2rem] font-black tracking-[-.02em]">Robô Seguidor de Linha</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:tracking-[.25em]">Equipe Alpha &nbsp;·&nbsp; Cascavel &nbsp;·&nbsp; Arduino</div>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
          <span class="border-2 border-[#13F3F7] bg-[rgba(19,243,247,.12)] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.1em] text-[#13F3F7] sm:px-4 sm:py-1.5 sm:text-[.7rem]">PÚBLICO</span>
          <span class="text-[.75rem] font-bold text-[#91B5BD] sm:text-[.85rem]">12 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-1 items-start gap-3 overflow-hidden border-b border-[#07556A] px-4 py-5 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:grid-cols-[3rem_1fr_auto] sm:items-center sm:gap-6 sm:px-8 sm:py-7 md:grid-cols-[4rem_1fr_auto] md:gap-8 lg:px-10">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-2xl font-black leading-none text-[#4E6B72] sm:text-[2.5rem]">02</div>
        <div>
          <div class="mb-1 text-base sm:text-[1.2rem] font-black tracking-[-.02em]">Braço Robótico Arduino</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:tracking-[.25em]">Equipe Beta &nbsp;·&nbsp; Londrina &nbsp;·&nbsp; Servo Motors</div>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
          <span class="border-2 border-[#00D6B5] bg-[rgba(0,214,181,.12)] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.1em] text-[#00D6B5] sm:px-4 sm:py-1.5 sm:text-[.7rem]">EQUIPE</span>
          <span class="text-[.75rem] font-bold text-[#91B5BD] sm:text-[.85rem]">8 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-1 items-start gap-3 overflow-hidden border-b border-[#07556A] px-4 py-5 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:grid-cols-[3rem_1fr_auto] sm:items-center sm:gap-6 sm:px-8 sm:py-7 md:grid-cols-[4rem_1fr_auto] md:gap-8 lg:px-10">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-2xl font-black leading-none text-[#4E6B72] sm:text-[2.5rem]">03</div>
        <div>
          <div class="mb-1 text-base sm:text-[1.2rem] font-black tracking-[-.02em]">Robô Sumô 500g</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:tracking-[.25em]">Equipe Gamma &nbsp;·&nbsp; Curitiba &nbsp;·&nbsp; C++</div>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
          <span class="border-2 border-[#13F3F7] bg-[rgba(19,243,247,.12)] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.1em] text-[#13F3F7] sm:px-4 sm:py-1.5 sm:text-[.7rem]">PÚBLICO</span>
          <span class="text-[.75rem] font-bold text-[#91B5BD] sm:text-[.85rem]">19 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-1 items-start gap-3 overflow-hidden border-b border-[#07556A] px-4 py-5 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:grid-cols-[3rem_1fr_auto] sm:items-center sm:gap-6 sm:px-8 sm:py-7 md:grid-cols-[4rem_1fr_auto] md:gap-8 lg:px-10">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-2xl font-black leading-none text-[#4E6B72] sm:text-[2.5rem]">04</div>
        <div>
          <div class="mb-1 text-base sm:text-[1.2rem] font-black tracking-[-.02em]">Controlador PID para Seguidor</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:tracking-[.25em]">Equipe Alpha &nbsp;·&nbsp; Cascavel &nbsp;·&nbsp; Python</div>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
          <span class="border-2 border-[#07556A] bg-[#082B3A] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.1em] text-[#4E6B72] sm:px-4 sm:py-1.5 sm:text-[.7rem]">PRIVADO</span>
          <span class="text-[.75rem] font-bold text-[#91B5BD] sm:text-[.85rem]">6 componentes</span>
        </div>
      </div>

      <div class="group relative grid cursor-pointer grid-cols-1 items-start gap-3 overflow-hidden px-4 py-5 transition-colors duration-[250ms] hover:bg-[rgba(7,85,106,.06)] sm:grid-cols-[3rem_1fr_auto] sm:items-center sm:gap-6 sm:px-8 sm:py-7 md:grid-cols-[4rem_1fr_auto] md:gap-8 lg:px-10">
        <div class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-[#13F3F7] transition-transform duration-[600ms] ease-[cubic-bezier(.65,.05,0,1)] group-hover:scale-x-100"></div>
        <div class="font-['Barlow_Condensed'] text-2xl font-black leading-none text-[#4E6B72] sm:text-[2.5rem]">05</div>
        <div>
          <div class="mb-1 text-base sm:text-[1.2rem] font-black tracking-[-.02em]">Visão Computacional com OpenCV</div>
          <div class="text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD] sm:tracking-[.25em]">Equipe Delta &nbsp;·&nbsp; Londrina &nbsp;·&nbsp; Python</div>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
          <span class="border-2 border-[#13F3F7] bg-[rgba(19,243,247,.12)] px-2.5 py-1 text-[.65rem] font-bold uppercase tracking-[.1em] text-[#13F3F7] sm:px-4 sm:py-1.5 sm:text-[.7rem]">PÚBLICO</span>
          <span class="text-[.75rem] font-bold text-[#91B5BD] sm:text-[.85rem]">11 componentes</span>
        </div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       6. SOBRE
  ═══════════════════════════════════════ -->
  <section class="grid grid-cols-1 border-b-[3px] border-[#07556A] lg:grid-cols-2">

    <!-- left col: big title -->
    <div class="relative overflow-hidden border-b-[3px] border-[#07556A] px-6 py-10 sm:px-10 sm:py-16 lg:border-b-0 lg:border-r-[3px] lg:px-12 lg:py-20">
      <div class="absolute -bottom-16 -right-16 h-[15rem] w-[15rem] rotate-[20deg] border-[3px] border-[rgba(7,85,106,.15)] sm:h-[20rem] sm:w-[20rem]"></div>
      <div class="mb-4 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:mb-6 sm:text-[.65rem]">SOBRE O PROJETO</div>
      <h2 class="relative z-[1] text-[clamp(2.2rem,6vw,6rem)] font-black tracking-[-.04em] leading-[.92]">
        POR QUE<br>O
        <span class="font-['Orbitron'] text-[#13F3F7]">ROBO<wbr>DRIVE</span><br>
        EXISTE?
      </h2>
    </div>

    <div class="flex flex-col justify-between gap-8 sm:gap-12 px-6 sm:px-10 lg:px-12 py-10 sm:py-16 lg:py-20">
      <div class="flex flex-col gap-4 sm:gap-6">
        <p class="text-base leading-[1.7] text-[#91B5BD] sm:text-[1.15rem]">
          O RoboDrive nasceu para resolver o problema da <strong class="text-[#F2FEFE]">dispersão de conhecimento</strong> em projetos de robótica educacional no IFPR.
        </p>
        <p class="text-xs leading-[1.7] text-[#91B5BD] sm:text-base">
          Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos pessoais, grupos de WhatsApp e pen drives, perde-se a capacidade de reaproveitar esse conhecimento em turmas futuras — e todo o esforço se perde com a formatura.
        </p>
        <p class="text-xs leading-[1.7] text-[#91B5BD] sm:text-base">
          Com o RoboDrive, você pode facilmente gerenciar equipes, ou entrar em uma e documentar projetos, sua equipe constrói um acervo técnico duradouro.
        </p>
      </div>

      <div class="flex flex-wrap gap-2.5 sm:gap-3">
        <span class="border-[3px] border-[#13F3F7] bg-[#13F3F7] px-5 py-2.5 text-xs font-black uppercase tracking-[.06em] text-[#000505] sm:px-8 sm:py-3.5 sm:text-[.9rem]">CENTRALIZE</span>
        <span class="border-[3px] border-[#00D6B5] bg-[#00D6B5] px-5 py-2.5 text-xs font-black uppercase tracking-[.06em] text-[#000505] sm:px-8 sm:py-3.5 sm:text-[.9rem]">ORGANIZE</span>
        <span class="border-[3px] border-[#13F3F7] bg-[#13F3F7] px-5 py-2.5 text-xs font-black uppercase tracking-[.06em] text-[#000505] sm:px-8 sm:py-3.5 sm:text-[.9rem]">REUTILIZE</span>
      </div>
    </div>

  </section>


    <!-- ═══════════════════════════════════════
      7. COMO FUNCIONA
    ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-[#07556A] section-07">

    <div class="border-b-[3px] border-[#07556A] px-4 pb-6 pt-10 sm:px-8 sm:pb-10 sm:pt-16 lg:px-10">
      <div class="mb-2 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem]">PRIMEIROS PASSOS</div>
      <h2 class="text-[clamp(2rem,5vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
        COMO <span class="text-[#13F3F7]">FUNCIONA</span>
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">

      <div class="relative border-b-[3px] border-[#07556A] px-6 py-8 sm:px-8 sm:py-12 md:border-b-0 md:border-r-[3px] lg:px-10">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#13F3F7]"></div>
        <div class="mb-3 font-['Barlow_Condensed'] text-[3.5rem] font-black leading-none text-[#13F3F7] opacity-[0.25] sm:mb-4 sm:text-[5rem]">01</div>
        <div class="mb-4 inline-flex border-2 border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-6 sm:p-3.5">
          <svg width="28" height="28" class="sm:w-[32px] sm:h-[32px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="mb-2.5 sm:mb-3.5 text-xl sm:text-[1.6rem] font-black tracking-[-.03em]">CADASTRE-SE</h3>
        <p class="text-xs leading-[1.7] text-[#91B5BD] sm:text-sm md:text-[.95rem]">Crie sua conta com e-mail institucional do IFPR. Professores validam membros e gerenciam equipes por campus.</p>
      </div>

      <div class="relative border-b-[3px] border-[#07556A] px-6 py-8 sm:px-8 sm:py-12 md:border-b-0 md:border-r-[3px] lg:px-10">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#13F3F7]"></div>
        <div class="mb-3 font-['Barlow_Condensed'] text-[3.5rem] font-black leading-none text-[#13F3F7] opacity-[0.25] sm:mb-4 sm:text-[5rem]">02</div>
        <div class="mb-4 inline-flex border-2 border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-6 sm:p-3.5">
          <svg width="28" height="28" class="sm:w-[32px] sm:h-[32px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
        </div>
        <h3 class="mb-2.5 sm:mb-3.5 text-xl sm:text-[1.6rem] font-black tracking-[-.03em]">CRIE PROJETOS</h3>
        <p class="text-xs leading-[1.7] text-[#91B5BD] sm:text-sm md:text-[.95rem]">Documente robôs com código, componentes, imagens e descrições técnicas. Defina a visibilidade: equipe, campus ou público.</p>
      </div>

      <div class="relative px-6 sm:px-8 lg:px-10 py-8 sm:py-12">
        <div class="absolute top-0 left-0 h-1 w-full bg-[#13F3F7]"></div>
        <div class="mb-3 font-['Barlow_Condensed'] text-[3.5rem] font-black leading-none text-[#13F3F7] opacity-[0.25] sm:mb-4 sm:text-[5rem]">03</div>
        <div class="mb-4 inline-flex border-2 border-[#13F3F7] p-3 text-[#13F3F7] sm:mb-6 sm:p-3.5">
          <svg width="28" height="28" class="sm:w-[32px] sm:h-[32px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3 class="mb-2.5 sm:mb-3.5 text-xl sm:text-[1.6rem] font-black tracking-[-.03em]">COLABORE</h3>
        <p class="text-xs leading-[1.7] text-[#91B5BD] sm:text-sm md:text-[.95rem]">Compartilhe com sua equipe, publique para o campus ou abra para toda a comunidade IFPR. Use o fórum para tirar dúvidas.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       8. QUEM USA
  ═══════════════════════════════════════ -->
  <section class="border-b-[3px] border-[#07556A]">

    <div class="border-b-[3px] border-[#07556A] px-4 pb-6 pt-10 sm:px-8 sm:pb-10 sm:pt-16 lg:px-10">
      <div class="mb-2 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:mb-3 sm:text-[.65rem]">CONTROLE DE ACESSO</div>
      <h2 class="text-[clamp(2rem,5vw,5rem)] font-black tracking-[-.04em] leading-[.95]">
        QUEM <span class="text-[#13F3F7]">USA</span>
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">

      <div class="relative border-b-[3px] border-[#07556A] p-6 sm:p-8 md:border-b-0 md:border-r-[3px] lg:p-12">
        <div class="mb-4 inline-block border-2 border-[#13F3F7] px-3.5 py-1 text-[.65rem] font-bold uppercase tracking-[.15em] text-[#13F3F7] sm:mb-6 sm:text-[.7rem]">COORDENADOR</div>
        <h3 class="mb-3 sm:mb-4 text-xl sm:text-[1.75rem] font-black tracking-[-.03em]">Controle Total</h3>
        <ul class="list-none p-0 text-xs leading-[1.8] text-[#91B5BD] sm:text-sm sm:leading-[2] md:text-[.95rem]">
          <li>→ &nbsp;Criar e gerenciar equipes</li>
          <li>→ &nbsp;Validar alunos no campus</li>
          <li>→ &nbsp;Definir visibilidade de projetos</li>
          <li>→ &nbsp;Acesso a todos os projetos da equipe</li>
          <li>→ &nbsp;Moderar fórum</li>
        </ul>
      </div>

      <div class="relative border-b-[3px] border-[#07556A] p-6 sm:p-8 md:border-b-0 md:border-r-[3px] lg:p-12">
        <div class="mb-4 inline-block border-2 border-[#00D6B5] px-3.5 py-1 text-[.65rem] font-bold uppercase tracking-[.15em] text-[#00D6B5] sm:mb-6 sm:text-[.7rem]">CONTRIBUIDOR</div>
        <h3 class="mb-3 sm:mb-4 text-xl sm:text-[1.75rem] font-black tracking-[-.03em]">Faz a Mágica Acontecer</h3>
        <ul class="list-none p-0 text-xs leading-[1.8] text-[#91B5BD] sm:text-sm sm:leading-[2] md:text-[.95rem]">
          <li>→ &nbsp;Criar e documentar projetos</li>
          <li>→ &nbsp;Usar componentes da biblioteca</li>
          <li>→ &nbsp;Colaborar com a equipe</li>
          <li>→ &nbsp;Participar do fórum</li>
          <li>→ &nbsp;Ver projetos públicos de outros campi</li>
        </ul>
      </div>

      <div class="relative p-6 sm:p-8 lg:p-12">
        <div class="mb-4 inline-block border-2 border-[#07556A] px-3.5 py-1 text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72] sm:mb-6 sm:text-[.7rem]">VISITANTE</div>
        <h3 class="mb-3 sm:mb-4 text-xl sm:text-[1.75rem] font-black tracking-[-.03em]">Leitor</h3>
        <ul class="list-none p-0 text-xs leading-[1.8] text-[#4E6B72] sm:text-sm sm:leading-[2] md:text-[.95rem]">
          <li>→ &nbsp;Ver projetos públicos</li>
          <li>→ &nbsp;Explorar componentes públicos</li>
          <li>→ &nbsp;Ler threads do fórum</li>
          <li class="text-[#4E6B72]">✕ &nbsp;Criar projetos</li>
          <li class="text-[#4E6B72]">✕ &nbsp;Participar de equipes</li>
        </ul>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       9. MARQUEE 2
  ═══════════════════════════════════════ -->
  <div class="marquee--primary relative ml-[calc(50%-50vw)] my-1 flex w-screen select-none gap-4 overflow-hidden" aria-label="Marquee de navegação rápida">
    <div class="marquee__track flex w-max items-center [will-change:transform]">
      <ul class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-2 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-3 [&>li]:py-2.5 [&>li]:text-xs [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-[#F2FEFE] sm:gap-4 sm:[&>li]:px-6 sm:[&>li]:py-[.95rem] sm:[&>li]:text-base">
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Professor</span> orientador</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Aluno</span> desenvolvedor</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Equipe</span> colaborativa</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Campus</span> integrado</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Repositório</span> central</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Projetos</span> publicados</li>
      </ul>

      <ul aria-hidden="true" class="marquee__content m-0 flex w-max shrink-0 list-none items-center gap-4 p-0 [&>li]:flex-none [&>li]:whitespace-nowrap [&>li]:px-3 [&>li]:py-2.5 [&>li]:text-xs [&>li]:font-black [&>li]:uppercase [&>li]:tracking-[.12em] [&>li]:text-[#F2FEFE] sm:[&>li]:px-6 sm:[&>li]:py-[.95rem] sm:[&>li]:text-base">
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Professor</span> orientador</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Aluno</span> desenvolvedor</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Equipe</span> colaborativa</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Campus</span> integrado</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Repositório</span> central</li>
        <li class="text-[#F2FEFE]"><span class="text-[#13F3F7]">Projetos</span> publicados</li>
      </ul>
    </div>
  </div>

  <div class="h-[3px] w-full bg-[#07556A]"></div>

  <!-- ═══════════════════════════════════════
       10. FIM
  ═══════════════════════════════════════ -->
  <section class="relative flex min-h-[50vh] sm:min-h-[60vh] flex-col justify-center overflow-hidden">

    <div class="relative z-[1] px-4 sm:px-8 lg:px-10 py-12 sm:py-24 text-center">
      <div class="mb-4 text-[.6rem] font-bold uppercase tracking-[.25em] text-[#91B5BD] sm:mb-6 sm:text-[.65rem]">FAÇA PARTE DA COMUNIDADE</div>
      <h2 class="mb-6 sm:mb-10 text-[clamp(2.5rem,8vw,9rem)] font-black tracking-[-.04em] leading-[.9]">
        PRONTO PARA<br>
        <span class="text-[#13F3F7]">COMEÇAR?</span>
      </h2>
      <p class="mx-auto mb-8 max-w-[38ch] text-sm leading-[1.65] text-[#91B5BD] sm:mb-12 sm:text-[1.15rem]">
        Junte-se ao repositório central de robótica e pare de perder conhecimento.
      </p>
      <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-3 sm:gap-5">
        <a href="<?= URL_BASE ?>/cadastro" class="inline-flex w-full items-center justify-center gap-3 border-[3px] border-[#13F3F7] bg-[#13F3F7] px-6 py-3 text-center text-sm font-black uppercase tracking-[.06em] text-[#000505] no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] hover:bg-[#54FBFE] sm:w-auto sm:px-10 sm:py-[1.1rem] sm:text-base">
          CRIAR CONTA GRÁTIS
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="<?= URL_BASE ?>/login" class="inline-flex w-full items-center justify-center gap-3 border-[3px] border-[#13F3F7] bg-transparent px-6 py-3 text-center text-sm font-black uppercase tracking-[.06em] text-[#13F3F7] no-underline transition-[filter,transform] duration-200 hover:translate-y-[-2px] sm:w-auto sm:px-10 sm:py-[1.1rem] sm:text-base">JÁ TENHO CONTA</a>
      </div>
    </div>

    <div class="h-[3px] w-full bg-[#13F3F7]"></div>
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
