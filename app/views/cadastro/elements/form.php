<div class="space-y-6 sm:space-y-7 pt-2">
    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
            Nome
        </label>

        <input type="text" name="nome" id="nome" class="h-11 sm:h-12 w-full border-2 border-[#07556A] bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none transition-colors duration-200 placeholder:text-[#4E6B72] focus:border-[#13F3F7] focus:ring-0" value="<?= isset($usuario["nome"]) ? $usuario["nome"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["nome"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#13F3F7]"><?= $erros["nome"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="nome_usuario" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
            Nome de Usuário
        </label>

        <input type="text" name="nome_usuario" id="nome_usuario" class="h-11 sm:h-12 w-full border-2 border-[#07556A] bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none transition-colors duration-200 placeholder:text-[#4E6B72] focus:border-[#13F3F7] focus:ring-0" value="<?= isset($usuario["nome_usuario"]) ? $usuario["nome_usuario"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["nome_usuario"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#13F3F7]"><?= $erros["nome_usuario"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="email" class="absolute -top-3 left-3 z-10 bg-[#06141C] px-2 text-[.65rem] sm:text-[.7rem] font-bold uppercase tracking-[.14em] text-[#F2FEFE]">
            Email
        </label>

        <input type="text" name="email" id="email" class="h-11 sm:h-12 w-full border-2 border-[#07556A] bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none transition-colors duration-200 placeholder:text-[#4E6B72] focus:border-[#13F3F7] focus:ring-0" value="<?= isset($usuario["email"]) ? $usuario["email"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["email"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#13F3F7]"><?= $erros["email"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    
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
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["senha"])): ?>
                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm font-medium text-[#13F3F7]"><?= $erros["senha"] ?></p>
            <?php endif;?>
        <?php endif;?>
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
            <?php if(isset($erros)): ?>
                <?php if(isset($erros["confirmar_senha"])): ?>
                    <?= $erros["confirmar_senha"] ?>
                <?php endif;?>
            <?php endif;?>
        </p>
        
    </div>
    <?php if(isset($erros["login"])): ?>
        <p class="text-xs sm:text-sm font-medium text-[#13F3F7]"><?= $erros["login"] ?></p>
    <?php endif;?>    
</div>

<div class="pt-2 sm:pt-3">
    <button type="submit" class="inline-flex w-full items-center justify-center border-[3px] border-[#13F3F7] bg-[#13F3F7] px-5 sm:px-6 py-3 sm:py-3.5 text-xs sm:text-sm font-black uppercase tracking-[.1em] text-[#000505] transition-colors duration-200 hover:-translate-y-0.5 hover:bg-[#54FBFE] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#54FBFE] focus-visible:ring-offset-2 focus-visible:ring-offset-[#06141C]">Criar conta</button>
</div>