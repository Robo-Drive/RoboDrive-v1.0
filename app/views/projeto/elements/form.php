<div>
    <label for="nome" class="text-white">Nome:</label>
    <br>
    <input type="text" name="nome" value="<?= isset($projeto) ? (is_object($projeto) ? $projeto->getNome() : (isset($projeto["nome"])? $projeto["nome"] : "") ) : ""?>">
    <?php if (isset($erros['nome'])): ?>
        <div class="text-red-500 small"><?= $erros['nome'] ?></div>
    <?php endif; ?>
</div>

<div>
    <label for="descricao" class="text-white">Descricao:</label>
    <br>
    <textarea name="descricao" rows="4" class="w-full">
        <?= isset($projeto) ? (is_object($projeto) ? $projeto->getDescricao() : (isset($projeto["descricao"])? $projeto["descricao"] : "") ) : ""?>
    </textarea>
    <?php if (isset($erros['descricao'])): ?>
        <div class="text-red-500 small"><?= $erros['descricao'] ?></div>
    <?php endif; ?>
</div>



<div>
    <label for="visibilidade" class="text-white">Visibilidade:</label>
    <br>
    <select name="visibilidade">
        <option value="">Selecione</option>
        <option value="privado" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "privado" ? "selected" : "") : (isset($projeto["visibilidade"])? ($projeto["visibilidade"] == "privado" ? "selected" : "" ) : "")) : "" ?>>Privado</option>
        <option value="equipe" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "equipe" ? "selected" : "") : (isset($projeto["visibilidade"])? ($projeto["visibilidade"] == "equipe" ? "selected" : "" ) : "")) : "" ?>>Equipe</option>
        <option value="grupo" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "publico" ? "selected" : "") : (isset($projeto["visibilidade"])? ($projeto["visibilidade"] == "publico" ? "selected" : "" ) : "")) : "" ?>>Publico</option>
    </select>
    <?php if (isset($erros['visibilidade'])): ?>
        <div class="text-red-500 small"><?= $erros['visibilidade'] ?></div>
    <?php endif; ?>
</div>
<?php if(isset($_POST["id"]) || isset($projeto)):?>
    <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($projeto) ? (is_object($projeto) ? $projeto->getId() : (isset($projeto["id"])? $projeto["id"] : "")) : "")?>">
<?php endif;?>
<div class="flex justify-center items-center p-2">
    <button type="submit" class="text-white px-3 py-1 rounded-xl bg-green-700">Enviar</button>
</div>