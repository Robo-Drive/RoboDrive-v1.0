<?php

namespace app\services;

use app\repositories\CategoriaRepositorySql;

class CategoriaService
{
    private CategoriaRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new CategoriaRepositorySql();
    }

    public function cadastro() :bool|null
    {
        return true;
    }
    public function listarTodos() :array|null
    {
        return $this->repositorySql->listarTodos();
    }
}