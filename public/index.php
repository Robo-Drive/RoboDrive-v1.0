<?php 


require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use app\core\Router;

$router = new Router();

$router->get('/', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');

//Rotas do usuario
$router->get('/usuario/listar', 'UsuarioController@listar');
$router->get('/usuario/cadastrar', 'UsuarioController@cadastrar');
$router->post('/usuario/salvar', 'UsuarioController@salvar');
$router->post('/usuario/perfil', 'UsuarioController@perfil');

$router->post('/usuario/editar', 'UsuarioController@editar');
$router->post('/usuario/atualizar', 'UsuarioController@atualizar');
$router->post('/usuario/excluir', 'UsuarioController@excluir');

$router->run();
