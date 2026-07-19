<div class="space-y-6">

    <!-- Nome de usuário-->
    <div class="relative">
        <label for="nome_usuario" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome de usuário
        </label>

        <input
            type="text"
            name="nome_usuario"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNomeUsuario() : (isset($usuario['nome_usuario']) ? $usuario['nome_usuario'] : '')) : '' ?>"
        >

        <?php if (isset($erros['nome'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['nome'] ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Nome -->
    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNome() : (isset($usuario['nome']) ? $usuario['nome'] : '')) : '' ?>"
        >

        <?php if (isset($erros['nome'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['nome'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Email -->
    <div class="relative">
        <label for="email" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Email
        </label>

        <input
            type="text"
            name="email"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getEmail() : (isset($usuario['email']) ? $usuario['email'] : '')) : '' ?>"
        >

        <?php if (isset($erros['email'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['email'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Senha -->
    <div class="relative">
        <label for="senha" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Senha
        </label>

        <div class="flex w-full">
            <input
                type="password"
                name="senha"
                class="password flex-1 h-12 bg-black/80 border border-r-0 border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
                value="<?= isset($usuario) ? (is_object($usuario) ? '' : (isset($usuario['senha']) ? $usuario['senha'] : '')) : '' ?>"
            >

            <button
                type="button"
                onclick="passowrdChange()"
                class="h-12 px-4 bg-black/80 border border-white border-l-0 text-white hover:text-[#FF1A1A] transition-all flex items-center justify-center"
            >
                <img
                    class="passwordButton"
                    src="<?= IMG_URL_BASE ?>/visibility.png"
                    alt="visualização"
                >
            </button>
        </div>

        <?php if (isset($erros['senha'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['senha'] ?></p>
        <?php endif; ?>
    </div>
    <div class="relative">
        <label for="confirmarSenha" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Confirmar senha
        </label>

        <div class="flex w-full">
            <input type="password" name="confirmarSenha" class="confirmarPassword flex-1 h-12 bg-black/80 border border-r-0 border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all">

            <button type="button" onclick="confirmarPassowrdChange()"class="h-12 px-4 bg-black/80 border border-white border-l-0 text-white hover:text-[#FF1A1A] transition-all flex items-center justify-center">
                <img  class="confirmarPasswordButton" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <p class="confirmarErro text-[#FF1A1A] mt-3">
            <?php if(isset($erros)): ?>
                <?php if(isset($erros["confirmarSenha"])): ?>
                    <?= $erros["confirmarSenha"] ?>
                <?php endif;?>
            <?php endif;?>
        </p>
        
    </div>

    <!-- Imagem -->
    <div class="relative">
        <label for="imagem" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Imagem de perfil
        </label>

        <input
            type="text"
            name="imagem"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getImagem() : (isset($usuario['imagem']) ? $usuario['imagem'] : '')) : '' ?>"
        >

        <?php if (isset($erros['imagem'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['imagem'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Regra -->
    <?php if($_SESSION["usuario_logado"]->getRegra() == "admin"): ?>
        <div class="relative">
            <label for="regra" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
                Regra
            </label>

            <select
                name="regra"
                class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            >
                <option value="">Selecione</option>

                <option
                    value="admin"
                    <?= isset($usuario)
                        ? (is_object($usuario)
                            ? ($usuario->getRegra() == "admin" ? "selected" : "")
                            : (isset($usuario["regra"])
                                ? ($usuario["regra"] == "admin" ? "selected" : "")
                                : ""))
                        : "" ?>
                >
                    Admin
                </option>

                <option
                    value="usuario"
                    <?= isset($usuario)
                        ? (is_object($usuario)
                            ? ($usuario->getRegra() == "usuario" ? "selected" : "")
                            : (isset($usuario["regra"])
                                ? ($usuario["regra"] == "usuario" ? "selected" : "")
                                : ""))
                        : "" ?>
                >
                    Usuário
                </option>
            </select>

            <?php if (isset($erros['regra'])): ?>
                <p class="text-[#FF1A1A] mt-3"><?= $erros['regra'] ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ID hidden -->
    <?php if(isset($_POST["id"]) || isset($usuario)): ?>
        <input
            type="hidden"
            name="id"
            value="<?= isset($_POST['id'])
                ? $_POST['id']
                : (isset($usuario)
                    ? (is_object($usuario)
                        ? $usuario->getId()
                        : (isset($usuario['id']) ? $usuario['id'] : ''))
                    : '') ?>"
        >
    <?php endif; ?>

</div>

<!-- Botão -->
<div class="flex justify-center items-center p-4">
    <button
        type="submit"
        class="text-white px-5 py-2 border border-white hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition-all"
    >
        Enviar
    </button>
</div>