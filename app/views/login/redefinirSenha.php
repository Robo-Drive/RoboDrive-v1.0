<?php
$titulo = "Nova Senha";
include_once(__DIR__ . "/../elements/header.php");
?>

<main class="relative z-[2] flex min-h-[90dvh] flex-1 items-center justify-center overflow-hidden px-4 py-6 sm:py-10 text-[#F2FEFE]">
    <section class="relative my-auto w-full max-w-md border-[3px] border-[#07556A] bg-[#06141C] p-5 sm:p-8 md:p-10 shadow-[8px_8px_0_#07556A]">
        <div class="absolute left-0 top-0 h-[3px] w-full bg-[#13F3F7]"></div>
        
        <div class="mb-6">
            <p class="mb-2 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">NOVA SENHA</p>
            <h1 class="font-['Orbitron'] text-2xl sm:text-3xl font-black text-white">
                CADASTRAR <span class="text-[#13F3F7]">SENHA</span>
            </h1>
            <p class="mt-2 text-xs text-[#91B5BD]">Informe sua nova senha de acesso.</p>
        </div>

        <form action="<?= URL_BASE ?>/redefinir-senha" autocomplete="off" method="post" class="space-y-5">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

            <div class="relative">
                <label for="senha" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
                    Senha
                </label>

                <div class="password-container flex w-full border-2 border-[#07556A] transition-colors duration-200 focus-within:border-[#13F3F7]">
                    <input type="password" name="senha" id="senha" class="password h-11 sm:h-12 min-w-0 flex-1 bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none">

                    <button type="button" onclick="passowrdChange()" class="password-divider flex h-11 sm:h-12 items-center justify-center border-l-2 border-[#07556A] bg-[#000505] px-3 sm:px-4 text-[#F2FEFE] transition-colors duration-200 focus:outline-none" aria-label="Alternar visibilidade da senha">
                        <img class="passwordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
                    </button>
                </div>
            </div>
            <div class="relative">
                <label for="confirmar_senha" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
                    Confirmar senha
                </label>

                <div class="password-container flex w-full border-2 border-[#07556A] transition-colors duration-200 focus-within:border-[#13F3F7]">
                    <input type="password" name="confirmar_senha" id="confirmar_senha" class="confirmarPassword h-11 sm:h-12 min-w-0 flex-1 bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none">

                    <button type="button" onclick="confirmarPassowrdChange()" class="password-divider flex h-11 sm:h-12 items-center justify-center border-l-2 border-[#07556A] bg-[#000505] px-3 sm:px-4 text-[#F2FEFE] transition-colors duration-200 focus:outline-none" aria-label="Alternar visibilidade da confirmação de senha">
                        <img class="confirmarPasswordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
                    </button>
                </div>
                <p class="confirmarErro mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#13F3F7]">
                </p>
                
            </div>

            <button type="submit" class="rd-btn rd-btn-primary w-full !py-3">
                Salvar Nova Senha
            </button>
        </form>
    </section>
</main>
<script src="<?= js_url("password.js") ?>"></script> 

<?php include_once(__DIR__ . "/../elements/footer.php"); ?>