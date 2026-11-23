<div class="space-y-6 sm:space-y-7 pt-2">
    
    <div class="relative">
        <label for="email" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
            Email
        </label>

        <input type="text" name="email" id="email" class="h-11 sm:h-12 w-full border-2 border-[#0795A5] bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none transition-colors duration-200 placeholder:text-[#91B5BD] focus:border-[#13F3F7] focus:ring-0" value="<?= isset($usuario["email"]) ? $usuario["email"]: "" ?>">
    </div>
    
    <div class="relative">
        <label for="senha" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
            Senha
        </label>

        <div class="password-container flex w-full border-2 border-[#07556A] transition-colors duration-200 focus-within:border-[#13F3F7]">
            <input type="password" name="senha" id="senha" class="password h-11 sm:h-12 min-w-0 flex-1 bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none">

            <button type="button" onclick="passowrdChange()" class="rd-btn-icon password-divider sm:h-12" aria-label="Alternar visibilidade da senha">
                <img class="passwordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
    </div>
    <div class="text-right">
        <a href="<?= URL_BASE ?>/recuperar-senha" class="text-xs text-[#91B5BD] hover:text-[#13F3F7] transition underline">
            Esqueceu sua senha?
        </a>
    </div>
</div>

<div class="pt-2 sm:pt-3">
    <button type="submit" class="rd-btn rd-btn-primary w-full">Entrar</button>
</div>