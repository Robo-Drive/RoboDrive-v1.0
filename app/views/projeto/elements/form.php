<div class="space-y-6">

    <!-- Nome -->
    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
            value="<?= isset($projeto) ? (is_object($projeto) ? $projeto->getNome() : (isset($projeto['nome']) ? $projeto['nome'] : '')) : '' ?>"
        >

        <?php if (isset($erros['nome'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['nome'] ?></p>
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
            class="w-full bg-black/80 border border-white px-4 py-3 text-white outline-none focus:border-[#FF1A1A] transition-all resize-none"
        ><?= isset($projeto) ? (is_object($projeto) ? $projeto->getDescricao() : (isset($projeto['descricao']) ? $projeto['descricao'] : '')) : '' ?></textarea>

        <?php if (isset($erros['descricao'])): ?>
            <p class="text-[#FF1A1A] mt-3"><?= $erros['descricao'] ?></p>
        <?php endif; ?>
    </div>

    <!-- Visibilidade -->
    <div class="relative">
        <label for="visibilidade" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Visibilidade
        </label>

        <select
            name="visibilidade"
            class="w-full h-12 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#FF1A1A] transition-all"
        >
            <option value="">Selecione</option>

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
                value="equipe"
                <?= isset($projeto)
                    ? (is_object($projeto)
                        ? ($projeto->getVisibilidade() == "equipe" ? "selected" : "")
                        : (isset($projeto["visibilidade"])
                            ? ($projeto["visibilidade"] == "equipe" ? "selected" : "")
                            : ""))
                    : "" ?>
            >
                Equipe
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
            <p class="text-[#FF1A1A] mt-3"><?= $erros['visibilidade'] ?></p>
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
        class="text-white px-5 py-2 border border-white hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition-all"
    >
        Enviar
    </button>
</div>