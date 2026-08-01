<div class="space-y-7">
    <div class="relative">
        <label for="nome" class="mb-2 block text-[.7rem] font-bold uppercase tracking-[.14em] text-zinc-300">
            Nome
        </label>

    <input type="text" name="nome" class="h-12 w-full border-2 border-white bg-black px-4 text-white outline-none transition-colors duration-200 placeholder:text-zinc-600 focus:border-[#FF2D2D] focus:ring-0" value="<?= isset($usuario["nome"]) ? $usuario["nome"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["nome"])): ?>
                <p class="mt-2 text-sm font-medium text-[#FF2D2D]"><?= $erros["nome"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="email" class="mb-2 block text-[.7rem] font-bold uppercase tracking-[.14em] text-zinc-300">
            Email
        </label>

    <input type="text" name="email" class="h-12 w-full border-2 border-white bg-black px-4 text-white outline-none transition-colors duration-200 placeholder:text-zinc-600 focus:border-[#FF2D2D] focus:ring-0" value="<?= isset($usuario["email"]) ? $usuario["email"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["email"])): ?>
                <p class="mt-2 text-sm font-medium text-[#FF2D2D]"><?= $erros["email"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    
    <div class="relative">
        <label for="senha" class="mb-2 block text-[.7rem] font-bold uppercase tracking-[.14em] text-zinc-300">
            Senha
        </label>

        <div class="flex w-full border-2 border-white transition-colors duration-200 focus-within:border-[#FF2D2D]">
            <input type="password" name="senha" class="password h-12 min-w-0 flex-1 bg-black px-4 text-white outline-none">

            <button type="button" onclick="passowrdChange()" class="flex h-12 items-center justify-center border-l-2 border-white bg-black px-4 text-white transition-colors duration-200 hover:bg-[#FF2D2D] focus:outline-none focus-visible:bg-[#FF2D2D]">
                <img class="passwordButton h-5 w-5 [filter:invert(1)]" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["senha"])): ?>
                <p class="mt-2 text-sm font-medium text-[#FF2D2D]"><?= $erros["senha"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="confirmarSenha" class="mb-2 block text-[.7rem] font-bold uppercase tracking-[.14em] text-zinc-300">
            Confirmar senha
        </label>

        <div class="flex w-full border-2 border-white transition-colors duration-200 focus-within:border-[#FF2D2D]">
            <input type="password" name="confirmarSenha" class="confirmarPassword h-12 min-w-0 flex-1 bg-black px-4 text-white outline-none">

            <button type="button" onclick="confirmarPassowrdChange()" class="flex h-12 items-center justify-center border-l-2 border-white bg-black px-4 text-white transition-colors duration-200 hover:bg-[#FF2D2D] focus:outline-none focus-visible:bg-[#FF2D2D]">
                <img class="confirmarPasswordButton h-5 w-5 [filter:invert(1)]" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <p class="confirmarErro mt-2 text-sm font-medium text-[#FF2D2D]">
            <?php if(isset($erros)): ?>
                <?php if(isset($erros["confirmarSenha"])): ?>
                    <?= $erros["confirmarSenha"] ?>
                <?php endif;?>
            <?php endif;?>
        </p>
        
    </div>
    <?php if(isset($erros["login"])): ?>
        <p class="text-sm font-medium text-[#FF2D2D]"><?= $erros["login"] ?></p>
    <?php endif;?>    
</div>

<div class="pt-1">
    <button type="submit" class="inline-flex w-full items-center justify-center border-[3px] border-white bg-[#FF2D2D] px-6 py-3.5 text-sm font-black uppercase tracking-[.1em] text-white transition-[filter,transform] duration-200 hover:-translate-y-0.5 hover:brightness-[1.15] focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-black">Criar conta</button>
</div>
