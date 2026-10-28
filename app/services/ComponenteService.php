<?php

namespace app\services;

use app\models\Componente;
use app\repositories\ComponenteRepositorySql;
use Exception;

class ComponenteService
{
    private ComponenteRepositorySql $repositorySql;
    
    public function __construct()
    {
        $this->repositorySql = new ComponenteRepositorySql();
    }
    public function listarTodos() : array|null
    {
        return $this->repositorySql->listarTodos();
    }
    public function salvarComponente(Componente $equipe): bool
    {
        $this->repositorySql->cadastrar($equipe);
        return true;
    }
    public function editarComponente(Componente $equipe):bool
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
    public function associar(array $posts)
    {
        $this->repositorySql->associar($posts);
    }
    public function buscarProjeto(int $id): array
    {

        return [];
    }
    public function desativar()
    {

    }
}