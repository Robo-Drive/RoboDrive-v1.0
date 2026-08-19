<div class="space-y-6">
    <div class="relative">
        <label for="nome_usuario" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome de usuário
        </label>

        <input type="text" name="nome_usuario" class="w-full h-10 bg-black/80 border border-white  px-4 text-white outline-none focus:border-[#00F5F5] transition-all" value="<?= isset($usuario["nome_usuario"]) ? $usuario["nome_usuario"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["nome_usuario"])): ?>
                <p class="text-[#00F5F5] mt-3"><?= $erros["nome_usuario"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome
        </label>

        <input type="text" name="nome" class="w-full h-10 bg-black/80 border border-white  px-4 text-white outline-none focus:border-[#00F5F5] transition-all" value="<?= isset($usuario["nome"]) ? $usuario["nome"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["nome"])): ?>
                <p class="text-[#00F5F5] mt-3"><?= $erros["nome"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="email" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Email
        </label>

    <input type="text" name="email" class="w-full h-10 bg-black/80 border border-white  px-4 text-white outline-none focus:border-[#00F5F5] transition-all" value="<?= isset($usuario["email"]) ? $usuario["email"]: "" ?>">
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["email"])): ?>
                <p class="text-[#00F5F5] mt-3"><?= $erros["email"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    
    <div class="relative">
        <label for="senha" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Senha
        </label>

        <div class="flex w-full">
            <input type="password" name="senha" class="password flex-1 h-12 bg-black/80 border border-r-0 border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all">

            <button type="button" onclick="passowrdChange()"class="h-12 px-4 bg-black/80 border border-white border-l-0 text-white hover:text-[#00F5F5] transition-all flex items-center justify-center">
                <img  class="passwordButton" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <?php if(isset($erros)): ?>
            <?php if(isset($erros["senha"])): ?>
                <p class="text-[#00F5F5] mt-3"><?= $erros["senha"] ?></p>
            <?php endif;?>
        <?php endif;?>
    </div>
    <div class="relative">
        <label for="confirmar_senha" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Confirmar senha
        </label>

        <div class="flex w-full">
            <input type="password" name="confirmar_senha" class="confirmarPassword flex-1 h-12 bg-black/80 border border-r-0 border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all">

            <button type="button" onclick="confirmarPassowrdChange()"class="h-12 px-4 bg-black/80 border border-white border-l-0 text-white hover:text-[#00F5F5] transition-all flex items-center justify-center">
                <img  class="confirmarPasswordButton" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <p class="confirmarErro text-[#00F5F5] mt-3">
            <?php if(isset($erros)): ?>
                <?php if(isset($erros["confirmar_senha"])): ?>
                    <?= $erros["confirmar_senha"] ?>
                <?php endif;?>
            <?php endif;?>
        </p>
        
    </div>
    <?php if(isset($erros["login"])): ?>
        <p class="text-[#00F5F5] mt-3"><?= $erros["login"] ?></p>
    <?php endif;?>    
</div>

<div class="flex justify-center items-center p-4">
    <button type="submit" class="text-white px-3 py-1 border ">Enviar</button>
</div>
