<div class="space-y-6">

    <div class="rd-field">
        <label for="nome" class="rd-label">Nome</label>
        <input
            type="text"
            id="nome"
            name="nome"
            class="rd-input"
            value="<?= isset($projeto) ? (is_object($projeto) ? $projeto->getNome() : (isset($projeto['nome']) ? $projeto['nome'] : '')) : '' ?>"
        >
        <?php if (isset($erros['nome'])): ?>
            <p class="rd-error"><?= $erros['nome'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label for="descricao" class="rd-label">Descrição</label>
        <textarea name="descricao" id="descricao" rows="5" class="rd-textarea"><?= isset($projeto) ? (is_object($projeto) ? $projeto->getDescricao() : (isset($projeto['descricao']) ? $projeto['descricao'] : '')) : '' ?></textarea>
        <?php if (isset($erros['descricao'])): ?>
            <p class="rd-error"><?= $erros['descricao'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label for="visibilidade" class="rd-label">Visibilidade</label>
        <select name="visibilidade" id="visibilidade" class="rd-select">
            <option value="">Selecione</option>
            <option value="privado" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "privado" ? "selected" : "") : (isset($projeto["visibilidade"]) ? ($projeto["visibilidade"] == "privado" ? "selected" : "") : "")) : "" ?>>Privado</option>
            <option value="equipe" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "equipe" ? "selected" : "") : (isset($projeto["visibilidade"]) ? ($projeto["visibilidade"] == "equipe" ? "selected" : "") : "")) : "" ?>>Equipe</option>
            <option value="publico" <?= isset($projeto) ? (is_object($projeto) ? ($projeto->getVisibilidade() == "publico" ? "selected" : "") : (isset($projeto["visibilidade"]) ? ($projeto["visibilidade"] == "publico" ? "selected" : "") : "")) : "" ?>>Público</option>
        </select>
        <?php if (isset($erros['visibilidade'])): ?>
            <p class="rd-error"><?= $erros['visibilidade'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label class="rd-label">Componentes</label>

        <div id="multiSelectComponente" class="relative">
            <input type="text" id="pesquisaComponente" placeholder="Pesquisar componente..." autocomplete="off" class="rd-input">

            <div id="listaComponentes" class="hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto border-2 border-[#07556A] bg-[#000505]">
                <?php if (isset($componentes)): ?>
                    <?php foreach ($componentes as $componente): ?>
                        <?php
                            $id = is_object($componente) ? $componente->getId() : $componente["id"];
                            $nome = is_object($componente) ? $componente->getNome() : $componente["nome"];
                        ?>
                        <label class="componente-option flex cursor-pointer items-center gap-3 px-4 py-3 text-sm text-[#F2FEFE] hover:bg-[rgba(19,243,247,.08)]" data-nome="<?= strtolower(htmlspecialchars($nome)) ?>">
                            <input type="checkbox" name="componentes[]" value="<?= $id ?>" class="componente-checkbox accent-[#13F3F7]">
                            <span><?= htmlspecialchars($nome) ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="componentesSelecionados" class="mt-3 flex flex-wrap gap-2"></div>
        </div>

        <?php if (isset($erros["componentes"])): ?>
            <p class="rd-error"><?= $erros["componentes"] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label class="rd-label">Arquivos de código</label>
        <button type="button" onclick="adicionarArquivos()" class="rd-file-btn">Adicionar arquivos</button>
        <input type="file" name="codigos[]" id="codigos" style="display:none;" multiple>
        <ul id="listaCodigos" class="rd-file-list"></ul>
        <?php if (isset($erros['codigo'])): ?>
            <p class="rd-error"><?= $erros['codigo'] ?></p>
        <?php endif; ?>
    </div>

    <div class="rd-field">
        <label class="rd-label">Imagens</label>
        <button type="button" onclick="adicionarImagens()" class="rd-file-btn">Adicionar imagens</button>
        <input type="file" name="imagens[]" id="imagens" accept="image/*" style="display:none;" multiple>
        <ul id="listaImagens" class="rd-file-list"></ul>
        <?php if (isset($erros['imagens'])): ?>
            <p class="rd-error"><?= $erros['imagens'] ?></p>
        <?php endif; ?>
    </div>

    <?php if (isset($_POST["id"]) || isset($projeto)): ?>
        <input type="hidden" name="id" value="<?= isset($_POST['id']) ? $_POST['id'] : (isset($projeto) ? (is_object($projeto) ? $projeto->getId() : (isset($projeto['id']) ? $projeto['id'] : '')) : '') ?>">
    <?php endif; ?>

</div>

<div class="flex items-center justify-center p-4 pt-6">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
