<div class="space-y-6 sm:space-y-7 pt-2">
    
    <div class="relative">
        <label for="email" class="absolute -top-3 left-3 bg-black px-2 z-10 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-white">
            Email
        </label>

        <input type="text" name="email" id="email" class="h-11 sm:h-12 w-full border-2 border-white bg-black px-3.5 sm:px-4 text-sm sm:text-base text-white outline-none transition-colors duration-200 placeholder:text-zinc-600 focus:border-[#FF2D2D] focus:ring-0" value="<?= isset($usuario["email"]) ? $usuario["email"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["email"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#FF2D2D]"><?= $erros["email"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    
    <div class="relative">
        <label for="senha" class="absolute -top-3 left-3 bg-black px-2 z-10 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-white">
            Senha
        </label>

        <div class="password-container flex w-full border-2 border-white transition-colors duration-200">
            <input type="password" name="senha" id="senha" class="password h-11 sm:h-12 min-w-0 flex-1 bg-black px-3.5 sm:px-4 text-sm sm:text-base text-white outline-none">

            <button type="button" onclick="passowrdChange()" class="password-divider flex h-11 sm:h-12 items-center justify-center border-l-2 border-white bg-black px-3 sm:px-4 text-white transition-colors duration-200 focus:outline-none" aria-label="Alternar visibilidade da senha">
                <img class="passwordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["senha"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#FF2D2D]"><?= $erros["senha"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <?php if(isset($erros["login"])): ?>
        <p class="text-xs sm:text-sm font-medium text-[#FF2D2D]"><?= $erros["login"] ?></p>
    <?php endif;?>    
</div>

<div class="pt-2 sm:pt-3">
    <button type="submit" class="inline-flex w-full items-center justify-center border-[3px] border-white bg-[#FF2D2D] px-5 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm font-black uppercase tracking-[.1em] text-white transition-[filter,transform] duration-200 hover:-translate-y-0.5 hover:brightness-[1.15] focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-black">Entrar</button>
</div>
