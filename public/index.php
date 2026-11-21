<?php

require_once __DIR__ . '/../app/core/Autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use app\core\Router;

$router = new Router();

/*
|--------------------------------------------------------------------------
| INÍCIO
|--------------------------------------------------------------------------
*/

$router->get('/', 'HomeController@home');


/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

$router->get('/login', 'AutenticacaoController@login');
$router->post('/logar', 'AutenticacaoController@logar');
$router->get('/cadastro', 'AutenticacaoController@cadastro');
$router->post('/logout', 'AutenticacaoController@logout');

$router->get('/recuperar-senha', 'AutenticacaoController@formularioRecuperarSenha');
$router->post('/recuperar-senha', 'AutenticacaoController@enviarLinkRecuperacao');

$router->get('/redefinir-senha', 'AutenticacaoController@formularioRedefinirSenha');
$router->post('/redefinir-senha', 'AutenticacaoController@atualizarSenhaRecuperada');


/*
|--------------------------------------------------------------------------
| ARQUIVOS
|--------------------------------------------------------------------------
*/

$router->get('/arquivo', 'ArquivoController@visualizar');


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

// Rotas do próprio usuário
$router->get('/usuario/cadastro', 'UsuarioController@cadastrar');
$router->get('/usuario/perfil', 'UsuarioController@perfil');
$router->post('/usuario/desativar', 'UsuarioController@desativar');
$router->post('/usuario/salvar', 'UsuarioController@salvar');
$router->post('/usuario/editar','UsuarioController@editar');
$router->post('/usuario/atualizar','UsuarioController@atualizar');
$router->post('/usuario/ativar', 'UsuarioController@ativar');
$router->post('/usuario/comunicado', 'UsuarioController@comunicado');
$router->post('/usuario/excluir-categoria', 'UsuarioController@excluirCategoria');


/*
|--------------------------------------------------------------------------
| PROJETOS
|--------------------------------------------------------------------------
*/

// Rotas públicas
$router->get('/projeto/listar', 'ProjetoController@listarPublico');
$router->get('/projeto/cadastro', 'ProjetoController@cadastrar');
$router->get('/projeto/perfil', 'ProjetoController@perfil');
$router->get('/projeto/entrar', 'ProjetoController@formularioEntrar');

$router->post('/projeto/salvar', 'ProjetoController@salvar');
$router->post('/projeto/editar', 'ProjetoController@editar');
$router->post('/projeto/atualizar', 'ProjetoController@atualizar');
$router->post('/projeto/desativar', 'ProjetoController@desativar');
$router->post('/projeto/ativar', 'ProjetoController@ativar');
$router->post('/projeto/entrar', 'ProjetoController@entrar');

// Gerenciamento de membros
$router->post('/projeto/adicionar-membro', 'ProjetoController@adicionarMembro');
$router->post('/projeto/remover-membro', 'ProjetoController@removerMembro');
$router->post('/projeto/alterar-papel', 'ProjetoController@alterarPapel');

// Solicitações
$router->post('/projeto/aprovar-solicitacao', 'ProjetoController@aprovarSolicitacao');
$router->post('/projeto/rejeitar-solicitacao', 'ProjetoController@rejeitarSolicitacao');


/*
|--------------------------------------------------------------------------
| EQUIPES
|--------------------------------------------------------------------------
*/

// Rotas públicas
$router->get('/equipe/cadastro', 'EquipeController@cadastrar');
$router->post('/equipe/salvar', 'EquipeController@salvar');
$router->get('/equipe/perfil', 'EquipeController@perfil');
$router->post('/equipe/editar', 'EquipeController@editar');
$router->post('/equipe/atualizar', 'EquipeController@atualizar');
$router->post('/equipe/desativar', 'EquipeController@desativar');
$router->post('/equipe/ativar', 'EquipeController@ativar');
$router->get('/equipe/entrar', 'EquipeController@formularioEntrar');
$router->post('/equipe/entrar', 'EquipeController@entrar');

// Gerenciamento de membros
$router->post('/equipe/alterar-papel', 'EquipeController@alterarPapel');
$router->post('/equipe/adicionar-membro', 'EquipeController@adicionarMembro');
$router->post('/equipe/remover-membro', 'EquipeController@removerMembro');

// Solicitações
$router->post('/equipe/aprovar-solicitacao', 'EquipeController@aprovarSolicitacao');
$router->post('/equipe/rejeitar-solicitacao', 'EquipeController@rejeitarSolicitacao');


/*
|--------------------------------------------------------------------------
| FÓRUM
|--------------------------------------------------------------------------
*/

$router->get('/forum/cadastro', 'ForumController@cadastrar');
$router->post('/forum/salvar', 'ForumController@salvar');
$router->post('/forum/editar', 'ForumController@editar');
$router->post('/forum/atualizar', 'ForumController@atualizar');
$router->get('/forum/listar', 'ForumController@listarPublico');
$router->get('/forum/perfil', 'ForumController@perfil');
$router->post('/forum/desativar', 'ForumController@desativar');
$router->post('/forum/ativar', 'ForumController@ativar');

/*
|--------------------------------------------------------------------------
| COMPONENTES
|--------------------------------------------------------------------------
*/

$router->get('/componente/cadastro', 'ComponenteController@cadastrar');
$router->post('/componente/salvar', 'ComponenteController@salvar');
$router->post('/componente/editar', 'ComponenteController@editar');
$router->post('/componente/atualizar', 'ComponenteController@atualizar');
$router->post('/componente/desativar', 'ComponenteController@desativar');
$router->post('/componente/ativar', 'ComponenteController@ativar');
$router->get('/componente/listar', 'ComponenteController@listar');
$router->get('/componente/perfil', 'ComponenteController@perfil');


/*
|--------------------------------------------------------------------------
| COMENTÁRIOS
|--------------------------------------------------------------------------
*/

$router->post('/comentario/salvar', 'ComentarioController@salvar');
$router->post('/comentario/desativar', 'ComentarioController@desativar');


/*
|--------------------------------------------------------------------------
| DENÚNCIAS
|--------------------------------------------------------------------------
*/

$router->post('/denuncia/salvar', 'DenunciaController@salvar');
$router->post('/denuncia/desativar', 'DenunciaController@desativar');


/*
|--------------------------------------------------------------------------
| ADMINISTRAÇÃO
|--------------------------------------------------------------------------
*/

$router->get('/admin/denuncia/listar', 'DenunciaController@listar');
$router->get('/admin/usuario/listar', 'UsuarioController@listar');
$router->get('/admin/equipe/listar', 'EquipeController@listar');
$router->get('/admin/projeto/listar', 'ProjetoController@listarAdmin');
$router->get('/admin/forum/listar', 'ForumController@listarAdmin');
$router->get('/admin/componente/listar', 'ComponenteController@listarAdmin');


/*
|--------------------------------------------------------------------------
| EXECUÇÃO DO ROUTER
|--------------------------------------------------------------------------
*/

$router->run();
