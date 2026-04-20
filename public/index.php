<?php 

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/Config.php';

use app\core\Router;

$router = new Router();

// ========== ROTAS DE AUTENTICAÇÃO ==========
$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');
$router->get('/logout', 'AutenticacaoController@logout');
$router->get('/cadastro', 'AutenticacaoController@cadastro');
$router->post('/registrar', 'AutenticacaoController@registrar');

// ========== ROTAS DE HOME ==========
$router->get('/', 'HomeController@index');

// ========== ROTAS DE USUÁRIOS ==========
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users/save', 'UserController@save');
$router->get('/users/show', 'UserController@show');
$router->get('/users/edit', 'UserController@edit');
$router->post('/users/update', 'UserController@update');
$router->get('/users/delete', 'UserController@delete');

// ========== ROTAS DE EQUIPES ==========
$router->get('/equipes', 'EquipeController@listAll');
$router->get('/equipes/show', 'EquipeController@show');
$router->get('/equipes/criar', 'EquipeController@create');
$router->post('/equipes/salvar', 'EquipeController@save');
$router->get('/equipes/editar', 'EquipeController@edit');
$router->post('/equipes/atualizar', 'EquipeController@update');
$router->get('/equipes/deletar', 'EquipeController@delete');

$router->run();        