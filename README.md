# RoboDrive

Plataforma para cadastro e compartilhamento de projetos, equipes, componentes e postagens de robótica.

## O que você precisa

- **PHP 8.0 ou superior**
- **MySQL** ou **MariaDB** rodando na sua máquina
- Extensões do PHP: `pdo_mysql`, `mbstring`, `fileinfo` e `openssl`

## Passo a passo

### 1. Baixe o projeto

```bash
git clone <url-do-repositorio>
cd RoboDrive-v1.0
```

### 2. Informe seu usuário e senha do banco

Use um usuário do MySQL/MariaDB que possa criar bancos (por exemplo, o `root`). Digite estes comandos no terminal onde você vai iniciar o sistema:

```bash
export DB_USER=seu_usuario
export DB_PASS='sua_senha'
```

### 3. (Opcional) Configure o envio de e-mails

Para recuperação de senha, comunicados e avisos de desativação funcionarem, o sistema precisa do e-mail e da senha de app da equipe.

Envie um e-mail para **robodriveifpr@gmail.com** pedindo essas credenciais. Quando receber, informe no mesmo terminal:

```bash
export PHP_MAILER_TCC_EMAIL='email-recebido'
export PHP_MAILER_TCC_PASS='senha-de-app-recebida'
```

Sem essas duas linhas, o sistema funciona normalmente, mas nenhum e-mail é enviado.

### 4. Inicie o servidor

Na pasta do projeto, no mesmo terminal dos passos anteriores:

```bash
cd public/
php -S localhost:8080
```

### 5. Crie o banco

Abra [http://localhost:8080/login](http://localhost:8080/login) e **recarregue a página**. Na primeira vez, o sistema cria sozinho o banco `robo_drive` e todas as tabelas.

Depois disso, crie sua conta pelo cadastro e pronto, o sistema está funcionando.

### 6. Entre como administrador

usuario = robodriveifpr@gmail.com
senha = robodriveadmin


## Problemas comuns

- **"Não foi possível acessar o banco"**: confira se `DB_USER` e `DB_PASS` estão certos e se o MySQL/MariaDB está rodando. Lembre-se de exportar as variáveis no mesmo terminal antes de iniciar o servidor.
- **Página em branco ou erro de função inexistente**: a versão do PHP é anterior à 8.0. Confira com `php -v`.
- **Imagens não sobem**: a pasta `store/` precisa de permissão de escrita.
- **Porta**: o sistema usa a porta `8080`, fixa em `URL_BASE` no arquivo `app/config/config.php`. Se usar outra porta, altere esse valor.
