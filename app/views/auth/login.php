<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
        body { min-height: 100vh; background: linear-gradient(135deg, #1e3a8a, #2563eb, #60a5fa); display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-container { width: 100%; max-width: 400px; background: #ffffff; border-radius: 16px; padding: 35px 30px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18); }
        .login-container h1 { text-align: center; margin-bottom: 10px; color: #1f2937; font-size: 28px; }
        .login-container p { text-align: center; margin-bottom: 25px; color: #6b7280; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 8px; color: #374151; font-weight: bold; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; outline: none; transition: 0.2s ease-in-out; }
        .form-group input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        .btn-login { width: 100%; padding: 13px; border: none; border-radius: 10px; background: #2563eb; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.2s ease-in-out; }
        .btn-login:hover { background: #1d4ed8; }
        .extra-links { margin-top: 18px; text-align: center; font-size: 14px; }
        .extra-links a { color: #2563eb; text-decoration: none; font-weight: bold; }
        .extra-links a:hover { text-decoration: underline; }
        .logo-area { text-align: center; margin-bottom: 20px; }
        .logo-area .icon { width: 65px; height: 65px; margin: 0 auto 10px; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #2563eb; font-weight: bold; }
        .alert { margin-bottom: 16px; padding: 10px 12px; border-radius: 8px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo-area">
            <div class="icon">🔐</div>
        </div>

        <h1>Bem-vindo</h1>
        <p>Faça login para acessar o sistema</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/logar" method="post">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="extra-links">
            <p>Não tem conta? <a href="<?= URL_BASE ?>/cadastro">Cadastre-se</a></p>
        </div>
    </div>

</body>
</html>
