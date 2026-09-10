<?php
$titulo = "Cadastro";
include_once(__DIR__ . "/../elements/header.php");
?>
<section class="relative z-[0] flex">
    <div class="fixed inset-0 z-0 h-screen w-screen overflow-hidden bg-[#000505] pointer-events-none" aria-hidden="true">
        <canvas id="myCanvas" class="h-full w-full"></canvas>
    </div>

</section>

<main class="relative z-[2] flex min-h-[90dvh] flex-1 items-center justify-center overflow-hidden px-4 py-6 sm:py-10 text-[#F2FEFE]">
    <section class="relative my-auto w-full max-w-md border-[3px] border-[#07556A] bg-[#06141C] p-5 sm:p-8 md:p-10 shadow-[8px_8px_0_#07556A] sm:shadow-[12px_12px_0_#07556A]" aria-labelledby="cadastro-title">
        <div class="absolute left-0 top-0 h-[3px] w-full bg-[#13F3F7]"></div>
        <div class="mb-6 sm:mb-10">
            <p class="mb-2 sm:mb-3 text-[.6rem] sm:text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">NOVA CONTA</p>
            <h1 id="cadastro-title" class="font-['Orbitron'] text-[clamp(1.75rem,6vw,3rem)] font-black leading-none tracking-[-.04em]">
                CRIAR <span class="text-[#13F3F7]">CONTA</span>
            </h1>
            <p class="mt-3 sm:mt-4 max-w-[36ch] text-xs sm:text-sm leading-5 sm:leading-6 text-[#91B5BD]">Comece a documentar e compartilhar seus projetos de robótica.</p>
        </div>

        <div>
            <form action="<?= URL_BASE ?>/usuario/salvar" method="post" class="w-full">
                <?php include_once(__DIR__ . "/elements/form.php") ?>
            </form>

            <p class="mt-6 sm:mt-7 border-t border-[#07556A] pt-4 sm:pt-5 text-center text-xs sm:text-sm text-[#91B5BD]">
                Já possui conta?
                <a href="<?= URL_BASE . "/login" ?>" class="ml-1 font-bold uppercase tracking-[.08em] text-[#F2FEFE] underline decoration-[#13F3F7] decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#54FBFE]">Entrar</a>
            </p>
        </div>
    </section>
</main>
<script src="<?= JS_URL_BASE ?>/password.js"></script> 
<script src="<?= JS_URL_BASE ?>/script.js"></script>
<script src="<?= JS_URL_BASE ?>/metaballs-wallpaper.js"></script>
<?php
include_once(__DIR__ . "/../elements/footer.php");
