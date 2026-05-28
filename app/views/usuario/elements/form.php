<div>
    <label for="nome" class="text-white">Nome:</label>
    <br>
    <input type="text" name="nome" value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNome() : (isset($usuario["nome"])? $usuario["nome"] : "") ) : ""?>">
    <?php if (isset($erros['nome'])): ?>
        <div class="text-red-500 small"><?= $erros['nome'] ?></div>
    <?php endif; ?>
</div>

<div>
    <label for="email" class="text-white">Email:</label>
    <br>
    <input type="text" name="email" value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getEmail() : (isset($usuario["email"])? $usuario["email"] : "")) : "" ?>">
    <?php if (isset($erros['email'])): ?>
        <div class="text-red-500 small"><?= $erros['email'] ?></div>
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

<div>
    <label for="imagem" class="text-white">Imagem de perfil:</label>
    <br>
    <input type="text" name="imagem" value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getImagem() : (isset($usuario["imagem"])? $usuario["imagem"] : "")) : "" ?>">
    <?php if (isset($erros['imagem'])): ?>
        <div class="text-red-500 small"><?= $erros['imagem'] ?></div>
    <?php endif; ?>
</div>
<?php if($_SESSION["usuario_logado"]->getRegra() == "admin"):?>    
    <div>
        <label for="Regra" class="text-white">Regra:</label>
        <br>
        <select name="regra">
            <option value="">Selecione</option>
            <option value="admin" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "admin" ? "selected" : "") : (isset($usuario["regra"])? ($usuario["regra"] == "admin" ? "selected" : "" ) : "")) : "" ?>>Admin</option>
            <option value="usuario" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "usuario" ? "selected" : "") : (isset($usuario["regra"])? ($usuario["regra"] == "usuario" ? "selected" : "" ) : "")) : "" ?>>Usuario</option>
        </select>
        <?php if (isset($erros['regra'])): ?>
            <div class="text-red-500 small"><?= $erros['regra'] ?></div>
        <?php endif; ?>
    </div>
<?php endif;?>
<?php if(isset($_POST["id"]) || isset($usuario)):?>
    <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($usuario) ? (is_object($usuario) ? $usuario->getId() : (isset($usuario["id"])? $usuario["id"] : "")) : "")?>">
<?php endif;?>
<div class="flex justify-center items-center p-2">
    <button type="submit" class="text-white px-3 py-1 rounded-xl bg-green-700">Enviar</button>
</div>