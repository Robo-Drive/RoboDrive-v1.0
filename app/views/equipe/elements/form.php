<div class="space-y-6">

    <div class="rd-field">
        <label for="nome" class="rd-label">Nome da equipe</label>
        <input
            type="text"
            id="nome"
            name="nome"
            class="rd-input"
            value="<?= isset($equipe) ? (is_object($equipe) ? $equipe->getNome() : (isset($equipe["nome"])? $equipe["nome"] : "") ) : ""?>"
        >
        <?php if (isset($erros['nome'])): ?>
            <p class="rd-error"><?= $erros['nome'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label for="senha" class="rd-label">Senha da equipe</label>
        <input
            type="password"
            id="senha"
            name="senha"
            class="rd-input"
            value="<?= isset($usuario) ? (is_object($usuario) ? "" : (isset($usuario["senha"])? $usuario["senha"] : "")) : "" ?>"
        >
        <?php if (isset($erros['senha'])): ?>
            <p class="rd-error"><?= $erros['senha'] ?></p>
        <?php endif; ?>
    </div>

    <?php if(isset($_POST["id"]) || isset($equipe)):?>
        <input type="hidden" name="id" value="<?= isset($_POST["id"])? $_POST["id"]:(isset($equipe) ? (is_object($equipe) ? $equipe->getId() : (isset($equipe["id"])? $equipe["id"] : "")) : "")?>">
    <?php endif;?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
