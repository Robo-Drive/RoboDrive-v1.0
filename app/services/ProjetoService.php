<?php

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;

use app\services\CodigoService;
use app\services\ImagemService;

use Exception;

class ProjetoService
{

    private CodigoService $codigoService;
    private ImagemService $imagemService;
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
        }
        if(!empty($posts["imagens"]))
        {
            $this->imagemService->transformarObjetoArquivo($posts["imagens"],$projeto);
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