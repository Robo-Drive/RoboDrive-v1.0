<?php

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/Config.php';

use app\core\Router;

$router = new Router();

$router->get('/', 'ProjetoController@listarTodos');

// Projeto Routes
$router->get('/projetos', 'ProjetoController@listarTodos');
$router->get('/projetos/projeto', 'ProjetoController@verProjeto');
$router->get('/projetos/cadastrar', 'ProjetoController@criar');

$router->post('/projetos/salvar', 'ProjetoController@salvar');
$router->get('/projetos/editar', 'ProjetoController@editar');
$router->post('/projetos/atualizar', 'ProjetoController@atualizar');
$router->get('/projetos/excluir', 'ProjetoController@excluir');

$router->run();
