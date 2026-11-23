document.addEventListener('DOMContentLoaded', () =>
{
    const form = document.querySelector('form[action*="/projeto/"]');

    if (!form)
    {
        return;
    }

    form.addEventListener('submit', async (e) =>
    {
        e.preventDefault();

        // Limpa erros anteriores da tela.
        limparErros();

        // Botão de envio (desativa para evitar cliques duplos).
        const submitBtn = form.querySelector('button[type="submit"]');
        const textoOriginal = submitBtn ? submitBtn.innerText : 'Enviar';

        if (submitBtn)
        {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Processando...';
        }

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

            // Cadastro ou atualização concluídos.
            if (response.ok && data.sucesso === true)
            {
                if (data.redirect)
                {
                    window.location.href = data.redirect;
                    return;
                }

                rdAviso(data.mensagem || 'Operação realizada com sucesso!', 'sucesso', true);
                window.location.reload();
                return;
            }

            // Erros de validação (HTTP 422).
            if (response.status === 422 && data.erros)
            {
                exibirErros(data.erros);
                rolarParaPrimeiroErro();
                return;
            }

            // Outros erros HTTP (403, 404, 500 etc).
            if (data.mensagem)
            {
                exibirMensagemGeral(data.mensagem);
            }
            else
            {
                exibirMensagemGeral(
                    'Ocorreu um erro inesperado. Tente novamente.'
                );
            }
        }
        catch (error)
        {
            console.error('Erro na requisição Ajax:', error);

            exibirMensagemGeral(
                'Falha na comunicação com o servidor. Verifique sua conexão.'
            );
        }
        finally
        {
            // Restaura o botão de envio.
            if (submitBtn)
            {
                submitBtn.disabled = false;
                submitBtn.innerText = textoOriginal;
            }
        }
    });

    /*
     * Remove mensagens e estilos de erro anteriores.
     */
    function limparErros()
    {
        document.querySelectorAll('.rd-error-ajax').forEach(el => el.remove());

        document.querySelectorAll("[class~='!border-[#F2A058]']").forEach(el =>
        {
            el.classList.remove('!border-[#F2A058]');
        });

        const alertGeral = document.getElementById('alertaGeralAjax');

        if (alertGeral)
        {
            alertGeral.remove();
        }
    }

    /*
     * Exibe os erros retornados pela API nos campos correspondentes.
     */
    function exibirErros(erros)
    {
        for (const [campo, mensagem] of Object.entries(erros))
        {
            let inputElement =
                document.getElementById(campo) ||
                document.querySelector(`[name="${campo}"]`);

            // Mapeamentos para campos com seletores especiais ou arrays
            if (campo === 'nova_categoria')
            {
                inputElement =
                    document.getElementById('nova_categoria') ||
                    document.getElementById('categoria');
            }
            else if (campo === 'componentes')
            {
                inputElement = document.getElementById('multiSelectComponente');
            }
            else if (campo === 'codigos' || campo === 'codigos_descricao')
            {
                inputElement = document.getElementById('listaCodigos');
            }
            else if (campo.startsWith('codigos_descricao_'))
            {
                inputElement = document.getElementById(campo) || document.getElementById('listaCodigos');
            }
            else if (campo === 'extras' || campo === 'extras_descricao')
            {
                inputElement = document.getElementById('listaExtras');
            }
            else if (campo.startsWith('extras_descricao_'))
            {
                inputElement = document.getElementById(campo) || document.getElementById('listaExtras');
            }
            else if (campo === 'imagens')
            {
                inputElement = document.getElementById('listaImagens');
            }

            if (!inputElement)
            {
                console.warn(`Campo não encontrado para o erro: ${campo}`);
                continue;
            }

            const containerField =
                inputElement.closest('.rd-field') ||
                inputElement.parentElement;

            if (!containerField)
            {
                continue;
            }

            // Destaca o elemento com borda vermelha
            inputElement.classList.add('!border-[#F2A058]');

            // Cria e insere o texto do erro
            const errorElement = document.createElement('p');
            errorElement.className = 'rd-error rd-error-ajax mt-1 text-xs text-[#F2A058] font-bold';
            errorElement.textContent = mensagem;

            // Se for a descrição do código ou extra, insere logo abaixo do input
            if (campo.startsWith('codigos_descricao_') || campo.startsWith('extras_descricao_'))
            {
                inputElement.insertAdjacentElement('afterend', errorElement);
            }
            else
            {
                containerField.appendChild(errorElement);
            }
        }
    }

    /*
     * Rola suavemente a página até o primeiro campo com erro.
     */
    function rolarParaPrimeiroErro()
    {
        const primeiroErro = document.querySelector('.rd-error-ajax');

        if (primeiroErro)
        {
            primeiroErro.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    /*
     * Exibe mensagem de erro geral no topo do formulário.
     */
    function exibirMensagemGeral(mensagem)
    {
        const alertDiv = document.createElement('div');

        alertDiv.id = 'alertaGeralAjax';

        alertDiv.className =
            'mb-6 p-4 border-2 border-[#F2A058] bg-[#06141C] text-[#F2A058] text-xs font-bold font-["Orbitron"] tracking-wider';

        alertDiv.textContent = `AVISO: ${mensagem}`;

        form.insertAdjacentElement('afterbegin', alertDiv);

        alertDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});