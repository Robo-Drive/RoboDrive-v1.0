<div class="space-y-6">

    <div class="rd-field">
        <label for="nome" class="rd-label">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            class="rd-input"
            value="<?= isset($componente) ? (is_object($componente) ? $componente->getNome() : (isset($componente["nome"]) ? $componente["nome"] : "")) : "" ?>"
        >
        <?php if (isset($erros["nome"])): ?>
            <p class="rd-error"><?= $erros["nome"] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label for="descricao" class="rd-label">Descrição</label>
        <textarea name="descricao" id="descricao" rows="4" class="rd-textarea"><?= isset($componente) ? (is_object($componente) ? $componente->getDescricao() : (isset($componente["descricao"]) ? $componente["descricao"] : "")) : "" ?></textarea>
        <?php if (isset($erros["descricao"])): ?>
            <p class="rd-error"><?= $erros["descricao"] ?></p>
        <?php endif; ?>
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

    <?php if (isset($_POST["id"]) || isset($componente)): ?>
        <input type="hidden" name="id" value="<?= isset($_POST["id"]) ? $_POST["id"] : (isset($componente) ? (is_object($componente) ? $componente->getId() : (isset($componente["id"]) ? $componente["id"] : "")) : "") ?>">
    <?php endif; ?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
