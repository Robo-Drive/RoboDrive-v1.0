document.addEventListener('DOMContentLoaded', () => {

    const pesquisaComponente =
        document.getElementById('pesquisaComponente');

    const listaComponentes =
        document.getElementById('listaComponentes');

    const componentesSelecionados =
        document.getElementById('componentesSelecionados');

    const container =
        document.getElementById('multiSelectComponente');

    // Verifica se os elementos existem
    if (
        !pesquisaComponente ||
        !listaComponentes ||
        !componentesSelecionados ||
        !container
    ) {
        console.error('Elementos dos componentes não encontrados.');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Abrir lista de componentes
    |--------------------------------------------------------------------------
    */

    pesquisaComponente.addEventListener('focus', () => {

        listaComponentes.classList.remove('hidden');

    });


    /*
    |--------------------------------------------------------------------------
    | Pesquisar componentes
    |--------------------------------------------------------------------------
    */

    pesquisaComponente.addEventListener('input', () => {

        const pesquisa =
            pesquisaComponente.value.toLowerCase().trim();

        const opcoes =
            document.querySelectorAll('.componente-option');

        opcoes.forEach(opcao => {

            const nome =
                opcao.dataset.nome.toLowerCase();

            if (nome.includes(pesquisa)) {

                opcao.classList.remove('hidden');

            } else {

                opcao.classList.add('hidden');

            }

        });

        listaComponentes.classList.remove('hidden');

    });

    /*
    |--------------------------------------------------------------------------
    | Selecionar componentes
    |--------------------------------------------------------------------------
    */

    const checkboxes =
        document.querySelectorAll('.componente-checkbox');

    checkboxes.forEach(checkbox => {

        checkbox.addEventListener('change', () => {

            const id = checkbox.value;
            const nome = checkbox.dataset.nome; 

            if (checkbox.checked) {
                adicionarComponente(id, nome);
            } else {
                removerComponente(id);
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Adicionar componente
    |--------------------------------------------------------------------------
    */

    function adicionarComponente(id, nome) {

        // Evita duplicação
        const existente =
            componentesSelecionados.querySelector(
                `.componente-selecionado[data-id="${id}"]`
            );

        if (existente) {
            return;
        }


        const div =
            document.createElement('div');

        div.className =
            'componente-selecionado flex items-center gap-3 bg-black/80 border border-white/20 p-3';

        div.dataset.id = id;


        div.innerHTML = `

            <div class="flex-1">

                <span class="text-white font-bold">
                    ${nome}
                </span>

            </div>


            <div class="flex items-center gap-2">

                <label class="text-gray-400 text-sm">
                    Quantidade
                </label>

                <input
                    type="number"
                    name="componentes[${id}][quantidade]"
                    value="1"
                    min="1"
                    required
                    class="w-20 h-9 bg-black border border-white px-2 text-white text-center outline-none focus:border-[#00F5F5]"
                >

            </div>


            <button
                type="button"
                class="remover-componente text-red-400 hover:text-red-300 px-2 text-xl"
                data-id="${id}"
                title="Remover componente"
            >
                ×
            </button>

        `;


        componentesSelecionados.appendChild(div);

    }


    /*
    |--------------------------------------------------------------------------
    | Remover componente
    |--------------------------------------------------------------------------
    */

    function removerComponente(id) {

        const componente =
            componentesSelecionados.querySelector(
                `.componente-selecionado[data-id="${id}"]`
            );

        if (componente) {

            componente.remove();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Botão de remover
    |--------------------------------------------------------------------------
    */

    componentesSelecionados.addEventListener('click', event => {

        const botao =
            event.target.closest('.remover-componente');

        if (!botao) {
            return;
        }

        const id = botao.dataset.id;


        // Desmarca o checkbox
        const checkbox =
            document.querySelector(
                `.componente-checkbox[value="${id}"]`
            );

        if (checkbox) {

            checkbox.checked = false;

        }


        // Remove da lista
        removerComponente(id);

    });


    /*
    |--------------------------------------------------------------------------
    | Fechar lista ao clicar fora
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', event => {

        if (!container.contains(event.target)) {

            listaComponentes.classList.add('hidden');

        }

    });

});