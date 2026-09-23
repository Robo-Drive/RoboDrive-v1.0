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
        <?php if (isset($erros['nome_usuario'])): ?>
            <p class="rd-error"><?= $erros['nome_usuario'] ?></p>
        <?php endif; ?>
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
        <?php if (isset($erros['nome'])): ?>
            <p class="rd-error"><?= $erros['nome'] ?></p>
        <?php endif; ?>
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
        <?php if (isset($erros['biografia'])): ?>
            <p class="rd-error"><?= $erros['biografia'] ?></p>
        <?php endif; ?>
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
        <?php if (isset($erros['email'])): ?>
            <p class="rd-error"><?= $erros['email'] ?></p>
        <?php endif; ?>
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
        <?php if (isset($erros['senha'])): ?>
            <p class="rd-error"><?= $erros['senha'] ?></p>
        <?php endif; ?>
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
            <?php if(isset($erros)): ?>
                <?php if(isset($erros["confirmar_senha"])): ?>
                    <?= $erros["confirmar_senha"] ?>
                <?php endif;?>
            <?php endif;?>
        </p>
    </div>

    <div class="rd-field">
        <label for="imagem" class="rd-label">Imagem</label>
        <button type="button" onclick="adicionarImagens()" class="rd-file-btn">Adicionar imagens</button>
        <input type="file" name="imagem" id="imagens" accept="image/*" style="display:none;">
        <ul id="listaImagens" class="rd-file-list"></ul>
        <?php if (isset($erros['imagem'])): ?>
            <p class="rd-error"><?= $erros['imagem'] ?></p>
        <?php endif; ?>
    </div>

    <?php if($_SESSION["usuario_logado"]->getRegra() == "admin"): ?>
        <div class="rd-field">
            <label for="regra" class="rd-label">Regra</label>
            <select name="regra" id="regra" class="rd-select">
                <option value="">Selecione</option>
                <option value="admin" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "admin" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "admin" ? "selected" : "") : "")) : "" ?>>Admin</option>
                <option value="usuario" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "usuario" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "usuario" ? "selected" : "") : "")) : "" ?>>Usuário</option>
            </select>
            <?php if (isset($erros['regra'])): ?>
                <p class="rd-error"><?= $erros['regra'] ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_POST["id"]) || isset($usuario)): ?>
        <input type="hidden" name="id" value="<?= isset($_POST['id']) ? $_POST['id'] : (isset($usuario) ? (is_object($usuario) ? $usuario->getId() : (isset($usuario['id']) ? $usuario['id'] : '')) : '') ?>">
    <?php endif; ?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
