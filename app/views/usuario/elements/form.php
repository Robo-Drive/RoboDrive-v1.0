<div class="space-y-6">

    <div class="rd-field">
        <label for="nome_usuario" class="rd-label">Nome de usuário</label>
        <input
            type="text"
            id="nome_usuario"
            name="nome_usuario"
            class="rd-input"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNomeUsuario() : (isset($usuario['nome_usuario']) ? $usuario['nome_usuario'] : '')) : '' ?>"
        >
    </div>

    <div class="rd-field">
        <label for="nome" class="rd-label">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            class="rd-input"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNome() : (isset($usuario['nome']) ? $usuario['nome'] : '')) : '' ?>"
        >
    </div>

    <div class="rd-field">
        <label for="biografia" class="rd-label">Biografia</label>
        <textarea
            name="biografia"
            id="biografia"
            rows="5"
            maxlength="500"
            placeholder="Conte um pouco sobre você..."
            class="rd-textarea"
        ><?= isset($usuario)
            ? (is_object($usuario)
                ? $usuario->getBiografia()
                : (isset($usuario['biografia']) ? $usuario['biografia'] : ''))
            : '' ?></textarea>
    </div>

    <div class="rd-field">
        <label for="email" class="rd-label">Email</label>
        <input
            type="text"
            id="email"
            name="email"
            class="rd-input"
            value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getEmail() : (isset($usuario['email']) ? $usuario['email'] : '')) : '' ?>"
        >
    </div>

    <div class="rd-field">
        <label for="senha" class="rd-label">Senha</label>
        <div class="flex w-full">
            <input
                type="password"
                id="senha"
                name="senha"
                class="password rd-input flex-1 border-r-0"
                value="<?= isset($usuario) ? (is_object($usuario) ? '' : (isset($usuario['senha']) ? $usuario['senha'] : '')) : '' ?>"
            >
            <button type="button" onclick="passowrdChange()" class="flex h-11 w-11 shrink-0 items-center justify-center border-2 border-[#07556A] bg-[#000505] transition-colors duration-200 hover:border-[#13F3F7]">
                <img class="passwordButton h-4 w-4" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
    </div>

    <div class="rd-field">
        <label for="confirmar_senha" class="rd-label">Confirmar senha</label>
        <div class="flex w-full">
            <input type="password" id="confirmar_senha" name="confirmar_senha" class="confirmarPassword rd-input flex-1 border-r-0">
            <button type="button" onclick="confirmarPassowrdChange()" class="flex h-11 w-11 shrink-0 items-center justify-center border-2 border-[#07556A] bg-[#000505] transition-colors duration-200 hover:border-[#13F3F7]">
                <img class="confirmarPasswordButton h-4 w-4" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
            </button>
        </div>
        <p class="confirmarErro rd-error">
        </p>
    </div>

        <!-- Foto Atual com opção de retirar (estilo projetos) -->
    <?php 
        if(isset($usuario))
        {
            $fotoAtual = is_object($usuario) ? $usuario->getImagem() : ($usuario['imagem'] ?? null);
        }
    ?>
    <?php if (!empty($fotoAtual)): ?>
        <div class="rd-field mb-4">
            <label class="rd-label">Foto de Perfil Atual</label>
            <div class="flex items-center gap-4 border-2 border-[#07556A] bg-[#06141C] p-3">
                <img 
                    src="<?= URL_BASE ?>/arquivo?arquivo=<?= urlencode($fotoAtual) ?>" 
                    alt="Foto de Perfil" 
                    class="h-16 w-16 object-cover border-2 border-[#13F3F7]"
                >
                <label class="flex items-center gap-2 cursor-pointer bg-[#000505] p-2 border border-[#07556A] text-xs">
                    <input 
                        type="checkbox" 
                        name="remover_imagem" 
                        value="1" 
                        class="accent-[#13F3F7] h-4 w-4"
                    >
                    <span class="text-xs text-rose-400 font-bold uppercase select-none">
                        Remover foto de perfil
                    </span>
                </label>
            </div>
        </div>
    <?php endif; ?>

    <!-- Nova Imagem -->
    <div class="rd-field">
        <label for="imagem" class="rd-label"><?= !empty($fotoAtual) ? 'Alterar foto de perfil' : 'Foto de perfil' ?></label>
        <button type="button" onclick="adicionarImagens()" class="rd-file-btn">Selecionar imagem</button>
        <input type="file" name="imagem" id="imagens" accept="image/*" style="display:none;">
        <ul id="listaImagens" class="rd-file-list"></ul>
    </div>

    <?php if($_SESSION["usuario_logado"]->getRegra() == "admin"): ?>
        <div class="rd-field">
            <label for="regra" class="rd-label">Regra</label>
            <select name="regra" id="regra" class="rd-select">
                <option value="">Selecione</option>
                <option value="admin" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "admin" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "admin" ? "selected" : "") : "")) : "" ?>>Admin</option>
                <option value="usuario" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "usuario" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "usuario" ? "selected" : "") : "")) : "" ?>>Usuário</option>
            </select>
        </div>
    <?php endif; ?>

    <?php if(isset($_POST["id"]) || isset($usuario)): ?>
        <input type="hidden" name="id" value="<?= isset($_POST['id']) ? $_POST['id'] : (isset($usuario) ? (is_object($usuario) ? $usuario->getId() : (isset($usuario['id']) ? $usuario['id'] : '')) : '') ?>">
    <?php endif; ?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
