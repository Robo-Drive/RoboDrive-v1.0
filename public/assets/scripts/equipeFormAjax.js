document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");

    forms.forEach(function(form) {
        const action = (form.getAttribute("action") || "").trim();
        const method = (form.getAttribute("method") || "POST").toUpperCase();

        // 1. Não interceptar formulários GET (filtros, pesquisas)
        // 2. Não interceptar formulários marcados explicitamente com data-native="true"
        // 3. Não interceptar rotas que apenas abrem telas de edição
        if (
            method === "GET" ||
            form.dataset.native === "true" ||
            action.endsWith("/editar")
        ) {
            return;
        }

        form.addEventListener("submit", async function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector("button[type='submit']");
            const textoOriginal = submitBtn ? submitBtn.innerText : "";

            try {
                // Limpa mensagens e bordas de erro anteriores
                limparErros(form);

                // Desabilita o botão durante o processamento
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = "Processando...";
                }

                const formData = new FormData(form);
                const url = form.action || window.location.href;

                const response = await fetch(url, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                });

                let data = null;
                const contentType = response.headers.get("content-type") || "";

                if (contentType.includes("application/json")) {
                    data = await response.json();
                } else {
                    const texto = await response.text();
                    // Tenta extrair JSON se houver algum warning ou espaço antes
                    try {
                        const jsonMatch = texto.match(/\{[\s\S]*\}/);
                        if (jsonMatch) {
                            data = JSON.parse(jsonMatch[0]);
                        }
                    } catch (_) {}

                    if (!data) {
                        if (response.ok) {
                            window.location.reload();
                            return;
                        }
                        console.error("Resposta não-JSON do servidor:", texto);
                        throw new Error(texto || "Resposta inválida do servidor.");
                    }
                }

                // Sucesso (HTTP 200 / 201)
                if (response.ok && data.sucesso !== false) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    if (data.mensagem) {
                        rdAviso(data.mensagem, "sucesso", true);
                    }

                    window.location.reload();
                    return;
                }

                // Erros de validação (HTTP 422)
                if (response.status === 422 && data.erros) {
                    exibirErrosNosCampos(form, data.erros);
                    rolarParaPrimeiroErro(form);
                    return;
                }

                // Outros erros (HTTP 401, 403, 404, 500 etc.)
                exibirErroGeral(
                    form,
                    data.mensagem || "Não foi possível concluir a operação."
                );

            } catch (err) {
                console.error("Erro na requisição Ajax:", err);

                // Se o servidor retornou uma mensagem detalhada de erro ou warning, exibe
                const detalhe = err.message && !err.message.includes("Resposta inválida") ? err.message : null;
                exibirErroGeral(
                    form,
                    detalhe ? ("Erro do servidor: " + detalhe.substring(0, 150)) : "Falha na comunicação com o servidor. Tente novamente."
                );
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = textoOriginal;
                }
            }
        });
    });

    // Remove erros anteriores do formulário
    function limparErros(form) {
        form.querySelectorAll(".rd-ajax-error, .rd-error-ajax, .rd-error, .erro-validacao-php").forEach(function(el) {
            el.remove();
        });

        form.querySelectorAll("[class~='!border-[#F2A058]']").forEach(function(el) {
            el.classList.remove("!border-[#F2A058]");
        });

        const alertGeral = form.querySelector(".alerta-geral-ajax");
        if (alertGeral) {
            alertGeral.remove();
        }
    }

    // Exibe erros nos respectivos campos
    function exibirErrosNosCampos(form, erros) {
        for (const [campo, mensagem] of Object.entries(erros)) {
            let inputElement =
                form.querySelector(`[name="${campo}"]`) ||
                form.querySelector(`[name="${campo}[]"]`) ||
                form.querySelector(`#${campo}`) ||
                document.getElementById(campo);

            if (campo === "nova_categoria") {
                inputElement =
                    document.getElementById("novaCategoria") ||
                    document.getElementById("categoria");
            } else if (campo === "componentes") {
                inputElement = document.getElementById("multiSelectComponente");
            } else if (campo === "codigos" || campo === "codigos_descricao") {
                inputElement = document.getElementById("listaCodigos");
            } else if (campo.startsWith("codigos_descricao_")) {
                inputElement =
                    document.getElementById(campo) ||
                    document.getElementById("listaCodigos");
            } else if (campo === "extras" || campo === "extras_descricao") {
                inputElement = document.getElementById("listaExtras");
            } else if (campo.startsWith("extras_descricao_")) {
                inputElement =
                    document.getElementById(campo) ||
                    document.getElementById("listaExtras");
            } else if (campo === "imagens") {
                inputElement = document.getElementById("listaImagens");
            }

            const errorElement = document.createElement("p");
            errorElement.className =
                "rd-ajax-error rd-error text-xs text-[#F2A058] font-bold mt-1.5";
            errorElement.textContent = mensagem;

            if (inputElement) {
                inputElement.classList.add("!border-[#F2A058]");

                const container =
                    inputElement.closest(".rd-field") ||
                    inputElement.closest(".password-container")?.parentElement ||
                    inputElement.parentElement;

                if (container) {
                    container.appendChild(errorElement);
                } else {
                    inputElement.insertAdjacentElement("afterend", errorElement);
                }
            } else {
                exibirErroGeral(form, `${campo}: ${mensagem}`);
            }
        }
    }

    // Rola a página até o primeiro campo com erro
    function rolarParaPrimeiroErro(form) {
        const primeiroErro = form.querySelector(
            ".rd-ajax-error, .rd-error-ajax, [class~='!border-[#F2A058]']"
        );

        if (primeiroErro) {
            primeiroErro.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    }

    // Exibe mensagens gerais de erro
    function exibirErroGeral(form, mensagem) {
        let alertDiv = form.querySelector(".alerta-geral-ajax");

        if (!alertDiv) {
            alertDiv = document.createElement("div");
            alertDiv.className =
                "alerta-geral-ajax mb-5 p-3.5 border-2 border-[#F2A058] bg-[#06141C] text-[#F2A058] text-xs font-bold font-['Orbitron'] tracking-wider";
            form.insertAdjacentElement("afterbegin", alertDiv);
        }

        alertDiv.textContent = `AVISO: ${mensagem}`;
        alertDiv.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }
});
