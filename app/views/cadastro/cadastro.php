<?php
$titulo = "Cadastro";
include_once(__DIR__."/../elements/header.php");
?>
<section class="relative z-[0] flex">
    <div class="fixed inset-0 z-0 h-screen w-screen overflow-hidden bg-black pointer-events-none" aria-hidden="true">
        <canvas id="myCanvas" class="h-full w-full"></canvas>
    </div>

    <div class="fixed inset-0 z-[1] pointer-events-none [background:linear-gradient(180deg,rgba(0,0,0,0.18)_0%,rgba(0,0,0,0.48)_45%,rgba(0,0,0,0.72)_100%),radial-gradient(circle_at_top_right,rgba(255,45,45,0.12),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(255,45,45,0.08),transparent_28%)]" aria-hidden="true"></div>
</section>

<main class="relative z-[2] flex min-h-[calc(100vh-60px)] flex-1 items-center justify-center overflow-hidden px-4 py-6 sm:py-10 text-white">
    <div class="absolute inset-0 opacity-30 [background-image:linear-gradient(rgba(255,255,255,.04)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.04)_1px,transparent_1px)] [background-size:72px_72px]"></div>

    <section class="relative my-auto w-full max-w-md border-[3px] border-white bg-black/90 p-5 sm:p-8 md:p-10 shadow-[8px_8px_0_#FF2D2D] sm:shadow-[12px_12px_0_#FF2D2D]" aria-labelledby="cadastro-title">
        <div class="absolute left-0 top-0 h-[3px] w-full bg-[#FF2D2D]"></div>
        <div class="mb-6 sm:mb-10">
            <p class="mb-2 sm:mb-3 text-[.6rem] sm:text-[.65rem] font-bold uppercase tracking-[.25em] text-[#a3a3a3]">NOVA CONTA</p>
            <h1 id="cadastro-title" class="font-['Orbitron'] text-[clamp(1.75rem,6vw,3rem)] font-black leading-none tracking-[-.04em]">
                CRIAR <span class="text-[#FF2D2D]">CONTA</span>
            </h1>
            <p class="mt-3 sm:mt-4 max-w-[36ch] text-xs sm:text-sm leading-5 sm:leading-6 text-zinc-400">Comece a documentar e compartilhar seus projetos de robótica.</p>
        </div>

        <div>
            <form action="<?= URL_BASE ?>/usuario/salvar" method="post" class="w-full">
                <?php include_once(__DIR__."/elements/form.php") ?>
            </form>

            <p class="mt-6 sm:mt-7 border-t border-[#404040] pt-4 sm:pt-5 text-center text-xs sm:text-sm text-zinc-400">
                Já possui conta?
                <a href="<?= URL_BASE."/login" ?>" class="ml-1 font-bold uppercase tracking-[.08em] text-white underline decoration-[#FF2D2D] decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#FF2D2D]">Entrar</a>
            </p>
        </div>
    </section>
</main>

<script src="<?= JS_URL_BASE ?>/script.js"></script>
<script src="<?= JS_URL_BASE ?>/metaballs-wallpaper.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
