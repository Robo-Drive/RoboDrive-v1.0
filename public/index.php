<?php

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use app\core\Router;

$router = new Router();

// Início e Autenticação
$router->get('/', 'HomeController@home');
$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');
$router->get('/cadastro', 'AutenticacaoController@cadastro');
$router->post('/logout', 'AutenticacaoController@logout');
$router->get('/recuperar-senha', 'AutenticacaoController@formularioRecuperarSenha');
$router->post('/recuperar-senha', 'AutenticacaoController@enviarLinkRecuperacao');
$router->get('/redefinir-senha', 'AutenticacaoController@formularioRedefinirSenha');
$router->post('/redefinir-senha', 'AutenticacaoController@atualizarSenhaRecuperada');

// Arquivos
$router->get('/arquivo', 'ArquivoController@visualizar');

// Usuário
$router->post('/usuario/ativar', 'UsuarioController@ativar');
$router->post('/usuario/comunicado', 'UsuarioController@comunicado');

// Projeto
$router->get('/projeto', 'ProjetoController@listarPublico');
$router->get('/projeto/listar', 'ProjetoController@listarPublico');
$router->get('/projeto/listar/admin', 'ProjetoController@listarTodos');
$router->get('/projeto/cadastro', 'ProjetoController@cadastrar');
$router->post('/projeto/salvar', 'ProjetoController@salvar');
$router->get('/projeto/perfil', 'ProjetoController@perfil');
$router->post('/projeto/editar', 'ProjetoController@editar');
$router->post('/projeto/atualizar', 'ProjetoController@atualizar');
$router->post('/projeto/desativar', 'ProjetoController@desativar');
$router->post('/projeto/adicionar-membro', 'ProjetoController@adicionarMembro');
$router->post('/projeto/remover-membro', 'ProjetoController@removerMembro');
$router->post('/projeto/alterar-papel', 'ProjetoController@alterarPapel');
$router->get('/projeto/entrar', 'ProjetoController@formularioEntrar');
$router->post('/projeto/entrar', 'ProjetoController@entrar');
$router->post('/projeto/aprovar-solicitacao', 'ProjetoController@aprovarSolicitacao');
$router->post('/projeto/rejeitar-solicitacao', 'ProjetoController@rejeitarSolicitacao');

// Equipe
$router->get('/equipe', 'EquipeController@listar');
$router->post('/equipe/alterar-papel', 'EquipeController@alterarPapel');
$router->post('/equipe/adicionar-membro', 'EquipeController@adicionarMembro');
$router->post('/equipe/remover-membro', 'EquipeController@removerMembro');
$router->get('/equipe/entrar', 'EquipeController@formularioEntrar');
$router->post('/equipe/entrar', 'EquipeController@entrar');
$router->post('/equipe/aprovar-solicitacao', 'EquipeController@aprovarSolicitacao');
$router->post('/equipe/rejeitar-solicitacao', 'EquipeController@rejeitarSolicitacao');

// Fórum
$router->get('/forum', 'ForumController@listar');
$router->post('/forum/perfil', 'ForumController@perfil');

// Componente
$router->get('/componente', 'ComponenteController@listar');

// Comentários
$router->post('/comentario/salvar', 'ComentarioController@salvar');
$router->post('/comentario/desativar', 'ComentarioController@desativar');

// Denúncias
$router->get('/denuncia', 'DenunciaController@listar');
$router->post('/denuncia/salvar', 'DenunciaController@salvar');
$router->post('/denuncia/desativar', 'DenunciaController@desativar');

// CRUDs principais (usuario, equipe, componente, forum)
$crudsPrincipais = ['usuario', 'equipe', 'componente', 'forum'];

foreach ($crudsPrincipais as $cp)
{
    $controller = ucfirst($cp) . 'Controller';
    $router->get('/' . $cp . '/listar', $controller . '@listar');
    $router->get('/' . $cp . '/cadastro', $controller . '@cadastrar');
    $router->post('/' . $cp . '/salvar', $controller . '@salvar');
    $router->get('/' . $cp . '/perfil', $controller . '@perfil');
    $router->post('/' . $cp . '/editar', $controller . '@editar');
    $router->post('/' . $cp . '/atualizar', $controller . '@atualizar');
    $router->post('/' . $cp . '/desativar', $controller . '@desativar');
}
$router->post('/usuario/excluir-categoria', 'UsuarioController@excluirCategoria');
$router->run();
