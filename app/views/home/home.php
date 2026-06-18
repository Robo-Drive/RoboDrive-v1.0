<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ROBODRIVE</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&family=Orbitron:wght@700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scrollbar-width: none; }
    body::-webkit-scrollbar { display: none; }

    body {
      background: #000;
      color: #fff;
      font-family: 'Space Grotesk', sans-serif;
      overflow-x: hidden;
      position: relative;
    }

    .video-bg {
      position: fixed;
      inset: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
      pointer-events: none;
      overflow: hidden;
      background: #000;
    }

    .video-bg iframe {
      width: 100vw;
      height: 100vh;
      border: 0;
      position: absolute;
      top: 0;
      left: 0;
      transform: scale(1.15);
      transform-origin: center center;
    }

    .video-bg-overlay {
      position: fixed;
      inset: 0;
      z-index: 1;
      pointer-events: none;
      background:
        linear-gradient(180deg, rgba(0, 0, 0, 0.18) 0%, rgba(0, 0, 0, 0.48) 45%, rgba(0, 0, 0, 0.72) 100%),
        radial-gradient(circle at top right, rgba(255, 45, 45, 0.12), transparent 34%),
        radial-gradient(circle at bottom left, rgba(255, 45, 45, 0.08), transparent 28%);
    }

    .page-content {
      position: relative;
      z-index: 2;
    }

    /* ── MARQUEE ── */
    @keyframes marquee-left {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
    @keyframes marquee-right {
      from { transform: translateX(-50%); }
      to   { transform: translateX(0); }
    }
    .marquee-track-left  { animation: marquee-left  18s linear infinite; display: flex; width: max-content; }
    .marquee-track-right { animation: marquee-right 22s linear infinite; display: flex; width: max-content; }

    /* ── ORBITRON only for branding ── */
    .brand { font-family: 'Orbitron', sans-serif; }

    /* ── BARLOW for impact numbers ── */
    .impact { font-family: 'Barlow Condensed', sans-serif; font-weight: 900; }

    /* ── EYEBROW ── */
    .eyebrow {
      font-size: .65rem;
      font-weight: 700;
      letter-spacing: .25em;
      text-transform: uppercase;
      color: #a3a3a3;
    }

    /* ── GRID BG ── */
    .grid-bg {
      background-image: linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size: 72px 72px;
    }

    /* ── PROJECT CARD HOVER ── */
    .proj-card { transition: background .25s; }
    .proj-card:hover { background: rgba(255,45,45,.06); }
    .proj-card .tag { transition: background .2s; }

    /* ── RED LINE anim ── */
    .line-grow {
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .6s cubic-bezier(.65,.05,0,1);
    }
    .proj-card:hover .line-grow { transform: scaleX(1); }

    /* ── STAT hover ── */
    .stat-cell { transition: background .2s; }
    .stat-cell:hover { background: rgba(255,45,45,.08); }

    /* ── BUTTON ── */
    .btn-primary {
      display: inline-flex; align-items: center; gap: .75rem;
      padding: 1.1rem 2.5rem;
      background: #FF2D2D; border: 3px solid #fff;
      font-weight: 900; font-size: 1rem; letter-spacing: .06em; text-transform: uppercase;
      text-decoration: none; color: #fff;
      transition: filter .2s, transform .2s;
    }
    .btn-primary:hover { filter: brightness(1.15); transform: translateY(-2px); }
    .btn-ghost {
      display: inline-flex; align-items: center; gap: .75rem;
      padding: 1.1rem 2.5rem;
      border: 3px solid #fff;
      font-weight: 900; font-size: 1rem; letter-spacing: .06em; text-transform: uppercase;
      text-decoration: none; color: #fff;
      transition: background .2s, color .2s;
    }
    .btn-ghost:hover { background: #fff; color: #000; }

    /* ── SECTION DIVIDER ── */
    .divider-red { width: 100%; height: 3px; background: #FF2D2D; }
    .divider-white { width: 100%; height: 3px; background: #fff; }
  </style>
</head>

<body>

  <div class="video-bg h-screen w-screen" aria-hidden="true">
    <iframe
      src="https://www.youtube.com/embed/B5aY6jblH9o?autoplay=1&mute=1&controls=0&loop=1&playlist=B5aY6jblH9o&playsinline=1&rel=0&modestbranding=1&showinfo=0"
      title="Infinite Black and Red Abstract Loop Background Visuals | 2 Hours 4K 60fps"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      referrerpolicy="strict-origin-when-cross-origin"
      style="pointer-events:none; filter:brightness(0.4) contrast(1.2);"
      class="h-screen w-screen"
      allowfullscreen>
    </iframe>
  </div>

  <div class="video-bg-overlay" aria-hidden="true"></div>

  <div class="page-content">

  <!-- ═══════════════════════════════════════
       1. HERO — fullscreen imersivo
  ═══════════════════════════════════════ -->
  <section style="min-height:100svh; position:relative; display:flex; flex-direction:column; justify-content:flex-end; overflow:hidden; border-bottom: 3px solid #fff;">

    <!-- grid background -->
    <div class="grid-bg" style="position:absolute;inset:0;"></div>

    <!-- noise layer -->
    <div style="position:absolute;inset:0;opacity:.03;background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22/></svg>');"></div>

    <!-- deco shapes -->
    <div style="position:absolute;top:8%;right:6%;width:22vw;height:22vw;border:3px solid rgba(255,45,45,.25);transform:rotate(15deg);"></div>
    <div style="position:absolute;top:18%;right:12%;width:12vw;height:12vw;background:#FF2D2D;opacity:.12;"></div>
    <div style="position:absolute;bottom:15%;left:4%;width:8vw;height:8vw;border:3px solid rgba(255,255,255,.12);transform:rotate(-8deg);"></div>

    <!-- eyebrow -->
    <div style="position:absolute;top:2.5rem;left:2.5rem;" class="eyebrow">
      IFPR &nbsp;·&nbsp; SISTEMA DE GESTÃO &nbsp;·&nbsp; 2025
    </div>

    <!-- counter top-right -->
    <div style="position:absolute;top:2.2rem;right:2.5rem;text-align:right;">
      <div class="eyebrow" style="margin-bottom:.3rem;">PROJETOS ATIVOS</div>
      <div class="impact" style="font-size:2.5rem;color:#FF2D2D;line-height:1;">24+</div>
    </div>

    <!-- main content -->
    <div style="position:relative;z-index:1;padding:0 2.5rem 5rem;">
      <div class="eyebrow" style="margin-bottom:1.2rem;color:#FF2D2D;">
        ● PLATAFORMA EDUCACIONAL DE ROBÓTICA
      </div>

      <h1 class="brand" style="font-size:clamp(3.5rem,11vw,10rem);line-height:.92;letter-spacing:-.03em;margin-bottom:2rem;max-width:14ch;">
        ROBO<span style="color:#FF2D2D;">DRIVE</span>
      </h1>

      <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:2rem;">
        <p style="font-size:clamp(1rem,2vw,1.4rem);color:#a3a3a3;max-width:42ch;line-height:1.55;border-left:4px solid #FF2D2D;padding-left:1.25rem;">
          Repositório central de projetos de robótica educacional do IFPR. Centralize código, componentes, equipes e conhecimento — tudo em um lugar.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;">
          <a href="#" class="btn-primary">
            COMEÇAR AGORA
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#" class="btn-ghost">VER PROJETOS PÚBLICOS</a>
        </div>
      </div>
    </div>

    <div class="divider-red"></div>
  </section>


  <!-- ═══════════════════════════════════════
       2. MARQUEE TICKER
  ═══════════════════════════════════════ -->
  <div style="background:#FF2D2D;border-bottom:3px solid #fff;overflow:hidden;padding:.75rem 0;">
    <div class="marquee-track-left">
      <!-- repeat twice for seamless loop -->
      <span style="white-space:nowrap;font-weight:900;font-size:.9rem;letter-spacing:.12em;text-transform:uppercase;padding:0 2rem;">
        CENTRALIZE &nbsp;·&nbsp; ORGANIZE &nbsp;·&nbsp; REUTILIZE &nbsp;·&nbsp;
        CÓDIGO &nbsp;·&nbsp; COMPONENTES &nbsp;·&nbsp; EQUIPES &nbsp;·&nbsp; FÓRUM &nbsp;·&nbsp;
        ROBÓTICA EDUCACIONAL &nbsp;·&nbsp; IFPR &nbsp;·&nbsp; REPOSITÓRIO &nbsp;·&nbsp;
        CENTRALIZE &nbsp;·&nbsp; ORGANIZE &nbsp;·&nbsp; REUTILIZE &nbsp;·&nbsp;
        CÓDIGO &nbsp;·&nbsp; COMPONENTES &nbsp;·&nbsp; EQUIPES &nbsp;·&nbsp; FÓRUM &nbsp;·&nbsp;
        ROBÓTICA EDUCACIONAL &nbsp;·&nbsp; IFPR &nbsp;·&nbsp; REPOSITÓRIO &nbsp;·&nbsp;
      </span>
    </div>
  </div>


  <!-- ═══════════════════════════════════════
       3. STATS — números grandes
  ═══════════════════════════════════════ -->
  <section style="border-bottom:3px solid #fff;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);">

      <div class="stat-cell" style="padding:3.5rem 2rem;border-right:3px solid #fff;text-align:center;">
        <div class="eyebrow" style="margin-bottom:.75rem;">PROJETOS</div>
        <div class="impact" style="font-size:clamp(4rem,8vw,7rem);line-height:1;color:#FF2D2D;">24+</div>
      </div>

      <div class="stat-cell" style="padding:3.5rem 2rem;border-right:3px solid #fff;text-align:center;">
        <div class="eyebrow" style="margin-bottom:.75rem;">EQUIPES</div>
        <div class="impact" style="font-size:clamp(4rem,8vw,7rem);line-height:1;color:#fff;">8</div>
      </div>

      <div class="stat-cell" style="padding:3.5rem 2rem;border-right:3px solid #fff;text-align:center;">
        <div class="eyebrow" style="margin-bottom:.75rem;">COMPONENTES</div>
        <div class="impact" style="font-size:clamp(4rem,8vw,7rem);line-height:1;color:#FF2D2D;">156</div>
      </div>

      <div class="stat-cell" style="padding:3.5rem 2rem;text-align:center;">
        <div class="eyebrow" style="margin-bottom:.75rem;">CAMPI IFPR</div>
        <div class="impact" style="font-size:clamp(4rem,8vw,7rem);line-height:1;color:#fff;">3</div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       4. FUNCIONALIDADES — feature grid
  ═══════════════════════════════════════ -->
  <section style="border-bottom:3px solid #fff;">

    <!-- section header -->
    <div style="padding:4rem 2.5rem 2.5rem;display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;border-bottom:3px solid #fff;">
      <div>
        <div class="eyebrow" style="margin-bottom:.75rem;">O QUE FAZEMOS</div>
        <h2 style="font-size:clamp(2.5rem,6vw,5rem);font-weight:900;letter-spacing:-.04em;line-height:.95;">
          TUDO QUE<br>
          <span style="color:#0066FF;">VOCÊ PRECISA</span>
        </h2>
      </div>
      <p style="max-width:36ch;color:#a3a3a3;line-height:1.6;font-size:1rem;padding-bottom:.4rem;">
        Quatro pilares que resolvem o problema da dispersão de conhecimento em projetos de robótica educacional.
      </p>
    </div>

    <!-- 2×2 grid -->
    <div style="display:grid;grid-template-columns:repeat(2,1fr);">

      <!-- CÓDIGO -->
      <div class="proj-card" style="padding:3rem;border-right:3px solid #fff;border-bottom:3px solid #fff;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div style="display:inline-flex;padding:1rem;border:3px solid #FF2D2D;color:#FF2D2D;margin-bottom:2rem;">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <h3 style="font-size:2rem;font-weight:900;letter-spacing:-.03em;">CÓDIGO</h3>
          <span class="eyebrow" style="padding:.4rem .8rem;border:2px solid #333;">01</span>
        </div>
        <p style="color:#737373;line-height:1.65;">Armazene e versione códigos dos seus robôs. Nunca mais perca uma linha de código importante entre turmas. Suporte a Arduino, Python e C++.</p>
      </div>

      <!-- COMPONENTES -->
      <div class="proj-card" style="padding:3rem;border-bottom:3px solid #fff;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div style="display:inline-flex;padding:1rem;border:3px solid #FF2D2D;color:#FF2D2D;margin-bottom:2rem;">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <h3 style="font-size:2rem;font-weight:900;letter-spacing:-.03em;">COMPONENTES</h3>
          <span class="eyebrow" style="padding:.4rem .8rem;border:2px solid #333;">02</span>
        </div>
        <p style="color:#737373;line-height:1.65;">Catalogue sensores, motores e atuadores com imagens e especificações técnicas. Construa uma biblioteca reutilizável para todo o campus.</p>
      </div>

      <!-- EQUIPES -->
      <div class="proj-card" style="padding:3rem;border-right:3px solid #fff;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div style="display:inline-flex;padding:1rem;border:3px solid #FF2D2D;color:#FF2D2D;margin-bottom:2rem;">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <h3 style="font-size:2rem;font-weight:900;letter-spacing:-.03em;">EQUIPES</h3>
          <span class="eyebrow" style="padding:.4rem .8rem;border:2px solid #333;">03</span>
        </div>
        <p style="color:#737373;line-height:1.65;">Gerencie equipes por campus. Controle de acesso granular por roles — professor controla tudo, aluno contribui, visitante visualiza.</p>
      </div>

      <!-- FÓRUM -->
      <div class="proj-card" style="padding:3rem;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div style="display:inline-flex;padding:1rem;border:3px solid #FF2D2D;color:#FF2D2D;margin-bottom:2rem;">
          <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <h3 style="font-size:2rem;font-weight:900;letter-spacing:-.03em;">FÓRUM</h3>
          <span class="eyebrow" style="padding:.4rem .8rem;border:2px solid #333;">04</span>
        </div>
        <p style="color:#737373;line-height:1.65;">Discuta, pergunte e compartilhe conhecimento. Conecte equipes de diferentes campi do IFPR. Dúvidas de hardware, software e estratégias de competição.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       5. PROJETOS — card grid (estilo F1 highlights)
  ═══════════════════════════════════════ -->
  <section style="background:#0a0a0a;border-bottom:3px solid #fff;">

    <div style="padding:4rem 2.5rem 2.5rem;display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;border-bottom:3px solid #404040;">
      <div>
        <div class="eyebrow" style="margin-bottom:.75rem;">PROJETOS RECENTES</div>
        <h2 style="font-size:clamp(2.5rem,6vw,5rem);font-weight:900;letter-spacing:-.04em;line-height:.95;">
          DA<br>COMUNIDADE
        </h2>
      </div>
      <a href="#" style="text-decoration:none;color:#a3a3a3;font-size:.9rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;border-bottom:2px solid #404040;padding-bottom:.3rem;white-space:nowrap;transition:color .2s,border-color .2s;" onmouseover="this.style.color='#fff';this.style.borderColor='#fff'" onmouseout="this.style.color='#a3a3a3';this.style.borderColor='#404040'">
        VER TODOS →
      </a>
    </div>

    <!-- project list rows (F1-style) -->
    <div>

      <div class="proj-card" style="display:grid;grid-template-columns:4rem 1fr auto;align-items:center;gap:2rem;padding:1.75rem 2.5rem;border-bottom:1px solid #1a1a1a;cursor:pointer;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;bottom:0;left:0;height:2px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:2.5rem;color:#333;line-height:1;">01</div>
        <div>
          <div style="font-size:1.2rem;font-weight:900;letter-spacing:-.02em;margin-bottom:.35rem;">Robô Seguidor de Linha</div>
          <div class="eyebrow">Equipe Alpha &nbsp;·&nbsp; Campus Cascavel &nbsp;·&nbsp; Arduino</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
          <span style="padding:.35rem .9rem;background:rgba(255,45,45,.15);border:2px solid #FF2D2D;color:#FF2D2D;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">PÚBLICO</span>
          <span style="color:#737373;font-size:.85rem;font-weight:700;">12 componentes</span>
        </div>
      </div>

      <div class="proj-card" style="display:grid;grid-template-columns:4rem 1fr auto;align-items:center;gap:2rem;padding:1.75rem 2.5rem;border-bottom:1px solid #1a1a1a;cursor:pointer;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;bottom:0;left:0;height:2px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:2.5rem;color:#333;line-height:1;">02</div>
        <div>
          <div style="font-size:1.2rem;font-weight:900;letter-spacing:-.02em;margin-bottom:.35rem;">Braço Robótico Arduino</div>
          <div class="eyebrow">Equipe Beta &nbsp;·&nbsp; Campus Londrina &nbsp;·&nbsp; Servo Motors</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
          <span style="padding:.35rem .9rem;background:rgba(0,102,255,.12);border:2px solid #0066FF;color:#0066FF;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">CAMPUS</span>
          <span style="color:#737373;font-size:.85rem;font-weight:700;">8 componentes</span>
        </div>
      </div>

      <div class="proj-card" style="display:grid;grid-template-columns:4rem 1fr auto;align-items:center;gap:2rem;padding:1.75rem 2.5rem;border-bottom:1px solid #1a1a1a;cursor:pointer;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;bottom:0;left:0;height:2px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:2.5rem;color:#333;line-height:1;">03</div>
        <div>
          <div style="font-size:1.2rem;font-weight:900;letter-spacing:-.02em;margin-bottom:.35rem;">Robô Sumô 500g</div>
          <div class="eyebrow">Equipe Gamma &nbsp;·&nbsp; Campus Curitiba &nbsp;·&nbsp; C++</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
          <span style="padding:.35rem .9rem;background:rgba(255,45,45,.15);border:2px solid #FF2D2D;color:#FF2D2D;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">PÚBLICO</span>
          <span style="color:#737373;font-size:.85rem;font-weight:700;">19 componentes</span>
        </div>
      </div>

      <div class="proj-card" style="display:grid;grid-template-columns:4rem 1fr auto;align-items:center;gap:2rem;padding:1.75rem 2.5rem;border-bottom:1px solid #1a1a1a;cursor:pointer;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;bottom:0;left:0;height:2px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:2.5rem;color:#333;line-height:1;">04</div>
        <div>
          <div style="font-size:1.2rem;font-weight:900;letter-spacing:-.02em;margin-bottom:.35rem;">Controlador PID para Seguidor</div>
          <div class="eyebrow">Equipe Alpha &nbsp;·&nbsp; Campus Cascavel &nbsp;·&nbsp; Python</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
          <span style="padding:.35rem .9rem;background:#1a1a1a;border:2px solid #404040;color:#737373;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">EQUIPE</span>
          <span style="color:#737373;font-size:.85rem;font-weight:700;">6 componentes</span>
        </div>
      </div>

      <div class="proj-card" style="display:grid;grid-template-columns:4rem 1fr auto;align-items:center;gap:2rem;padding:1.75rem 2.5rem;cursor:pointer;position:relative;overflow:hidden;">
        <div class="line-grow" style="position:absolute;bottom:0;left:0;height:2px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:2.5rem;color:#333;line-height:1;">05</div>
        <div>
          <div style="font-size:1.2rem;font-weight:900;letter-spacing:-.02em;margin-bottom:.35rem;">Visão Computacional com OpenCV</div>
          <div class="eyebrow">Equipe Delta &nbsp;·&nbsp; Campus Londrina &nbsp;·&nbsp; Python</div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
          <span style="padding:.35rem .9rem;background:rgba(255,45,45,.15);border:2px solid #FF2D2D;color:#FF2D2D;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">PÚBLICO</span>
          <span style="color:#737373;font-size:.85rem;font-weight:700;">11 componentes</span>
        </div>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       6. SOBRE — why it exists (two-col)
  ═══════════════════════════════════════ -->
  <section style="border-bottom:3px solid #fff;display:grid;grid-template-columns:1fr 1fr;">

    <!-- left col: big title -->
    <div style="padding:5rem 3rem;border-right:3px solid #fff;position:relative;overflow:hidden;">
      <div style="position:absolute;bottom:-4rem;right:-4rem;width:20rem;height:20rem;border:3px solid rgba(255,45,45,.15);transform:rotate(20deg);"></div>
      <div class="eyebrow" style="margin-bottom:1.5rem;">SOBRE O PROJETO</div>
      <h2 style="font-size:clamp(3rem,7vw,6rem);font-weight:900;letter-spacing:-.04em;line-height:.92;position:relative;z-index:1;">
        POR QUE<br>O
        <span class="brand" style="color:#FF2D2D;">ROBO<wbr>DRIVE</span><br>
        EXISTE?
      </h2>
    </div>

    <!-- right col: text + tags -->
    <div style="padding:5rem 3rem;display:flex;flex-direction:column;justify-content:space-between;gap:3rem;">
      <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <p style="font-size:1.15rem;color:#d4d4d4;line-height:1.7;">
          O RoboDrive nasceu para resolver o problema da <strong style="color:#fff;">dispersão de conhecimento</strong> em projetos de robótica educacional no IFPR.
        </p>
        <p style="font-size:1rem;color:#737373;line-height:1.7;">
          Quando códigos, documentações e decisões técnicas ficam espalhados em dispositivos pessoais, grupos de WhatsApp e pen drives, perde-se a capacidade de reaproveitar esse conhecimento em turmas futuras — e todo o esforço se perde com a formatura.
        </p>
        <p style="font-size:1rem;color:#737373;line-height:1.7;">
          Com o RoboDrive, professores organizam equipes, alunos documentam projetos e o campus constrói um acervo técnico duradouro.
        </p>
      </div>

      <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
        <span style="padding:.85rem 2rem;background:#FF2D2D;border:3px solid #fff;font-weight:900;letter-spacing:.06em;text-transform:uppercase;font-size:.9rem;">CENTRALIZE</span>
        <span style="padding:.85rem 2rem;background:#0066FF;border:3px solid #fff;font-weight:900;letter-spacing:.06em;text-transform:uppercase;font-size:.9rem;">ORGANIZE</span>
        <span style="padding:.85rem 2rem;background:#FF2D2D;border:3px solid #fff;font-weight:900;letter-spacing:.06em;text-transform:uppercase;font-size:.9rem;">REUTILIZE</span>
      </div>
    </div>

  </section>


  <!-- ═══════════════════════════════════════
       7. COMO FUNCIONA — 3 steps horizontal
  ═══════════════════════════════════════ -->
  <section style="background:#0a0a0a;border-bottom:3px solid #fff;">

    <div style="padding:4rem 2.5rem 2.5rem;border-bottom:3px solid #222;">
      <div class="eyebrow" style="margin-bottom:.75rem;">PRIMEIROS PASSOS</div>
      <h2 style="font-size:clamp(2.5rem,6vw,5rem);font-weight:900;letter-spacing:-.04em;line-height:.95;">
        COMO <span style="color:#0066FF;">FUNCIONA</span>
      </h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);">

      <div style="padding:3rem 2.5rem;border-right:3px solid #222;position:relative;">
        <div style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:5rem;color:#FF2D2D;opacity:.15;line-height:1;margin-bottom:1rem;">01</div>
        <div style="display:inline-flex;padding:.85rem;border:2px solid #FF2D2D;color:#FF2D2D;margin-bottom:1.5rem;">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 style="font-size:1.6rem;font-weight:900;letter-spacing:-.03em;margin-bottom:.85rem;">CADASTRE-SE</h3>
        <p style="color:#525252;line-height:1.7;font-size:.95rem;">Crie sua conta com e-mail institucional do IFPR. Professores validam membros e gerenciam equipes por campus.</p>
      </div>

      <div style="padding:3rem 2.5rem;border-right:3px solid #222;position:relative;">
        <div style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:5rem;color:#FF2D2D;opacity:.15;line-height:1;margin-bottom:1rem;">02</div>
        <div style="display:inline-flex;padding:.85rem;border:2px solid #FF2D2D;color:#FF2D2D;margin-bottom:1.5rem;">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
        </div>
        <h3 style="font-size:1.6rem;font-weight:900;letter-spacing:-.03em;margin-bottom:.85rem;">CRIE PROJETOS</h3>
        <p style="color:#525252;line-height:1.7;font-size:.95rem;">Documente robôs com código, componentes, imagens e descrições técnicas. Defina a visibilidade: equipe, campus ou público.</p>
      </div>

      <div style="padding:3rem 2.5rem;position:relative;">
        <div style="position:absolute;top:0;left:0;height:4px;width:100%;background:#FF2D2D;"></div>
        <div class="impact" style="font-size:5rem;color:#FF2D2D;opacity:.15;line-height:1;margin-bottom:1rem;">03</div>
        <div style="display:inline-flex;padding:.85rem;border:2px solid #FF2D2D;color:#FF2D2D;margin-bottom:1.5rem;">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3 style="font-size:1.6rem;font-weight:900;letter-spacing:-.03em;margin-bottom:.85rem;">COLABORE</h3>
        <p style="color:#525252;line-height:1.7;font-size:.95rem;">Compartilhe com sua equipe, publique para o campus ou abra para toda a comunidade IFPR. Use o fórum para tirar dúvidas.</p>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       8. ROLES — quem usa
  ═══════════════════════════════════════ -->
  <section style="border-bottom:3px solid #fff;">

    <div style="padding:4rem 2.5rem 2.5rem;border-bottom:3px solid #fff;">
      <div class="eyebrow" style="margin-bottom:.75rem;">CONTROLE DE ACESSO</div>
      <h2 style="font-size:clamp(2.5rem,6vw,5rem);font-weight:900;letter-spacing:-.04em;line-height:.95;">
        QUEM <span style="color:#FF2D2D;">USA</span>
      </h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);">

      <div style="padding:3rem;border-right:3px solid #fff;position:relative;">
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.15em;color:#FF2D2D;text-transform:uppercase;margin-bottom:1.5rem;padding:.4rem .9rem;border:2px solid #FF2D2D;display:inline-block;">PROFESSOR</div>
        <h3 style="font-size:1.75rem;font-weight:900;letter-spacing:-.03em;margin-bottom:1rem;">Controle Total</h3>
        <ul style="color:#737373;line-height:2;font-size:.95rem;list-style:none;padding:0;">
          <li>→ &nbsp;Criar e gerenciar equipes</li>
          <li>→ &nbsp;Validar alunos no campus</li>
          <li>→ &nbsp;Definir visibilidade de projetos</li>
          <li>→ &nbsp;Acesso a todos os projetos da equipe</li>
          <li>→ &nbsp;Moderar fórum</li>
        </ul>
      </div>

      <div style="padding:3rem;border-right:3px solid #fff;position:relative;">
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.15em;color:#0066FF;text-transform:uppercase;margin-bottom:1.5rem;padding:.4rem .9rem;border:2px solid #0066FF;display:inline-block;">ALUNO</div>
        <h3 style="font-size:1.75rem;font-weight:900;letter-spacing:-.03em;margin-bottom:1rem;">Contribuidor</h3>
        <ul style="color:#737373;line-height:2;font-size:.95rem;list-style:none;padding:0;">
          <li>→ &nbsp;Criar e documentar projetos</li>
          <li>→ &nbsp;Usar componentes da biblioteca</li>
          <li>→ &nbsp;Colaborar com a equipe</li>
          <li>→ &nbsp;Participar do fórum</li>
          <li>→ &nbsp;Ver projetos públicos de outros campi</li>
        </ul>
      </div>

      <div style="padding:3rem;position:relative;">
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.15em;color:#737373;text-transform:uppercase;margin-bottom:1.5rem;padding:.4rem .9rem;border:2px solid #404040;display:inline-block;">VISITANTE</div>
        <h3 style="font-size:1.75rem;font-weight:900;letter-spacing:-.03em;margin-bottom:1rem;">Leitor</h3>
        <ul style="color:#525252;line-height:2;font-size:.95rem;list-style:none;padding:0;">
          <li>→ &nbsp;Ver projetos públicos</li>
          <li>→ &nbsp;Explorar componentes públicos</li>
          <li>→ &nbsp;Ler threads do fórum</li>
          <li style="color:#404040;">✕ &nbsp;Criar projetos</li>
          <li style="color:#404040;">✕ &nbsp;Participar de equipes</li>
        </ul>
      </div>

    </div>
  </section>


  <!-- ═══════════════════════════════════════
       9. MARQUEE 2 — segundo ticker (branco)
  ═══════════════════════════════════════ -->
  <div style="background:#fff;border-bottom:3px solid #fff;overflow:hidden;padding:.75rem 0;">
    <div class="marquee-track-right">
      <span style="white-space:nowrap;font-weight:900;font-size:.9rem;letter-spacing:.12em;text-transform:uppercase;color:#000;padding:0 2rem;">
        PROFESSOR &nbsp;·&nbsp; ALUNO &nbsp;·&nbsp; EQUIPE &nbsp;·&nbsp; CAMPUS &nbsp;·&nbsp;
        REPOSITÓRIO &nbsp;·&nbsp; CÓDIGO &nbsp;·&nbsp; DOCUMENTAÇÃO &nbsp;·&nbsp;
        ARDUINO &nbsp;·&nbsp; PYTHON &nbsp;·&nbsp; C++ &nbsp;·&nbsp; SENSOR &nbsp;·&nbsp; MOTOR &nbsp;·&nbsp;
        PROFESSOR &nbsp;·&nbsp; ALUNO &nbsp;·&nbsp; EQUIPE &nbsp;·&nbsp; CAMPUS &nbsp;·&nbsp;
        REPOSITÓRIO &nbsp;·&nbsp; CÓDIGO &nbsp;·&nbsp; DOCUMENTAÇÃO &nbsp;·&nbsp;
        ARDUINO &nbsp;·&nbsp; PYTHON &nbsp;·&nbsp; C++ &nbsp;·&nbsp; SENSOR &nbsp;·&nbsp; MOTOR &nbsp;·&nbsp;
      </span>
    </div>
  </div>


  <!-- ═══════════════════════════════════════
       10. CTA FINAL — fullscreen call to action
  ═══════════════════════════════════════ -->
  <section style="position:relative;overflow:hidden;min-height:60vh;display:flex;flex-direction:column;justify-content:center;">
    <div class="grid-bg" style="position:absolute;inset:0;opacity:.6;"></div>
    <div style="position:absolute;top:-5rem;right:-5rem;width:35vw;height:35vw;border:3px solid rgba(255,45,45,.2);transform:rotate(25deg);"></div>
    <div style="position:absolute;bottom:-3rem;left:8%;width:18vw;height:18vw;border:3px solid rgba(255,255,255,.08);transform:rotate(-12deg);"></div>
    <div style="position:absolute;top:20%;right:15%;width:10vw;height:10vw;background:#FF2D2D;opacity:.08;"></div>

    <div style="position:relative;z-index:1;padding:6rem 2.5rem;text-align:center;">
      <div class="eyebrow" style="margin-bottom:1.5rem;">FAÇA PARTE DA COMUNIDADE</div>
      <h2 style="font-size:clamp(3.5rem,10vw,9rem);font-weight:900;letter-spacing:-.04em;line-height:.9;margin-bottom:2.5rem;">
        PRONTO PARA<br>
        <span style="color:#FF2D2D;">COMEÇAR?</span>
      </h2>
      <p style="font-size:1.15rem;color:#737373;max-width:38ch;margin:0 auto 3rem;line-height:1.65;">
        Junte-se ao repositório central de robótica do IFPR e pare de perder conhecimento entre turmas.
      </p>
      <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.25rem;">
        <a href="#" class="btn-primary" style="font-size:1.1rem;padding:1.4rem 3rem;">
          CRIAR CONTA GRÁTIS
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        <a href="#" class="btn-ghost" style="font-size:1.1rem;padding:1.4rem 3rem;">JÁ TENHO CONTA</a>
      </div>
    </div>

    <div class="divider-red"></div>
  </section>

  </div>

</body>
</html>