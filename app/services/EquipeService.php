<?php

namespace app\services;

use app\models\Equipe;
use app\repositories\EquipeRepositorySql;

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
    public function editarEquipe(Equipe $equipe): bool
    {
        $resposta = new Equipe();
        return true;
    }
}