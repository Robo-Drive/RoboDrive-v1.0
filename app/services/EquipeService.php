<?php

namespace app\services;

use app\models\Equipe;
use app\repositories\EquipeRepositorySql;
use Exception;

class EquipeService
{
    private EquipeRepositorySql $repositorySql;
    
    public function __construct()
    {
        $this->repositorySql = new EquipeRepositorySql();
    }
    public function salvarEquipe(Equipe $equipe): bool
    {
        $this->repositorySql->cadastrar($equipe);
        return true;
    }
    public function editarEquipe(Equipe $equipe):bool
    {
        try
        {
            $this->repositorySql->editar($equipe);
        }
        catch(Exception $e)
        {
            print_r($e);
            die;
            return false;
        }
        return true;
    }
}