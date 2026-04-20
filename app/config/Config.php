<?php
//Configurção do ambiente
define('DEV_ENVIROMENT', true);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if(DEV_ENVIROMENT){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

//Cnfiguração do sistema
define('APP_NAME', 'CRUD_BASE');
define("URL_BASE", "http://localhost:8080");

//Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'CRUD_BASE');
define('DB_USER', 'root');
define('DB_PASS', '@petrus24');