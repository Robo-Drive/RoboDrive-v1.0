<?php 


require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use app\core\Router;

$router = new Router();

$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');
$router->get('/cadastro', 'AutenticacaoController@cadastro');
$router->post('/logout', 'AutenticacaoController@logout');
$router->get('/', 'HomeController@home');

$crudsPrincipais = ["usuario","equipe","projeto","componente","forum"];

foreach($crudsPrincipais as $cp)
{
    $router->get('/'.$cp, ucfirst($cp).'Controller@listar');
    $router->get('/'.$cp.'/cadastrar', ucfirst($cp).'Controller@cadastrar');
    $router->post('/'.$cp.'/salvar', ucfirst($cp).'Controller@salvar');
    $router->get('/'.$cp.'/perfil', ucfirst($cp).'Controller@perfil');

    $router->post('/'.$cp.'/editar', ucfirst($cp).'Controller@editar');
    $router->post('/'.$cp.'/atualizar', ucfirst($cp).'Controller@atualizar');
    $router->post('/'.$cp.'/excluir', ucfirst($cp).'Controller@excluir');
}

$router->run();
