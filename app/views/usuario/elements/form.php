<?php
$fotoAtual = null;
if (isset($usuario)) {
    $fotoAtual = is_object($usuario) ? $usuario->getImagem() : ($usuario['imagem'] ?? null);
}
$estrela = '<span aria-hidden="true" class="text-[#F2A058]">*</span>';
$marcado = 'group-has-[input[name=remover_imagem]:checked]';
?>
<div class="space-y-9">

    <p class="text-xs text-[#91B5BD]"><span aria-hidden="true" class="text-[#F2A058]">*</span> Campos obrigatórios</p>

    <!-- Foto de perfil -->
    <div class="group flex flex-col items-center gap-4 sm:flex-row">
        <img
            id="avatarPerfil"
            src="<?= !empty($fotoAtual) ? URL_BASE . '/arquivo?arquivo=' . urlencode($fotoAtual) : IMG_URL_BASE . '/perfil.png' ?>"
            alt="Foto de perfil"
            class="aspect-square h-24 w-24 shrink-0 overflow-hidden border-2 border-[#13F3F7] object-cover shadow-[0_0_24px_rgba(19,243,247,.35)] transition-opacity motion-reduce:transition-none <?= $marcado ?>:opacity-40"
        >

        <div class="flex w-full flex-col gap-2 sm:w-auto">
            <button type="button" id="btnSelecionarImagem" onclick="adicionarImagens()" class="rd-file-btn sm:w-auto sm:px-6">Selecionar imagem</button>
            <input type="file" name="imagem" id="imagens" accept="image/*" style="display:none;" data-recorte-largura="512" data-recorte-altura="512" data-recorte-tipo="image/png" data-recorte-nome="perfil" data-recorte-titulo="Recortar foto de perfil" data-recorte-dica="Formato quadrado (1:1)">

            <?php if (!empty($fotoAtual)): ?>
                <label class="flex min-h-11 cursor-pointer items-center gap-3 text-sm font-bold text-[#F2A058]">
                    <input type="checkbox" name="remover_imagem" value="1" class="peer sr-only">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center border-2 border-[#07556A] bg-[#06141C] text-transparent peer-checked:border-[#13F3F7] peer-checked:bg-[#13F3F7] peer-checked:text-[#000505] peer-focus-visible:ring-2 peer-focus-visible:ring-[#54FBFE] peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#06141C]">
                        <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true"><path d="M4 10.5l4 4 8-9"/></svg>
                    </span>
                    <span class="select-none">Remover foto atual</span>
                </label>
                <p role="status" class="hidden text-xs text-[#91B5BD] <?= $marcado ?>:block">A foto será removida ao salvar.</p>
            <?php endif; ?>
        </div>
    </div>

    <ul id="listaImagens" class="rd-file-list [&>li]:bg-[#06141C] [&>li]:border-[#07556A] [&>li]:text-[#F2FEFE] [&>li]:p-4 [&>li:hover]:border-[#13F3F7] [&>li>div>img]:rounded-none [&>li>div>img]:border-2 [&>li>div>img]:border-[#13F3F7] [&>li>div>img]:shadow-[0_0_10px_rgba(19,243,247,.35)] [&_p:last-child]:text-[#91B5BD] [&>li>button]:flex [&>li>button]:h-11 [&>li>button]:w-11 [&>li>button]:items-center [&>li>button]:justify-center"></ul>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-3 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">01</span>
                Dados pessoais
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <div class="rd-field">
            <label for="nome_usuario" class="rd-label">Nome de usuário <?= $estrela ?></label>
            <input
                type="text"
                id="nome_usuario"
                name="nome_usuario"
                class="rd-input"
                required
                value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNomeUsuario() : (isset($usuario['nome_usuario']) ? $usuario['nome_usuario'] : '')) : '' ?>"
            >
        </div>

        <div class="rd-field">
            <label for="nome" class="rd-label">Nome <?= $estrela ?></label>
            <input
                type="text"
                id="nome"
                name="nome"
                class="rd-input"
                required
                value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getNome() : (isset($usuario['nome']) ? $usuario['nome'] : '')) : '' ?>"
            >
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
                aria-describedby="contador-biografia"
            ><?= isset($usuario)
                ? (is_object($usuario)
                    ? $usuario->getBiografia()
                    : (isset($usuario['biografia']) ? $usuario['biografia'] : ''))
                : '' ?></textarea>
            <p id="contador-biografia" aria-live="polite" class="mt-1 text-right text-xs text-[#91B5BD]">0/500</p>
        </div>

        <div class="rd-field">
            <label for="email" class="rd-label">E-mail <?= $estrela ?></label>
            <input
                type="email"
                id="email"
                name="email"
                class="rd-input"
                autocomplete="email"
                required
                value="<?= isset($usuario) ? (is_object($usuario) ? $usuario->getEmail() : (isset($usuario['email']) ? $usuario['email'] : '')) : '' ?>"
            >
        </div>

        <?php if($_SESSION["usuario_logado"]->getRegra() == "admin"): ?>
            <div class="rd-field">
                <label for="regra" class="rd-label">Regra</label>
                <select name="regra" id="regra" class="rd-select">
                    <option value="">Selecione</option>
                    <option value="admin" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "admin" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "admin" ? "selected" : "") : "")) : "" ?>>Admin</option>
                    <option value="usuario" <?= isset($usuario) ? (is_object($usuario) ? ($usuario->getRegra() == "usuario" ? "selected" : "") : (isset($usuario["regra"]) ? ($usuario["regra"] == "usuario" ? "selected" : "") : "")) : "" ?>>Usuário</option>
                </select>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-3 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">02</span>
                Segurança
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <div class="rd-field">
            <label for="senha" class="rd-label">Nova senha</label>
            <div class="flex w-full">
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    autocomplete="new-password"
                    class="password rd-input flex-1 border-r-0"
                    aria-describedby="dica-senha"
                    value="<?= isset($usuario) ? (is_object($usuario) ? '' : (isset($usuario['senha']) ? $usuario['senha'] : '')) : '' ?>"
                >
                <button type="button" onclick="passowrdChange()" class="rd-btn-icon">
                    <img class="passwordButton h-4 w-4" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
                </button>
            </div>
            <p id="dica-senha" class="mt-2 text-xs text-[#91B5BD]">Deixe em branco para manter a senha atual.</p>
        </div>

        <div class="rd-field">
            <label for="confirmar_senha" class="rd-label">Confirmar nova senha</label>
            <div class="flex w-full">
                <input type="password" id="confirmar_senha" name="confirmar_senha" autocomplete="new-password" class="confirmarPassword rd-input flex-1 border-r-0">
                <button type="button" onclick="confirmarPassowrdChange()" class="rd-btn-icon">
                    <img class="confirmarPasswordButton h-4 w-4" src="<?= IMG_URL_BASE ?>/visibility.png" alt="visualização">
                </button>
            </div>
            <p class="confirmarErro rd-error"></p>
        </div>
    </fieldset>

    <?php if(isset($_POST["id"]) || isset($usuario)): ?>
        <input type="hidden" name="id" value="<?= isset($_POST['id']) ? $_POST['id'] : (isset($usuario) ? (is_object($usuario) ? $usuario->getId() : (isset($usuario['id']) ? $usuario['id'] : '')) : '') ?>">
    <?php endif; ?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary w-full sm:w-auto">Salvar alterações</button>
</div>
