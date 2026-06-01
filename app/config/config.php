<?php

//Configuração do ambiente
define('DEV_ENVIRONMENT', true);
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}


if (DEV_ENVIRONMENT == true)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

//Configuração do Sistema
define('APP_NAME', 'RoboDrive');
define('URL_BASE', 'http://127.0.0.1:8080');
define('CSS_URL_BASE', 'http://127.0.0.1:8080/assets/css');
define('JS_URL_BASE', 'http://127.0.0.1:8080/assets/scripts');
define('IMG_URL_BASE', 'http://127.0.0.1:8080/assets/images');
define('STORE_PATH', __DIR__.'/../../store');

//Configurações do Banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'robo_drive');
define('DB_USER', getenv("DB_USER"));
define('DB_PASS', getenv("DB_PASS"));

//Configurações de gmail
