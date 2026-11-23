
<div class="space-y-6">

    <!-- Nome -->
    <div class="rd-field">
        <label for="nome" class="rd-label">Nome</label>

        <input
            type="text"
            id="nome"
            name="nome"
            placeholder="Digite o nome da equipe..."
            class="rd-input"
            value="<?= texto($_POST["nome"] ?? (isset($equipe) ? (is_object($equipe) ? $equipe->getNome() : ($equipe["nome"] ?? "")) : "")) ?>"
        >

    </div>

    <!-- Descrição -->
    <div class="rd-field">
        <label for="descricao" class="rd-label">Descrição</label>

        <textarea
            id="descricao"
            name="descricao"
            rows="5"
            placeholder="Conte um pouco sobre a equipe"
            class="rd-textarea"
        ><?= texto($_POST["descricao"] ?? (isset($equipe) ? (is_object($equipe) ? $equipe->getDescricao() : ($equipe["descricao"] ?? "")) : "")) ?></textarea>

    </div>

    <!-- Senha -->
    <div class="rd-field">
        <label for="senha" class="rd-label">Senha da Equipe</label>

        <div class="password-container flex w-full border-2 border-[#07556A] transition-colors duration-200 focus-within:border-[#13F3F7]">

            <input
                type="password"
                name="senha"
                id="senha"
                class="password h-11 sm:h-12 min-w-0 flex-1 bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none"
            >

            <button
                type="button"
                onclick="passowrdChange()"
                class="rd-btn-icon password-divider sm:h-12"
                aria-label="Alternar visibilidade da senha"
            >
                <img
                    class="passwordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200"
                    src="<?= IMG_URL_BASE ?>/visibility.png"
                    alt="visualização"
                >
            </button>

        </div>

    </div>

    <!-- Confirmar Senha -->
    <div class="rd-field">
        <label for="confirmar_senha" class="rd-label">Confirmar Senha da Equipe</label>

        <div class="password-container flex w-full border-2 border-[#07556A] transition-colors duration-200 focus-within:border-[#13F3F7]">

            <input
                type="password"
                name="confirmar_senha"
                id="confirmar_senha"
                class="confirmarPassword h-11 sm:h-12 min-w-0 flex-1 bg-[#000505] px-3.5 sm:px-4 text-sm sm:text-base text-[#F2FEFE] outline-none"
            >

            <button
                type="button"
                onclick="confirmarPassowrdChange()"
                class="rd-btn-icon password-divider sm:h-12"
                aria-label="Alternar visibilidade da senha"
            >
                <img
                    class="confirmarPasswordButton h-4 w-4 sm:h-5 sm:w-5 transition-all duration-200"
                    src="<?= IMG_URL_BASE ?>/visibility.png"
                    alt="visualização"
                >
            </button>

        </div>

    </div>

    <!-- ID oculto -->
    <?php if (isset($equipe) || isset($_POST["id"])): ?>

        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars($_POST["id"] ?? (isset($equipe) ? (is_object($equipe) ? $equipe->getId() : ($equipe["id"] ?? "")) : "")) ?>"
        >

    <?php endif; ?>

</div>

<!-- Botão -->
<div class="flex items-center justify-center p-4 pt-8">

    <button type="submit" class="rd-btn rd-btn-primary">
        Enviar
    </button>

</div>
