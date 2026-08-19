<?php

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;

use app\services\CodigoService;
use app\services\ImagemService;
use app\services\UploadService;
use Exception;

class ProjetoService
{

    private CodigoService $codigoService;
    private ImagemService $imagemService;
    private UploadService $imagensUploadService;
    private UploadService $codigosUploadService;
    private ProjetoRepositorySql $repositorySql;

    
    public function __construct()
    {
        $this->repositorySql = new ProjetoRepositorySql();
        $this->imagemService = new ImagemService();
        $this->codigoService = new CodigoService();

    }
    public function salvarProjeto(array $posts): bool
    {
        $projeto = Projeto::map([$posts])[0];
        $projeto = $this->repositorySql->cadastrar($projeto);
        if(!empty($posts["codigos"]))
        {
            $this->codigoService->transformarObjetoArquivo($posts["codigos"],$projeto);
            
            $this->codigosUploadService = new UploadService("/users/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-".$projeto->getId()."/code");
        }
        if(!empty($posts["imagens"]))
        {
            $this->imagemService->transformarObjetoArquivo($posts["imagens"],$projeto);
            
            $this->imagensUploadService = new UploadService("/users/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-".$projeto->getId()."/img");
            
        }
        return true;
    }
    public function editarProjeto(Projeto $projeto):bool
    {
        try
        {
            $this->repositorySql->editar($projeto);
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }
}