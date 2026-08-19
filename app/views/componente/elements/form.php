<div class="space-y-6">

    <div class="relative">
        <label for="nome" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Nome
        </label>

        <input
            type="text"
            name="nome"
            class="w-full h-10 bg-black/80 border border-white px-4 text-white outline-none focus:border-[#00F5F5] transition-all"
            value="<?= isset($componente) ? (is_object($componente) ? $componente->getNome() : (isset($componente["nome"]) ? $componente["nome"] : "")) : "" ?>"
        >

        <?php if (isset($erros["nome"])): ?>
            <p class="text-[#00F5F5] mt-3">
                <?= $erros["nome"] ?>
            </p>
        <?php endif; ?>
    </div>


    <div class="relative">
        <label for="descricao" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Descrição
        </label>

        <textarea
            name="descricao"
            rows="4"
            class="w-full bg-black/80 border border-white px-4 py-3 text-white outline-none focus:border-[#00F5F5] transition-all resize-none"
        ><?= isset($componente) ? (is_object($componente) ? $componente->getDescricao() : (isset($componente["descricao"]) ? $componente["descricao"] : "")) : "" ?></textarea>

        <?php if (isset($erros["descricao"])): ?>
            <p class="text-[#00F5F5] mt-3">
                <?= $erros["descricao"] ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="relative">
        <label for="imagem" class="absolute -top-3 left-3 bg-black px-2 text-white font-bold">
            Imagem
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
            name="imagem"
            id="imagens"
            accept="image/*"
            style="display:none;"
        >

        <ul id="listaImagens" class="space-y-2 mt-3"></ul>

        <?php if (isset($erros['imagens'])): ?>
            <p class="text-[#00F5F5] mt-3"><?= $erros['imagens'] ?></p>
        <?php endif; ?>
    </div>


    <?php if (isset($_POST["id"]) || isset($componente)): ?>
        <input
            type="hidden"
            name="id"
            value="<?= isset($_POST["id"]) ? $_POST["id"] : (isset($componente) ? (is_object($componente) ? $componente->getId() : (isset($componente["id"]) ? $componente["id"] : "")) : "") ?>"
        >
    <?php endif; ?>

</div>


<div class="flex justify-center items-center p-4">
    <button
        type="submit"
        class="text-white px-6 py-2 border border-white hover:border-[#00F5F5] hover:text-[#00F5F5] transition-all"
    >
        Enviar
    </button>
</div>