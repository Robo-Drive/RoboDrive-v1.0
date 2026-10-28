<?php

namespace app\controllers;

use app\core\Controller;
use app\helpers\ValidadorHelper;
use app\models\Componente;
use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;
use app\services\ComponenteService;
use app\services\CategoriaService;
use app\services\ProjetoService;
use app\services\CodigoService;
use app\services\ImagemService;
use app\services\UploadService;
use Exception;


class ProjetoController extends Controller
{
    private ProjetoService $service;
    private ProjetoRepositorySql $repositorySql;
    private ComponenteService $componenteService;
    private CategoriaService $categoriaService;
    private UsuarioRepositorySql $usuarioRepositorySql;

    public function __construct()
    {
        $this->service = new ProjetoService();
        $this->repositorySql = new ProjetoRepositorySql();
        $this->componenteService = new ComponenteService();
        $this->categoriaService = new CategoriaService();
        $this->usuarioRepositorySql = new UsuarioRepositorySql();
    }

    public function listar()
    {
        $this->loginRequired();
        $data['projetos'] = $this->repositorySql->listarPublico();
        $this->view("projeto/list",$data);
    }
    public function cadastrar()
    {
        $this->loginRequired();
        $componentes = $this->componenteService->listarTodos();
        $categorias = $this->categoriaService->listarTodos();
        $data["componentes"] = $componentes;
        $data["categorias"] = $categorias;
        $this->view("projeto/create",$data);
    }
    public function salvar()
    {
        $this->loginRequired();

        //print "<pre>";
        //print_r($_POST);
        //print_r($_FILES);
        //print "</pre>";
        
        $validador = new ValidadorHelper();
    
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',  trim($_POST["descricao"]));
        $validador->obrigatorio('visibilidade',  trim($_POST["visibilidade"]));
        $validador->tamanho('nome', trim($_POST["nome"]), 3,100);
        $validador->tamanho('descricao', trim($_POST["descricao"]), 3,2000);
        if(!is_numeric($_POST["categoria"]) && $_POST["categoria"] == "criar")
        {
            $validador->tamanho('categoria', trim($_POST["novaCategoria"]), 3,100);    
            $posts["categoria_nome"]  = trim(filter_input(INPUT_POST, 'novaCategoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $posts["categoria_id"] = $this->categoriaService->cadastro($posts)->getId();
        };
        if(is_numeric($_POST["categoria"]))
        {
            $posts["categoria_id"] = $_POST["categoria"];
        }
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]   = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["visibilidade"]  = trim(filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["codigos_descricao"] = filter_input(INPUT_POST,'codigos_descricao',FILTER_DEFAULT,FILTER_REQUIRE_ARRAY);
        $posts["codigos_descricao"] = array_map(fn($descricao) => trim($descricao),$posts["codigos_descricao"] ?? []);
        $componentes = $_POST['componentes'] ?? [];
        $componentesObj = array();
        $i = 0;
        foreach ($componentes as $componenteId => $dados)
        {
            $quantidade = (int) $dados['quantidade'];
            $componentesObj[$i] = new Componente();
            $componentesObj[$i]->setId($componenteId);
            $componentesObj[$i]->setQuantidade($quantidade);
            $i++;
        }
        //print "<pre>";
        //print_r($componentesObj);
        //print "</pre>";
        $posts["componentes"] = $componentesObj;
        if(isset($_FILES["imagens"]))
        {
            $posts["imagens"] = $_FILES["imagens"];
        }
        if(isset($_FILES["codigos"]))
        {
            $posts["codigos"] = $_FILES["codigos"];
        }
        print "<pre>";
        print_r($posts);
        //print_r($validador->getErros());
        print "</pre>";
        die;
        if($validador->temErros())
        {
            $data["projeto"] = $posts;
            $data["erros"] = $validador->getErros();
            die;
            $this->view('projeto/create',$data);
        }
        else
        {   $cadastrados = $this->service->salvarProjeto($posts);
            $posts["projeto"] = $cadastrados[0];
            $posts["projeto-versao"] = $cadastrados[1];
            if(count($cadastrados) == 2)
            {
                if($this->componenteService->associar($posts));
                {
                    if(!empty($posts["codigos"]))
                    {
                        (new CodigoService)->salvarCodigo($posts["codigos"],$posts["projeto"]);
                        
                    }
                    if(!empty($posts["imagens"]))
                    {
                        (new ImagemService)->salvarImagem($posts["imagens"],$posts["projeto"]);
                    }
                    $this->redirect(URL_BASE . '/projetos');
                }
            } 
            else
            {
                $data["projeto"] = $posts;
                $this->view('projeto/create',$data);
            }
        }
    }
    public function editar()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_POST["id"]);
        $data["projeto"] = $this->repositorySql->buscarId($projeto);
        if($data["projeto"]->getNome() != null)
        {
            $this->view("projeto/edit",$data);
        }
        else
        {
            $this->view("projeto/list");    
        }
    }
    public function atualizar()
    {
        $this->loginRequired();
        $validador = new ValidadorHelper();
        
        $validador->obrigatorio('id',   trim($_POST["id"]));
        $validador->obrigatorio('nome',   trim($_POST["nome"]));
        $validador->obrigatorio('descricao',  trim($_POST["descricao"]));
        $validador->obrigatorio('visibilidade',  trim($_POST["visibilidade"]));
        $validador->tamanho('nome', $_POST["nome"], 3,100);
        $posts["id"] = $_POST["id"];
        $posts["nome"]   = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["descricao"]  = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $posts["visibilidade"]  = trim(filter_input(INPUT_POST, 'visibilidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $projeto = Projeto::map([$posts])[0];
        if($validador->temErros())
        {
            $data["projeto"] = $posts;
            $data["erros"] = $validador->getErros();
            $this->view('usuario/edit',$data);
        }
        else
        {
            if ($this->service->editarProjeto($projeto))
            {
                $this->redirect(URL_BASE . '/projeto/listar');
            } 
            else
            {
                $data["projeto"] = $projeto;
                $this->view('projeto/edit',$data);
            }
        }
    }
    public function perfil()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_GET["id"]);
        $data["projeto"] = $this->repositorySql->buscarId($projeto);
        $componentes = $this->componenteService->buscarProjeto($projeto->getId());
        $data["projeto"]->setComponentes($componentes);
        $data["usuarios"] = $this->usuarioRepositorySql->buscarProjeto($projeto->getId());
        if($data["projeto"]->getNome() != null)
        {
            $this->view("projeto/perfil",$data);
        }
        else
        {
            $this->view("projeto/list");    
        }

    }
    public function excluir()
    {
        $this->loginRequired();
        $projeto = new Projeto();
        $projeto->setId($_POST["id"]);
        $this->repositorySql->deletar($projeto);
        $this->listar();
    }
    public function desativar()
    {
        $this->loginRequired();
        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/projeto');
    }
}