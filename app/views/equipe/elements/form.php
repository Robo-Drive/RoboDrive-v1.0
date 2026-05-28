<div>
    <label for="nome" class="text-white">Nome:</label>
    <br>
    <input type="text" name="nome" value="<?= isset($equipe) ? (is_object($equipe) ? $equipe->getNome() : (isset($equipe["nome"])? $equipe["nome"] : "") ) : ""?>">
    <?php if (isset($erros['nome'])): ?>
        <div class="text-red-500 small"><?= $erros['nome'] ?></div>
    <?php endif; ?>
</div>

<div>
    <label for="senha" class="text-white">Senha:</label>
    <br>
    <input type="text" name="senha" value="<?= isset($usuario) ? (is_object($usuario) ? "" : (isset($usuario["senha"])? $usuario["senha"] : "")) : "" ?>">
    <?php if (isset($erros['senha'])): ?>
        <div class="text-red-500 small"><?= $erros['senha'] ?></div>
    <?php endif; ?>
</div>
<?php if(isset($_POST["id"]) || isset($equipe)):?>
    <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($equipe) ? (is_object($equipe) ? $equipe->getId() : (isset($equipe["id"])? $equipe["id"] : "")) : "")?>">
<?php endif;?>
<div class="flex justify-center items-center p-2">
    <button type="submit" class="text-white px-3 py-1 rounded-xl bg-green-700">Enviar</button>
</div>