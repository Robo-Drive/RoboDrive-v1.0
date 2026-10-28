<?php

namespace app\services;

use app\models\Categoria;
use app\repositories\CategoriaRepositorySql;

class CategoriaService
{
    private CategoriaRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new CategoriaRepositorySql();
    }

    public function cadastro(array $posts) :Categoria
    {
        $cadastradas = $this->repositorySql->listarTodos();
        foreach($cadastradas as $cad)
        {
            if($cad->getNome() == $posts["categoria_nome"])
            {
                return $cad;
            }
        }
        $categoria = new Categoria();
        $dados = ["nome" => $posts["categoria_nome"]];
        $categoria = Categoria::map([$dados])[0];
        return $this->repositorySql->cadastrar($categoria);
    }
    public function listarTodos() :array|null
    {
        return $this->repositorySql->listarTodos();
    }
}