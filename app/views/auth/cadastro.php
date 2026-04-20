<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { min-height: 100vh; background: linear-gradient(135deg, #1e3a8a, #2563eb, #60a5fa); display: flex; align-items: center; justify-content: center; padding: 20px; }
        .register-container { width: 100%; max-width: 430px; background: #ffffff; border-radius: 16px; padding: 35px 30px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18); }
        .register-container h1 { text-align: center; margin-bottom: 10px; color: #1f2937; font-size: 28px; }
        .register-container p { text-align: center; margin-bottom: 25px; color: #6b7280; font-size: 14px; }
        .logo-area { text-align: center; margin-bottom: 20px; }
        .logo-area .icon { width: 65px; height: 65px; margin: 0 auto 10px; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #2563eb; font-weight: bold; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: bold; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; outline: none; transition: 0.2s ease-in-out; background: #fff; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .btn-register { width: 100%; padding: 13px; border: none; border-radius: 10px; background: #2563eb; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.2s ease-in-out; }
        .btn-register:hover { background: #1d4ed8; }
        .extra-links { margin-top: 18px; text-align: center; font-size: 14px; }
        .extra-links a { color: #2563eb; text-decoration: none; font-weight: bold; }
        .extra-links a:hover { text-decoration: underline; }
        .alert { margin-bottom: 16px; padding: 10px 12px; border-radius: 8px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .field-error { margin-top: 6px; color: #991b1b; font-size: 12px; }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="logo-area">
            <div class="icon">👤</div>
        </div>

        <h1>Criar conta</h1>
        <p>Preencha os dados para se cadastrar no sistema</p>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/registrar" method="post">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" value="<?= htmlspecialchars($nome ?? '') ?>" required>
                <?php if (!empty($erros['nome'])): ?><div class="field-error"><?= htmlspecialchars($erros['nome']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
                <?php if (!empty($erros['email'])): ?><div class="field-error"><?= htmlspecialchars($erros['email']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                <?php if (!empty($erros['senha'])): ?><div class="field-error"><?= htmlspecialchars($erros['senha']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="perfil">Perfil</label>
                <select id="perfil" name="perfil" required>
                    <option value="">Selecione o perfil</option>
                    <option value="aluno" <?= (($perfil ?? '') === 'aluno') ? 'selected' : '' ?>>Aluno</option>
                    <option value="professor" <?= (($perfil ?? '') === 'professor') ? 'selected' : '' ?>>Professor</option>
                </select>
                <?php if (!empty($erros['perfil'])): ?><div class="field-error"><?= htmlspecialchars($erros['perfil']) ?></div><?php endif; ?>
            </div>

            <button type="submit" class="btn-register">Cadastrar</button>
        </form>

        <div class="extra-links">
            <p>Já tem conta? <a href="<?= URL_BASE ?>/login">Entrar</a></p>
        </div>
    </div>

</body>
</html>
