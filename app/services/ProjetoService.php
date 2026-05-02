<?php

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;

class ProjetoService
{
    private ProjetoRepositorySql $repositorySql;
    
    public function __construct()
    {
        $this->repositorySql = new ProjetoRepositorySql();
    }
    public function salvarProjeto(Projeto $projeto): bool
    {
        $this->repositorySql->cadastrar($projeto);
        return true;
    }
    public function editarProjeto(Projeto $projeto): bool
    {
        $resposta = new Projeto();
        return true;
    }
}