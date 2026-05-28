
<div>
    <label for="conteudo" class="text-white">Conteudo:</label>
    <br>
    <textarea name="conteudo" rows="4" class="w-full">
        <?= isset($forum) ? (is_object($forum) ? $forum->getconteudo() : (isset($forum["conteudo"])? $forum["conteudo"] : "") ) : ""?>
    </textarea>
    <?php if (isset($erros['conteudo'])): ?>
        <div class="text-red-500 small"><?= $erros['conteudo'] ?></div>
    <?php endif; ?>
</div>



<div>
    <label for="visibilidade" class="text-white">Visibilidade:</label>
    <br>
    <select name="visibilidade">
        <option value="">Selecione</option>
        <option value="equipe" <?= isset($forum) ? (is_object($forum) ? ($forum->getVisibilidade() == "equipe" ? "selected" : "") : (isset($forum["visibilidade"])? ($forum["visibilidade"] == "equipe" ? "selected" : "" ) : "")) : "" ?>>Equipe</option>
        <option value="publico" <?= isset($forum) ? (is_object($forum) ? ($forum->getVisibilidade() == "publico" ? "selected" : "") : (isset($forum["visibilidade"])? ($forum["visibilidade"] == "publico" ? "selected" : "" ) : "")) : "" ?>>Publico</option>
    </select>
    <?php if (isset($erros['visibilidade'])): ?>
        <div class="text-red-500 small"><?= $erros['visibilidade'] ?></div>
    <?php endif; ?>
</div>
<?php if(isset($_POST["id"]) || isset($forum)):?>
    <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($forum) ? (is_object($forum) ? $forum->getId() : (isset($forum["id"])? $forum["id"] : "")) : "")?>">
<?php endif;?>
<div class="flex justify-center items-center p-2">
    <button type="submit" class="text-white px-3 py-1 rounded-xl bg-green-700">Enviar</button>
</div>