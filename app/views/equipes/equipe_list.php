<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipes - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin-top: 20px;
        }
        .btn-criar {
            margin-bottom: 20px;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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
        <h1 class="mb-4">Equipes</h1>

        <?php if (isset($_SESSION['user_logado']) && $_SESSION['user_logado']->getPerfil() === 'professor'): ?>
            <div class="btn-criar">
                <a href="<?= URL_BASE ?>/equipes/criar" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Criar Equipe
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($equipes)): ?>
            <div class="alert alert-info" role="alert">
                Nenhuma equipe cadastrada no momento.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($equipes as $equipe): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($equipe['nome_equipe']) ?></h5>
                                <p class="card-text text-muted">
                                    <small>Professor: <?= htmlspecialchars($equipe['professor_nome']) ?></small>
                                </p>
                                <p class="card-text text-muted">
                                    <small>Criado em: <?= date('d/m/Y', strtotime($equipe['criado_em'])) ?></small>
                                </p>

                                <a href="<?= URL_BASE ?>/equipes/show?id=<?= $equipe['id'] ?>" class="btn btn-sm btn-info">
                                    Ver Detalhes
                                </a>

                                <?php if (isset($_SESSION['user_logado']) && 
                                    $_SESSION['user_logado']->getId() == $equipe['professor_id']): ?>
                                    <a href="<?= URL_BASE ?>/equipes/editar?id=<?= $equipe['id'] ?>" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>
                                    <a href="<?= URL_BASE ?>/equipes/deletar?id=<?= $equipe['id'] ?>" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Tem certeza que deseja deletar?')">
                                        Deletar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
