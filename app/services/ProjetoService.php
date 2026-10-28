<?php

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use Exception;


class ProjetoService
{

    
    private ProjetoRepositorySql $repositorySql;

    
    public function __construct()
    {
        $this->repositorySql = new ProjetoRepositorySql();

    }
    public function salvarProjeto(array $posts): array
    {
        $projeto = Projeto::map([$posts])[0];
        
        //print "<pre>";
        //print_r($projeto);
        //print "</pre>";
        
        $projetos = $this->repositorySql->cadastrar($projeto);
        
        return $projetos;
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