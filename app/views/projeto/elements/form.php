<div class="space-y-10">

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-6 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">01</span>
                Informações
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <!-- Nome -->
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

        <!-- Descrição -->
        <div class="rd-field">
            <label for="descricao" class="rd-label">Descrição</label>
            <textarea
                id="descricao"
                name="descricao"
                rows="5"
                placeholder="Conte o objetivo do projeto, como ele funciona e o que foi usado..."
                class="rd-textarea"
            ><?= isset($projeto) ? (is_object($projeto) ? $projeto->getDescricao() : (isset($projeto['descricao']) ? $projeto['descricao'] : '')) : '' ?></textarea>
            <?php if (isset($erros['descricao'])): ?>
                <p class="rd-error"><?= $erros['descricao'] ?></p>
            <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-6 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">02</span>
                Organização
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <!-- Categoria -->
        <div class="rd-field divCategoria">
            <label for="pesquisaCategoria" class="rd-label z-20">Categoria</label>

            <!-- Campo de pesquisa -->
            <div class="relative">
                <input
                    type="text"
                    id="pesquisaCategoria"
                    placeholder="Pesquisar categoria..."
                    autocomplete="off"
                    class="rd-input pr-11"
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#91B5BD]"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/></svg>
            </div>

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
                class="hidden absolute z-50 mt-1 max-h-60 w-full overflow-y-auto border-[3px] border-[#07556A] bg-[#000505] shadow-[6px_6px_0_#07556A] [scrollbar-color:#07556A_transparent] [scrollbar-width:thin]"
            >

                <?php if(isset($categorias)): ?>

                    <?php foreach($categorias as $cat): ?>

                        <div
                            class="categoria-option cursor-pointer border-b border-[#07556A]/60 px-4 py-3 text-sm text-[#F2FEFE] transition-colors duration-150 hover:bg-[#13F3F7]/10 hover:text-[#13F3F7]"
                            data-value="<?= $cat->getId() ?>"
                            data-nome="<?= strtolower($cat->getNome()) ?>"
                        >
                            <?= $cat->getNome() ?>
                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

                <!-- Criar categoria -->
                <div
                    class="categoria-option cursor-pointer px-4 py-3 text-xs font-black uppercase tracking-[.1em] text-[#13F3F7] transition-colors duration-150 hover:bg-[#13F3F7]/10"
                    data-value="criar"
                    data-nome="criar categoria"
                >
                    + Criar categoria
                </div>

            </div>

            <!-- Campo que aparece ao criar categoria -->
            <div id="novaCategoriaContainer" class="hidden mt-3 border-l-4 border-[#13F3F7] pl-3">

                <input
                    type="text"
                    name="novaCategoria"
                    id="novaCategoria"
                    placeholder="Digite o nome da nova categoria"
                    aria-label="Nome da nova categoria"
                    class="rd-input"
                >

            </div>

            <?php if (isset($erros['categoria'])): ?>
                <p class="rd-error">
                    <?= $erros['categoria'] ?>
                </p>
            <?php endif; ?>

        </div>

        <!-- Visibilidade -->
        <div class="rd-field">
            <label for="visibilidade" class="rd-label">Visibilidade</label>
            <select
                id="visibilidade"
                name="visibilidade"
                aria-describedby="visibilidadeAjuda"
                class="rd-select"
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
            <p id="visibilidadeAjuda" class="mt-2 text-xs text-[#91B5BD]">Privado: só o time do projeto vê. Público: todos os usuários da plataforma.</p>

            <?php if (isset($erros['visibilidade'])): ?>
                <p class="rd-error"><?= $erros['visibilidade'] ?></p>
            <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-6 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">03</span>
                Componentes
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <!-- Componentes -->
        <div class="rd-field">
            <label for="pesquisaComponente" class="rd-label z-10">Componentes</label>
            <div id="multiSelectComponente" class="relative border-2 border-[#07556A] bg-[#000505] transition-colors duration-200 focus-within:border-[#13F3F7]">
                <!-- Campo de pesquisa -->
                <div class="relative">
                    <input
                        type="text"
                        id="pesquisaComponente"
                        placeholder="Pesquisar componente..."
                        autocomplete="off"
                        class="h-11 w-full bg-transparent pl-4 pr-11 text-sm text-[#F2FEFE] outline-none placeholder:text-[#4E6B72]"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#91B5BD]"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/></svg>
                </div>
                <!-- Lista dos componentes -->
                <div
                    id="listaComponentes"
                    class="hidden absolute -left-[2px] -right-[2px] z-50 mt-[2px] max-h-60 overflow-y-auto border-[3px] border-[#07556A] bg-[#000505] shadow-[6px_6px_0_#07556A] [scrollbar-color:#07556A_transparent] [scrollbar-width:thin]"
                >
                    <?php if (!empty($componentes)): ?>
                        <?php foreach ($componentes as $componente): ?>
                            <?php
                                $id = $componente->getId();
                                $nome = $componente->getNome();
                            ?>
                            <label
                                class="componente-option flex cursor-pointer items-center gap-3 border-b border-[#07556A]/60 px-4 py-3 text-sm text-[#F2FEFE] transition-colors duration-150 hover:bg-[#13F3F7]/10 has-[:checked]:bg-[#13F3F7]/10"
                                data-nome="<?= strtolower($nome) ?>"
                            >
                                <input
                                    type="checkbox"
                                    value="<?= $id ?>"
                                    data-nome="<?= $nome ?>"
                                    class="componente-checkbox peer h-5 w-5 shrink-0 cursor-pointer appearance-none border-2 border-[#0795A5] bg-[#000505] transition-colors checked:border-[#13F3F7] checked:bg-[#13F3F7] checked:shadow-[inset_0_0_0_2px_#000505] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#13F3F7]"
                                >
                                <span class="peer-checked:font-bold peer-checked:text-[#13F3F7]">
                                    <?= $nome ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-4 py-3 text-sm text-[#91B5BD]">
                            Nenhum componente cadastrado.
                        </div>
                    <?php endif; ?>

                </div>
                <!-- Componentes selecionados (itens criados por componente.js, estilizados a partir deste container) -->
                <div
                    id="componentesSelecionados"
                    class="border-t-2 border-[#07556A] [counter-reset:componente] empty:before:block empty:before:px-4 empty:before:py-4 empty:before:text-center empty:before:text-xs empty:before:uppercase empty:before:tracking-[.12em] empty:before:text-[#91B5BD] empty:before:content-['Nenhum_componente_selecionado'] [&:not(:empty)]:before:block [&:not(:empty)]:before:bg-[#082B3A] [&:not(:empty)]:before:px-4 [&:not(:empty)]:before:py-2 [&:not(:empty)]:before:text-[.6rem] [&:not(:empty)]:before:font-bold [&:not(:empty)]:before:uppercase [&:not(:empty)]:before:tracking-[.2em] [&:not(:empty)]:before:text-[#91B5BD] [&:not(:empty)]:before:content-['Selecionados'] [&>.componente-selecionado]:flex-wrap [&>.componente-selecionado]:gap-x-3 [&>.componente-selecionado]:gap-y-1 max-[359px]:[&>.componente-selecionado]:gap-x-2 max-[359px]:[&_label]:tracking-normal sm:[&>.componente-selecionado]:flex-nowrap [&>.componente-selecionado>.flex-1]:basis-[calc(100%-2.25rem)] sm:[&>.componente-selecionado>.flex-1]:basis-0 [&>.componente-selecionado>div:nth-of-type(2)]:ml-auto sm:[&>.componente-selecionado>div:nth-of-type(2)]:ml-0 [&>.componente-selecionado]:border-x-0 [&>.componente-selecionado]:border-b-0 [&>.componente-selecionado]:border-t [&>.componente-selecionado]:border-[#07556A]/60 [&>.componente-selecionado]:bg-transparent [&>.componente-selecionado]:px-4 [&>.componente-selecionado]:py-2 [&>.componente-selecionado]:[counter-increment:componente] [&>.componente-selecionado]:before:font-['Orbitron'] [&>.componente-selecionado]:before:text-xs [&>.componente-selecionado]:before:font-black [&>.componente-selecionado]:before:text-[#13F3F7] [&>.componente-selecionado]:before:content-[counter(componente,decimal-leading-zero)] [&_.flex-1]:min-w-0 [&_.flex-1_span]:block [&_.flex-1_span]:truncate [&_.flex-1_span]:text-sm [&_.flex-1_span]:text-[#F2FEFE] [&_label]:text-[.6rem] [&_label]:font-bold [&_label]:uppercase [&_label]:tracking-[.15em] [&_label]:text-[#91B5BD] [&_input]:h-9 [&_input]:w-16 [&_input]:border-2 [&_input]:border-[#07556A] [&_input]:bg-[#06141C] [&_input]:font-bold [&_input]:text-[#F2FEFE] [&_input:focus]:border-[#13F3F7] [&_.remover-componente]:flex [&_.remover-componente]:h-9 [&_.remover-componente]:w-9 [&_.remover-componente]:items-center [&_.remover-componente]:justify-center [&_.remover-componente]:border-2 [&_.remover-componente]:border-transparent [&_.remover-componente]:px-0 [&_.remover-componente]:text-2xl [&_.remover-componente]:leading-none [&_.remover-componente]:text-[#91B5BD] [&_.remover-componente]:transition-colors [&_.remover-componente:hover]:border-[#F2A058] [&_.remover-componente:hover]:text-[#F2A058]"
                ></div>
            </div>
            <?php if (isset($erros["componentes"])): ?>
                <p class="rd-error">
                    <?= $erros["componentes"] ?>
                </p>
            <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-6 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">04</span>
                Arquivos
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <!-- Arquivos de código -->
        <div class="rd-field">
            <label for="codigos" class="rd-label">Arquivos de código</label>
            <button type="button" onclick="adicionarArquivos()" class="group flex w-full items-center gap-4 border-2 border-[#07556A] bg-[#000505] px-4 py-3 text-left transition-colors duration-200 hover:border-[#13F3F7] focus-visible:border-[#13F3F7] focus-visible:outline-none">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center border-2 border-[#07556A] text-[#13F3F7] transition-colors duration-200 group-hover:border-[#13F3F7]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="h-5 w-5"><path d="m8 7-5 5 5 5"/><path d="m16 7 5 5-5 5"/><path d="m14 4-4 16"/></svg>
                </span>
                <span class="flex min-w-0 flex-1 flex-col">
                    <span class="text-xs font-black uppercase tracking-[.08em] text-[#F2FEFE]">Adicionar arquivos</span>
                    <span class="text-xs leading-snug text-[#91B5BD]">.ino, .py, .cpp, .c, .h, .java, .js...</span>
                </span>
                <span class="text-2xl font-light leading-none text-[#13F3F7]" aria-hidden="true">+</span>
            </button>

            <input type="file" name="codigos[]" id="codigos" style="display:none;" multiple>

            <!-- Itens criados por inputFiles.js, estilizados a partir desta lista -->
            <ul id="listaCodigos" class="mt-3 flex flex-col gap-2 empty:hidden [&>li]:gap-3 [&>li]:border-2 [&>li]:border-[#07556A] [&>li]:bg-[#06141C] [&>li]:p-3 [&>li:hover]:border-[#13F3F7] [&>li>div]:gap-3 [&>li>div>span]:min-w-0 [&>li>div>span]:text-[#F2FEFE] [&>li>div>span]:before:mr-2 [&>li>div>span]:before:font-black [&>li>div>span]:before:text-[#13F3F7] [&>li>div>span]:before:content-['#'] [&>li>div>button]:flex [&>li>div>button]:h-8 [&>li>div>button]:w-8 [&>li>div>button]:shrink-0 [&>li>div>button]:items-center [&>li>div>button]:justify-center [&>li>div>button]:border-2 [&>li>div>button]:border-transparent [&>li>div>button]:transition-colors [&>li>div>button:hover]:border-[#F2A058] [&>li>div>button_img]:h-4 [&>li>div>button_img]:w-4 [&>li>input]:h-10 [&>li>input]:border-2 [&>li>input]:border-[#07556A] [&>li>input]:bg-[#000505] [&>li>input]:text-[#F2FEFE] [&>li>input]:placeholder:text-[#4E6B72] [&>li>input:focus]:border-[#13F3F7]"></ul>

            <?php if (isset($erros['codigo'])): ?>
                <p class="rd-error"><?= $erros['codigo'] ?></p>
            <?php endif; ?>
        </div>

        <!-- Imagens -->
        <div class="rd-field">
            <label for="imagens" class="rd-label">Imagens</label>
            <button
                type="button"
                onclick="adicionarImagens()"
                class="group flex w-full items-center gap-4 border-2 border-[#07556A] bg-[#000505] px-4 py-3 text-left transition-colors duration-200 hover:border-[#13F3F7] focus-visible:border-[#13F3F7] focus-visible:outline-none"
            >
                <span class="flex h-10 w-10 shrink-0 items-center justify-center border-2 border-[#07556A] text-[#13F3F7] transition-colors duration-200 group-hover:border-[#13F3F7]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="h-5 w-5"><path d="M3 4h18v16H3z"/><path d="m3 16 5-5 4 4 3-3 6 6"/><circle cx="15.5" cy="8.5" r="1.5"/></svg>
                </span>
                <span class="flex min-w-0 flex-1 flex-col">
                    <span class="text-xs font-black uppercase tracking-[.08em] text-[#F2FEFE]">Adicionar imagens</span>
                    <span class="text-xs leading-snug text-[#91B5BD]">.jpg, .png, .gif, .svg, .webp</span>
                </span>
                <span class="text-2xl font-light leading-none text-[#13F3F7]" aria-hidden="true">+</span>
            </button>

            <input
                type="file"
                name="imagens[]"
                id="imagens"
                accept="image/*"
                style="display:none;"
                multiple
            >

            <!-- Itens criados por images.js, estilizados a partir desta lista -->
            <ul id="listaImagens" class="mt-3 grid grid-cols-2 gap-3 empty:hidden sm:grid-cols-3 [&>li]:relative [&>li]:flex-col [&>li]:items-stretch [&>li]:justify-start [&>li]:border-2 [&>li]:border-[#07556A] [&>li]:bg-[#06141C] [&>li]:p-0 [&>li:hover]:border-[#13F3F7] [&>li>div]:min-w-0 [&>li>div]:flex-col [&>li>div]:items-stretch [&>li>div]:gap-0 [&>li>div>img]:aspect-square [&>li>div>img]:h-auto [&>li>div>img]:w-full [&>li>div>img]:rounded-none [&>li>div>img]:border-0 [&>li>div>img]:border-b-2 [&>li>div>img]:border-[#07556A] [&>li>div>div]:min-w-0 [&>li>div>div]:px-2.5 [&>li>div>div]:py-2 [&>li>div>div>p:first-child]:truncate [&>li>div>div>p:first-child]:text-xs [&>li>div>div>p:first-child]:text-[#F2FEFE] [&>li>div>div>p:last-child]:text-[.6rem] [&>li>div>div>p:last-child]:font-bold [&>li>div>div>p:last-child]:uppercase [&>li>div>div>p:last-child]:tracking-[.12em] [&>li>div>div>p:last-child]:text-[#91B5BD] [&>li>button]:absolute [&>li>button]:right-1.5 [&>li>button]:top-1.5 [&>li>button]:flex [&>li>button]:h-8 [&>li>button]:w-8 [&>li>button]:items-center [&>li>button]:justify-center [&>li>button]:border-2 [&>li>button]:border-[#07556A] [&>li>button]:bg-[#000505]/85 [&>li>button]:transition-colors [&>li>button:hover]:border-[#F2A058] [&>li>button_img]:h-4 [&>li>button_img]:w-4"></ul>

            <?php if (isset($erros['imagens'])): ?>
                <p class="rd-error"><?= $erros['imagens'] ?></p>
            <?php endif; ?>
        </div>
    </fieldset>

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
<div class="flex items-center justify-center p-4 pt-8">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>
