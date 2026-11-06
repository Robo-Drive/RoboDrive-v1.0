document.addEventListener('DOMContentLoaded', () =>
{
    // Seleciona tanto o formulário de cadastro quanto o de atualização.
    const form = document.querySelector(
        'form[action*="/projeto/salvar"], form[action*="/projeto/atualizar"]'
    );

    if (!form) return;

    const btnSubmit = form.querySelector('button[type="submit"]');

    if (!btnSubmit) return;

    form.addEventListener('submit', async (e) =>
    {
        e.preventDefault();

        limparErros();

        const textoOriginal = btnSubmit.innerHTML;

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Enviando...';

        try
        {
            // Captura campos, arquivos e arrays do formulário.
            const formData = new FormData(form);

            // Envia os dados ao PHP.
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Lê a resposta do servidor.
            const data = await response.json();

            console.log('Status HTTP:', response.status);
            console.log('Resposta do servidor:', data);

            // Cadastro ou atualização concluídos.
            if (response.ok && data.sucesso === true)
            {
                if (data.redirect)
                {
                    window.location.href = data.redirect;
                    return;
                }

                console.error(
                    'URL de redirecionamento não informada:',
                    data
                );

                alert(
                    data.mensagem ||
                    'Operação concluída, mas a URL de redirecionamento não foi informada.'
                );

                return;
            }

            // Exibe erros de validação nos respectivos campos.
            if (data.erros)
            {
                exibirErros(data.erros);
            }
            else if (data.mensagem)
            {
                alert(data.mensagem);
            }
            else
            {
                alert('Não foi possível concluir a operação.');
            }
        }
        catch (error)
        {
            console.error('Erro na requisição AJAX:', error);

            alert(
                'Ocorreu uma falha na comunicação com o servidor. ' +
                'Verifique o console para mais detalhes.'
            );
        }
        finally
        {
            // Restaura o botão se a página continuar aberta.
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = textoOriginal;
        }
    });


    /**
     * Renderiza os erros abaixo dos respectivos campos.
     */
    function exibirErros(erros)
    {
        for (const [campo, mensagem] of Object.entries(erros))
        {
            let inputElement = document.getElementById(campo);

            // Mapeamento dos campos especiais.
            if (campo === 'categoria')
            {
                inputElement = document.getElementById('pesquisaCategoria');
            }
            else if (campo === 'novaCategoria')
            {
                inputElement = document.getElementById('novaCategoria');

                document.getElementById('novaCategoriaContainer')
                    ?.classList.remove('hidden');
            }
            else if (campo === 'componentes')
            {
                inputElement = document.getElementById('multiSelectComponente');
            }
            else if (campo === 'codigos')
            {
                inputElement = document.getElementById('listaCodigos');
            }
            else if (campo === 'codigos_descricao')
            {
                inputElement = document.getElementById('listaCodigos');
            }
            else if (campo.startsWith('codigos_descricao_'))
            {
                inputElement = document.getElementById(campo);

                if (!inputElement)
                {
                    inputElement = document.getElementById('listaCodigos');
                }
            }
            else if (campo === 'imagens')
            {
                inputElement = document.getElementById('listaImagens');
            }

            if (!inputElement)
            {
                console.warn(
                    `Campo não encontrado para o erro: ${campo}`
                );

                continue;
            }

            const containerField =
                inputElement.closest('.rd-field') ||
                inputElement.parentElement;

            if (!containerField)
            {
                console.warn(
                    `Container não encontrado para o campo: ${campo}`
                );

                continue;
            }

            // Cria a mensagem de erro.
            const pErro = document.createElement('p');

            pErro.className =
                'rd-error text-red-400 text-xs mt-1 transition-all';

            pErro.dataset.ajaxErro = 'true';

            pErro.textContent = Array.isArray(mensagem)
                ? mensagem.join(', ')
                : mensagem;

            containerField.appendChild(pErro);
        }
    }


    /**
     * Remove as mensagens de erro anteriores do AJAX.
     */
    function limparErros()
    {
        document
            .querySelectorAll('[data-ajax-erro="true"]')
            .forEach((el) => el.remove());
    }
});