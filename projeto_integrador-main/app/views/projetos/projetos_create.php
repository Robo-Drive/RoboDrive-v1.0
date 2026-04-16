<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Projeto Integrador • Novo Projeto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

    <div class="container">



        <div class="d-flex mt-5">

            <!-- CONTEÚDO -->
            <main class="flex-fill content">

                <!-- TÍTULO + VOLTAR -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Novo Projeto</h2>
                    <a href="<?= URL_BASE ?>/projetos" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>

                <!-- CARD COM FORMULÁRIO -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <form action="<?= URL_BASE ?>/projetos/salvar" method="post">

                            <div class="row g-3">
                                <!-- Nome -->
                                <div class="col-md-6">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?= $projeto['nome'] ?? '' ?>">
                                    <?php if (isset($erros['nome'])): ?>
                                        <div class="text-danger">
                                            <?= $erros['nome'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Visibilidade -->
                                <div class="col-md-6">
                                    <label for="visibilidade" class="form-label">Visibilidade</label>
                                    <select class="form-select" id="visibilidade" name="visibilidade">
                                        <option value="privado" <?= (isset($projeto['visibilidade']) && $projeto['visibilidade'] == 'privado') ? 'selected' : '' ?>>Privado</option>
                                        <option value="equipe" <?= (isset($projeto['visibilidade']) && $projeto['visibilidade'] == 'equipe') ? 'selected' : '' ?>>Equipe</option>
                                        <option value="publico" <?= (isset($projeto['visibilidade']) && $projeto['visibilidade'] == 'publico') ? 'selected' : '' ?>>Público</option>
                                    </select>

                                </div>
                            </div>

                            <!-- Botão Salvar -->
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle"></i> Salvar
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </main>
        </div>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>