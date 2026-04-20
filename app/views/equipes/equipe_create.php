<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Equipe - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin-top: 20px;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_BASE ?>"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_BASE ?>/equipes">Equipes</a>
                    </li>
                    <?php if (isset($_SESSION['user_logado'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-light">
                                <?= htmlspecialchars($_SESSION['user_logado']->getNome()) ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL_BASE ?>/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL_BASE ?>/login">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <a href="<?= URL_BASE ?>/equipes" class="btn btn-secondary mb-3">← Voltar</a>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Criar Nova Equipe</h3>
            </div>
            <div class="card-body">
                <?php if (isset($erros) && !empty($erros)): ?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Erros encontrados:</h4>
                        <ul class="mb-0">
                            <?php foreach ($erros as $campo => $mensagem): ?>
                                <li><?= htmlspecialchars($mensagem) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= URL_BASE ?>/equipes/salvar">
                    <div class="mb-3">
                        <label for="nome_equipe" class="form-label">Nome da Equipe *</label>
                        <input 
                            type="text" 
                            class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>" 
                            id="nome_equipe" 
                            name="nome_equipe" 
                            value="<?= isset($nome_equipe) ? htmlspecialchars($nome_equipe) : '' ?>"
                            placeholder="Digite o nome da equipe"
                            required
                            minlength="3"
                            maxlength="100"
                        >
                        <div class="form-text">Mínimo 3 caracteres, máximo 100</div>
                        <?php if (isset($erros['nome'])): ?>
                            <div class="invalid-feedback" style="display: block;">
                                <?= htmlspecialchars($erros['nome']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha *</label>
                        <input 
                            type="password" 
                            class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>" 
                            id="senha" 
                            name="senha" 
                            placeholder="Digite a senha da equipe"
                            required
                            minlength="6"
                        >
                        <div class="form-text">Mínimo 6 caracteres</div>
                        <?php if (isset($erros['senha'])): ?>
                            <div class="invalid-feedback" style="display: block;">
                                <?= htmlspecialchars($erros['senha']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                        <a href="<?= URL_BASE ?>/equipes" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Criar Equipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
