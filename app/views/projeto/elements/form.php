<div class="space-y-6">

    <!-- Nome -->
    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
            value="<?= isset($projeto) ? (is_object($projeto) ? $projeto->getNome() : (isset($projeto['nome']) ? $projeto['nome'] : '')) : '' ?>"
        >

        <?php if (isset($erros['nome'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['nome'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Descrição -->
    <div class="relative">
        <label for="descricao" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Descrição
        </label>

        <textarea
            name="descricao"
            rows="5"
            class="w-full bg-black/80 border border-white px-4 py-3 text-white outline-none focus:border-[#00F5F5] transition-all resize-none"
        ><?= isset($projeto) ? (is_object($projeto) ? $projeto->getDescricao() : (isset($projeto['descricao']) ? $projeto['descricao'] : '')) : '' ?></textarea>

        <?php if (isset($erros['descricao'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['descricao'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Categoria -->
    <div class="relative divCategoria">

        <label
            for="pesquisaCategoria"
            class="absolute -top-3 left-3 bg-black px-2 text-white font-bold z-20"
        >
            Categoria
        </label>

        <!-- Campo de pesquisa -->
        <input
            type="text"
            id="pesquisaCategoria"
            placeholder="Pesquisar categoria..."
            autocomplete="off"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
        >

        <!-- Select real -->
        <select
            name="categoria"
            id="categoria"
            class="hidden"
        >
            <option value="">Selecione</option>

            <?php if(isset($categorias)): ?>
                <?php foreach($categorias as $cat): ?>

                    <option
                        value="<?= $cat->getId() ?>"
                        <?= isset($projeto)
                            ? (
                                is_object($projeto)
                                    ? ($projeto->getCategoria() == $cat->getId() ? "selected" : "")
                                    : (
                                        isset($projeto["categoria"])
                                            ? ($projeto["categoria"] == $cat->getId() ? "selected" : "")
                                            : ""
                                    )
                            )
                            : ""
                        ?>
                    >
                        <?= $cat->getNome() ?>
                    </option>

                <?php endforeach; ?>
            <?php endif; ?>

            <option value="criar">
                Criar categoria
            </option>
        </select>

        <!-- Lista pesquisável -->
        <div
            id="listaCategorias"
            class="hidden absolute z-50 w-full mt-1 bg-black border border-white max-h-60 overflow-y-auto"
        >

            <?php if(isset($categorias)): ?>

                <?php foreach($categorias as $cat): ?>

                    <div
                        class="categoria-option px-4 py-3 text-white hover:bg-[#00F5F5]/10 cursor-pointer"
                        data-value="<?= $cat->getId() ?>"
                        data-nome="<?= strtolower($cat->getNome()) ?>"
                    >
                        <?= $cat->getNome() ?>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

            <!-- Criar categoria -->
            <div
                class="categoria-option px-4 py-3 text-[#00F5F5] hover:bg-[#00F5F5]/10 cursor-pointer"
                data-value="criar"
                data-nome="criar categoria"
            >
                + Criar categoria
            </div>

        </div>

        <!-- Campo que aparece ao criar categoria -->
        <div id="novaCategoriaContainer" class="hidden mt-3">

            <input
                type="text"
                name="novaCategoria"
                id="novaCategoria"
                placeholder="Digite o nome da nova categoria"
                class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
            >

        </div>

        <?php if (isset($erros['categoria'])): ?>
            <p class="text-[#00F5F5] mt-3">
                <?= $erros['categoria'] ?>
            </p>
        <?php endif; ?>

    </div>


    <!-- Visibilidade -->
    <div class="relative">
        <label for="visibilidade" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Visibilidade
        </label>

        <select
            name="visibilidade"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
        >
            <option
                value="privado"
                <?= isset($projeto)
                    ? (is_object($projeto)
                        ? ($projeto->getVisibilidade() == "privado" ? "selected" : "")
                        : (isset($projeto["visibilidade"])
                            ? ($projeto["visibilidade"] == "privado" ? "selected" : "")
                            : ""))
                    : "" ?>
            >
                Privado
            </option>

            <option
                value="publico"
                <?= isset($projeto)
                    ? (is_object($projeto)
                        ? ($projeto->getVisibilidade() == "publico" ? "selected" : "")
                        : (isset($projeto["visibilidade"])
                            ? ($projeto["visibilidade"] == "publico" ? "selected" : "")
                            : ""))
                    : "" ?>
            >
                Público
            </option>
        </select>
        
        <?php if (isset($erros['visibilidade'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['visibilidade'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Componentes -->
    <div class="relative">
        <label class="absolute -top-3 left-3 bg-black px-2 text-white font-bold z-10">
            Componentes
        </label>
        <div id="multiSelectComponente" class="relative">
            <!-- Campo de pesquisa -->
            <input
                type="text"
                id="pesquisaComponente"
                placeholder="Pesquisar componente..."
                autocomplete="off"
                class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
            >
            <!-- Lista dos componentes -->
            <div
                id="listaComponentes"
                class="hidden absolute z-50 w-full mt-1 bg-black border border-white max-h-60 overflow-y-auto"
            >
                <?php if (!empty($componentes)): ?>
                    <?php foreach ($componentes as $componente): ?>
                        <?php
                            $id = $componente->getId();
                            $nome = $componente->getNome();
                        ?>
                        <label
                            class="componente-option flex items-center gap-3 px-4 py-3 text-white hover:bg-[#00F5F5]/10 cursor-pointer"
                            data-nome="<?= strtolower($nome) ?>"
                        >
                            <input
                                type="checkbox"
                                value="<?= $id ?>"
                                data-nome="<?= $nome ?>"
                                class="componente-checkbox accent-[#00F5F5]"
                            >
                            <span>
                                <?= $nome ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="px-4 py-3 text-gray-400">
                        Nenhum componente cadastrado.
                    </div>
                <?php endif; ?>

            </div>
            <!-- Componentes selecionados -->
            <div
                id="componentesSelecionados"
                class="space-y-2 mt-3"
            >
            </div>
        </div>
        <?php if (isset($erros["componentes"])): ?>
            <p class="text-[#00F5F5] mt-3">
                <?= $erros["componentes"] ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="relative">
        <label for="codigos" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Arquivos de código
        </label>
        <button type="button" onclick="adicionarArquivos()" class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all">
            Adicionar arquivos
        </button>
        
        <input type="file" name="codigos[]" id="codigos" style="display:none;" multiple>
        
        <!-- Adicionado space-y-2 mt-3 para espaçamento vertical entre os cards -->
        <ul id="listaCodigos" class="space-y-2 mt-3"></ul>
        
        <?php if (isset($erros['codigo'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['codigo'] ?></p>
        <?php endif; ?>
    </div>

    
    <div class="relative">
        <label for="imagens" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Imagens
        </label>

        <button
            type="button"
            onclick="adicionarImagens()"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
        >
            Adicionar imagens
        </button>

        <input
            type="file"
            name="imagens[]"
            id="imagens"
            accept="image/*"
            style="display:none;"
            multiple
        >

        <ul id="listaImagens" class="space-y-2 mt-3"></ul>

        <?php if (isset($erros['imagens'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['imagens'] ?></p>
        <?php endif; ?>
    </div>

    <!-- ID oculto -->
    <?php if (isset($_POST["id"]) || isset($projeto)): ?>
        <input
            type="hidden"
            name="id"
            value="<?= isset($_POST['id'])
                ? $_POST['id']
                : (isset($projeto)
                    ? (is_object($projeto)
                        ? $projeto->getId()
                        : (isset($projeto['id']) ? $projeto['id'] : ''))
                    : '') ?>"
        >
    <?php endif; ?>

</div>

<!-- Botão -->
<div class="flex justify-center items-center p-4">
    <button
        type="submit"
        class="text-white px-5 py-2 border border-white hover:border-[#00F5F5] hover:text-[#00F5F5] transition-all"
    >
        Enviar
    </button>
</div>