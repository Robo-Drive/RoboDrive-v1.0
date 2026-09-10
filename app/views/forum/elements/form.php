<div class="space-y-6">

    <div class="rd-field">
        <label for="conteudo" class="rd-label">Conteúdo</label>
        <textarea name="conteudo" id="conteudo" rows="6" class="rd-textarea"><?= isset($forum) ? (is_object($forum) ? $forum->getconteudo() : (isset($forum["conteudo"])? $forum["conteudo"] : "") ) : ""?></textarea>
        <?php if (isset($erros['conteudo'])): ?>
            <p class="rd-error"><?= $erros['conteudo'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label for="visibilidade" class="rd-label">Visibilidade</label>
        <select name="visibilidade" id="visibilidade" class="rd-select">
            <option value="">Selecione</option>
            <option value="equipe" <?= isset($forum) ? (is_object($forum) ? ($forum->getVisibilidade() == "equipe" ? "selected" : "") : (isset($forum["visibilidade"])? ($forum["visibilidade"] == "equipe" ? "selected" : "" ) : "")) : "" ?>>Equipe</option>
            <option value="publico" <?= isset($forum) ? (is_object($forum) ? ($forum->getVisibilidade() == "publico" ? "selected" : "") : (isset($forum["visibilidade"])? ($forum["visibilidade"] == "publico" ? "selected" : "" ) : "")) : "" ?>>Público</option>
        </select>
        <?php if (isset($erros['visibilidade'])): ?>
            <p class="rd-error"><?= $erros['visibilidade'] ?></p>
        <?php endif; ?>
    </div>

    <?php if(isset($_POST["id"]) || isset($forum)):?>
        <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($forum) ? (is_object($forum) ? $forum->getId() : (isset($forum["id"])? $forum["id"] : "")) : "")?>">
    <?php endif;?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
