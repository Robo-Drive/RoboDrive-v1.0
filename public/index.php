<?php 


require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use app\core\Router;

$router = new Router();

$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');
$router->get('/cadastro', 'AutenticacaoController@cadastro');
$router->post('/logout', 'AutenticacaoController@logout');
$router->get('/arquivo', 'ArquivoController@visualizar');
$router->get('/', 'HomeController@home');

$router->post('/projeto/adicionar-membro', 'ProjetoController@adicionarMembro');
$router->post('/projeto/remover-membro', 'ProjetoController@removerMembro');

$router->get('/denuncia', 'DenunciaController@listar');
$router->post('/denuncia/salvar', 'DenunciaController@salvar');
$router->post('/denuncia/excluir', 'DenunciaController@excluir');

$router->get('/recuperar-senha', 'AutenticacaoController@formularioRecuperarSenha');
$router->post('/recuperar-senha', 'AutenticacaoController@enviarLinkRecuperacao');
$router->get('/redefinir-senha', 'AutenticacaoController@formularioRedefinirSenha');
$router->post('/redefinir-senha', 'AutenticacaoController@atualizarSenhaRecuperada');
$router->post('/usuario/ativar', 'UsuarioController@ativar');

// Alteração de cargo/papel de membros
$router->post('/projeto/alterar-papel', 'ProjetoController@alterarPapel');
$router->post('/equipe/alterar-papel', 'EquipeController@alterarPapel');

$router->post('/usuario/comunicado', 'UsuarioController@comunicado');
$router->post('/equipe/adicionar-membro', 'EquipeController@adicionarMembro');
$router->post('/equipe/remover-membro', 'EquipeController@removerMembro');
$router->post('/forum/perfil', 'ForumController@perfil');

// Entrar em equipe / projeto com Hash e Senha
$router->get('/equipe/entrar', 'EquipeController@formularioEntrar');
$router->post('/equipe/entrar', 'EquipeController@entrar');
$router->post('/equipe/aprovar-solicitacao', 'EquipeController@aprovarSolicitacao');
$router->post('/equipe/rejeitar-solicitacao', 'EquipeController@rejeitarSolicitacao');

$router->get('/projeto/entrar', 'ProjetoController@formularioEntrar');
$router->post('/projeto/entrar', 'ProjetoController@entrar');
$router->post('/projeto/aprovar-solicitacao', 'ProjetoController@aprovarSolicitacao');
$router->post('/projeto/rejeitar-solicitacao', 'ProjetoController@rejeitarSolicitacao');

$crudsPrincipais = ["usuario","equipe","projeto","componente","forum"];

foreach($crudsPrincipais as $cp)
{
    $router->get('/'.$cp, ucfirst($cp).'Controller@listar');
    $router->get('/'.$cp.'/cadastro', ucfirst($cp).'Controller@cadastrar');
    $router->post('/'.$cp.'/salvar', ucfirst($cp).'Controller@salvar');
    $router->get('/'.$cp.'/perfil', ucfirst($cp).'Controller@perfil');

    $router->post('/'.$cp.'/editar', ucfirst($cp).'Controller@editar');
    $router->post('/'.$cp.'/atualizar', ucfirst($cp).'Controller@atualizar');
    $router->post('/'.$cp.'/excluir', ucfirst($cp).'Controller@excluir');
    $router->post('/'.$cp.'/desativar', ucfirst($cp).'Controller@desativar');
}

$router->run();
