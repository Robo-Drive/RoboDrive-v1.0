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

        <?php if (isset($projeto)): ?>
        <div class="rd-field mb-6">
            <label for="descricao_alteracao" class="rd-label">Notas da Nova Versão (O que mudou?)</label>
            <input type="text" id="descricao_alteracao" name="descricao_alteracao" placeholder="Ex: Substituído servo motor por motor de passo e ajustado script de calibração" class="rd-input">
            <p class="mt-1 text-xs text-[#91B5BD]">Se você alterar componentes ou códigos, uma nova versão do projeto será criada automaticamente.</p>
        </div>
        <?php endif; ?>
    </fieldset>

    <fieldset class="min-w-0 space-y-6">
        <legend class="mb-6 w-full">
            <span class="flex items-center gap-3 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">
                <span class="font-['Orbitron'] font-black text-[#13F3F7]">02</span>
                Organização
                <span class="h-[2px] flex-1 bg-[#07556A]"></span>
            </span>
        </legend>

        <!-- Senha de Acesso do Projeto -->
        <?php if (!isset($projeto)): ?>
            <div class="rd-field">
                <label for="senha" class="rd-label">Senha de Entrada no Projeto</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Defina a senha para novos membros entrarem..."
                    class="rd-input"
                >
                <p class="mt-1 text-xs text-[#91B5BD]">Esta senha será exigida junto ao Hash para novos membros solicitarem entrada.</p>
                <?php if (isset($erros['senha'])): ?>
                    <p class="rd-error"><?= $erros['senha'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Confirmar Senha do Projeto -->
            <div class="rd-field">
                <label for="confirmar_senha" class="rd-label">Confirmar Senha do Projeto</label>
                <input
                    type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    placeholder="Repita a senha de acesso..."
                    class="rd-input"
                >
                <?php if (isset($erros['confirmar_senha'])): ?>
                    <p class="rd-error"><?= $erros['confirmar_senha'] ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Categoria -->
        <div class="rd-field divCategoria">
            <?php
                // Identifica o ID e o Nome da Categoria do projeto (se estiver editando)
                $catIdSelecionada = '';
                $catNomeSelecionado = '';
                if (isset($projeto)) {
                    if (is_object($projeto)) {
                        $catIdSelecionada = $projeto->getCategoria()?->getId() ?? '';
                        $catNomeSelecionado = $projeto->getCategoria()?->getNome() ?? '';
                    } else {
                        $catIdSelecionada = $projeto['categoria_id'] ?? $projeto['categoria'] ?? '';
                    }
                }
                if (!empty($catIdSelecionada) && empty($catNomeSelecionado) && isset($categorias)) {
                    foreach ($categorias as $c) {
                        if ($c->getId() == $catIdSelecionada) {
                            $catNomeSelecionado = $c->getNome();
                            break;
                        }
                    }
                }
            ?>

            <label for="pesquisaCategoria" class="rd-label z-25">Categoria</label>

            <!-- Campo de pesquisa preenchido automaticamente com a categoria atual -->
            <div class="relative">
                <input
                    type="text"
                    id="pesquisaCategoria"
                    placeholder="Pesquisar categoria..."
                    autocomplete="off"
                    value="<?= htmlspecialchars(html_entity_decode($catNomeSelecionado)) ?>"
                    class="rd-input pr-11"
                >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#91B5BD]"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/></svg>
            </div>

            <!-- Select real com a opção já 'selected' -->
            <select name="categoria" id="categoria" class="hidden">
                <option value="">Selecione</option>
                <?php if(isset($categorias)): ?>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat->getId() ?>" <?= ($cat->getId() == $catIdSelecionada) ? 'selected' : '' ?>>
                            <?= $cat->getNome() ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
                <option value="criar">+ Criar categoria</option>
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
                <?php else: ?>
                    <div class="px-4 py-3 text-gray-400">
                        Nenhuma categoria cadastrada.
                    </div>
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
        <?php
            $equipeIdVisibilidade = $_GET['equipe_id'] ?? null;

            if (empty($equipeIdVisibilidade) && isset($projeto)) {
                if (is_object($projeto)) {
                    $equipeIdVisibilidade = $projeto->getEquipe()?->getId() ?? null;
                } else {
                    $equipeIdVisibilidade = $projeto['equipe_id'] ?? null;
                }
            }

            $visibilidadeAtual = 'privado';

            if (isset($projeto)) {
                $visibilidadeAtual = is_object($projeto)
                    ? $projeto->getVisibilidade()
                    : ($projeto['visibilidade'] ?? 'privado');
            }
        ?>

        <div class="rd-field mt-6">
            <label for="visibilidade" class="rd-label">Visibilidade</label>

            <select
                id="visibilidade"
                name="visibilidade"
                aria-describedby="visibilidadeAjuda"
                class="rd-select"
            >
                <option value="privado" <?= $visibilidadeAtual === 'privado' ? 'selected' : '' ?>>
                    Privado
                </option>
                <option value="publico" <?= $visibilidadeAtual === 'publico' ? 'selected' : '' ?>>
                    Público
                </option>
                <?php if (!empty($equipeIdVisibilidade)): ?>
                    <option value="equipe" <?= $visibilidadeAtual === 'equipe' ? 'selected' : '' ?>>
                        Visível para equipe
                    </option>
                <?php endif; ?>
            </select>

            <p id="visibilidadeAjuda" class="mt-2 text-xs text-[#91B5BD]">
                Privado: somente os responsáveis pelo projeto podem visualizar.
                Público: todos os usuários da plataforma.
                <?php if (!empty($equipeIdVisibilidade)): ?>
                    Visível para equipe: somente os integrantes da equipe vinculada.
                <?php endif; ?>
            </p>

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

                <!-- Componentes selecionados -->
                <div id="componentesSelecionados" class="border-t-2 border-[#07556A] p-2 space-y-2">
                    <?php if (!empty($componentesAtuais)): ?>
                        <?php foreach ($componentesAtuais as $comp): ?>
                            <div class="componente-selecionado flex items-center gap-3 bg-black/80 border border-white/20 p-3" data-id="<?= $comp->getId() ?>">
                                <div class="flex-1">
                                    <span class="text-white font-bold"><?= htmlspecialchars(html_entity_decode($comp->getNome())) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-gray-400 text-sm">Quantidade</label>
                                    <input type="number" name="componentes[<?= $comp->getId() ?>][quantidade]" value="<?= $comp->getQuantidade() ?>" class="w-20 h-9 bg-black border border-white px-2 text-white text-center outline-none focus:border-[#00F5F5]">
                                </div>
                                <button type="button" class="remover-componente text-red-400 hover:text-red-300 px-2 text-xl" data-id="<?= $comp->getId() ?>" title="Remover componente">×</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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

        <?php if (!empty($codigosAtuais)): ?>
        <div class="mb-4">
            <p class="text-xs font-bold text-[#13F3F7] uppercase tracking-wider mb-2 font-['Orbitron']">
                Arquivos da versão <?= !empty($versaoAtual) ? 'v' . $versaoAtual['versao'] : 'atual' ?> (desmarque para retirar da nova versão):
            </p>
            <ul class="space-y-2">
                <?php foreach ($codigosAtuais as $cod): ?>
                    <li class="flex items-center justify-between border-2 border-[#07556A] bg-[#06141C] p-3 text-xs">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="codigos_manter[]" value="<?= $cod->getId() ?>" checked class="accent-[#13F3F7] h-4 w-4">
                            <div>
                                <span class="font-mono font-bold text-[#F2FEFE]"><?= htmlspecialchars(html_entity_decode(basename($cod->getCaminho()))) ?></span>
                                <span class="block text-[.65rem] text-[#91B5BD]"><?= htmlspecialchars(html_entity_decode($cod->getDescricao() ?? 'Sem descrição')) ?></span>
                            </div>
                        </label>
                        <span class="text-[.6rem] text-[#4E6B72] uppercase font-mono">Manter na versão</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Arquivos de código -->
        <div class="rd-field">
            <label for="codigos" class="rd-label">Arquivos de código</label>

            <button
                type="button"
                onclick="adicionarArquivos()"
                class="group flex w-full items-center gap-4 border-2 border-[#07556A] bg-[#000505] px-4 py-3 text-left transition-colors duration-200 hover:border-[#13F3F7] focus-visible:border-[#13F3F7] focus-visible:outline-none"
            >
                <span class="flex h-10 w-10 shrink-0 items-center justify-center border-2 border-[#07556A] text-[#13F3F7] transition-colors duration-200 group-hover:border-[#13F3F7]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true" class="h-5 w-5">
                        <path d="m8 7-5 5 5 5"/>
                        <path d="m16 7 5 5-5 5"/>
                        <path d="m14 4-4 16"/>
                    </svg>
                </span>

                <span class="flex min-w-0 flex-1 flex-col">
                    <span class="text-xs font-black uppercase tracking-[.08em] text-[#F2FEFE]">
                        Adicionar arquivos
                    </span>
                    <span class="text-xs leading-snug text-[#91B5BD]">
                        .ino, .py, .cpp, .c, .h, .java, .js...
                    </span>
                </span>

                <span class="text-2xl font-light leading-none text-[#13F3F7]" aria-hidden="true">
                    +
                </span>
            </button>

            <input
                type="file"
                name="codigos[]"
                id="codigos"
                style="display:none;"
                multiple
            >

            <ul id="listaCodigos" class="mt-3 flex flex-col gap-2 empty:hidden"></ul>

            <?php if (isset($erros['codigos'])): ?>
                <p class="rd-error">
                    <?= htmlspecialchars(html_entity_decode($erros['codigos'])) ?>
                </p>
            <?php endif; ?>

            <?php if (isset($erros['codigos_descricao'])): ?>
                <p class="rd-error">
                    <?= htmlspecialchars(html_entity_decode($erros['codigos_descricao'])) ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- FOTOS ATUAIS DO PROJETO -->
        <?php if (!empty($imagensAtuais)): ?>
            <div class="mb-4">
                <p class="text-xs font-bold text-[#13F3F7] uppercase tracking-wider mb-2 font-['Orbitron']">
                    Fotos atuais do projeto (desmarque para retirar):
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <?php foreach ($imagensAtuais as $img): ?>
                        <div class="relative border-2 border-[#07556A] bg-[#06141C] p-2 flex flex-col justify-between group hover:border-[#13F3F7] transition">
                            <a 
                                href="<?= URL_BASE ?>/arquivo?arquivo=<?= urlencode($img->getCaminho()) ?>" 
                                target="_blank" 
                                title="Clique para abrir imagem em tamanho real" 
                                class="block aspect-square overflow-hidden bg-black mb-2 relative"
                            >
                                <img 
                                    src="<?= URL_BASE ?>/arquivo?arquivo=<?= urlencode($img->getCaminho()) ?>" 
                                    alt="Foto do projeto" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-200"
                                >
                                <span class="absolute bottom-1 right-1 bg-black/80 text-[.6rem] text-[#13F3F7] px-1.5 py-0.5 border border-[#07556A]">
                                    🔍 Ver
                                </span>
                            </a>

                            <label class="flex items-center gap-2 cursor-pointer bg-[#000505] p-1.5 border border-[#07556A] text-xs">
                                <input 
                                    type="checkbox" 
                                    name="imagens_manter[]" 
                                    value="<?= $img->getId() ?>" 
                                    checked 
                                    class="accent-[#13F3F7] h-4 w-4"
                                >
                                <span class="text-[.65rem] text-[#F2FEFE] font-bold uppercase select-none">
                                    Manter foto
                                </span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

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

            <ul id="listaImagens" class="mt-3 grid grid-cols-2 gap-3 empty:hidden sm:grid-cols-3"></ul>

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

    <?php 
        $equipeIdHidden = $_GET['equipe_id'] ?? null;
        if (empty($equipeIdHidden) && isset($projeto)) {
            $equipeIdHidden = is_object($projeto) 
                ? $projeto->getEquipe()?->getId() 
                : ($projeto['equipe_id'] ?? null);
        }
    ?>
    <?php if (!empty($equipeIdHidden)): ?>
        <input type="hidden" name="equipe_id" value="<?= htmlspecialchars($equipeIdHidden) ?>">
    <?php endif; ?>

</div>

<!-- Botão -->
<div class="flex items-center justify-center p-4 pt-8">
    <button type="submit" class="rd-btn rd-btn-primary">Enviar</button>
</div>