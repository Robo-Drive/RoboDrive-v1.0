<div class="space-y-6">
    <!-- Conteúdo -->
    <div class="rd-field">
        <label for="conteudo" class="rd-label">Conteúdo</label>

        <textarea
            id="conteudo"
            name="conteudo"
            rows="5"
            placeholder="Escreva o conteúdo da publicação..."
            class="rd-textarea"
        ><?= isset($forum) ? (is_object($forum) ? $forum->getConteudo() : ($forum["conteudo"] ?? "")) : "" ?></textarea>

        <?php if (isset($erros['conteudo'])): ?>
            <p class="rd-error"><?= $erros['conteudo'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Visibilidade -->
    <div class="rd-field">
        <input name="visibilidade" type="hidden" value="<?= isset($_GET["equipe_id"]) ? $_GET["equipe_id"] : "publico" ?>">
    </div>

    <!-- ID oculto -->
    <?php if (isset($_POST["id"]) || isset($forum)): ?>
        <input
            type="hidden"
            name="id"
            value="<?= isset($forum) ? (isset($_POST["id"])
                ? $_POST["id"]
                : (is_object($forum)
                    ? $forum->getId()
                    : ($forum["id"] ?? ""))) : "" ?>"
        >
    <?php endif; ?>

</div>

<!-- Botão -->
<div class="flex items-center justify-center p-4 pt-8">
    <button type="submit" class="rd-btn rd-btn-primary">
        Enviar
    </button>
</div>