<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Equipe - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
                <h3 class="mb-0"><?= htmlspecialchars($equipe['nome_equipe']) ?></h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informações</h5>
                        <p><strong>ID:</strong> <?= $equipe['id'] ?></p>
                        <p><strong>Professor:</strong> <?= htmlspecialchars($equipe['professor_nome']) ?></p>
                        <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($equipe['criado_em'])) ?></p>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_logado']) && 
                    $_SESSION['user_logado']->getId() == $equipe['professor_id']): ?>
                    <div class="mt-4">
                        <h5>Ações</h5>
                        <a href="<?= URL_BASE ?>/equipes/editar?id=<?= $equipe['id'] ?>" class="btn btn-warning">
                            Editar
                        </a>
                        <a href="<?= URL_BASE ?>/equipes/deletar?id=<?= $equipe['id'] ?>" class="btn btn-danger" 
                            onclick="return confirm('Tem certeza que deseja deletar?')">
                            Deletar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
