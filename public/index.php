<?php 

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/Config.php';

use app\core\Router;

$router = new Router();

$router->get('/', 'UsuarioController@login');

//Rotas do usuario
$router->get('/usuario/listar', 'UsuarioController@listar');
$router->get('/usuario/cadastrar', 'UsuarioController@cadastrar');
$router->post('/usuario/salvar', 'UsuarioController@salvar');

$router->get('/usuario/editar', 'UsuarioController@editar');
$router->post('/usuario/atualizar', 'UsuarioController@atualizar');
$router->get('/usuario/excluir', 'UsuarioController@excluir');

$router->run();
