<?php
//Configuração do ambiente
define('DEV_ENVIRONMENT', true);
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}


if (DEV_ENVIRONMENT)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

//Configuração do Sistema
define('APP_NAME', 'RoboDrive');
define('URL_BASE', 'http://localhost:8080');
define('CSS_URL_BASE', 'http://localhost:8080/assets/css');
define('JS_URL_BASE', 'http://localhost:8080/assets/scripts');
define('IMG_URL_BASE', 'http://localhost:8080/assets/images');
define('STORE_PATH', __DIR__.'/../../store');

function texto(mixed $valor): string
{
    return htmlspecialchars(html_entity_decode((string)($valor ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES, 'UTF-8');
}

function js_url(string $arquivo): string
{
    return JS_URL_BASE . '/' . $arquivo . '?v=' . (int)@filemtime(__DIR__ . '/../../public/assets/scripts/' . $arquivo);
}

function css_url(string $arquivo): string
{
    return CSS_URL_BASE . '/' . $arquivo . '?v=' . (int)@filemtime(__DIR__ . '/../../public/assets/css/' . $arquivo);
}

//Configurações do Banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'robo_drive');
define('DB_USER', getenv("DB_USER"));
define('DB_PASS', getenv("DB_PASS"));

// Configurações de envio de e-mail
define('MAIL_USER', getenv('PHP_MAILER_TCC_EMAIL'));
define('MAIL_PASS', getenv('PHP_MAILER_TCC_PASS'));
define('MAIL_NAME', 'RoboDrive');

