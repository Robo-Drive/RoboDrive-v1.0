<?php

namespace app\services;

use app\models\Componente;
use app\repositories\ComponenteRepositorySql;

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
    public function editarComponente(Componente $componente): bool
    {
        try
        {
            $this->repositorySql->editar($componente);
            return true;
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());
            return false;
        }
    }
    public function associar(array $posts)
    {
        $this->repositorySql->associar($posts);
    }
    public function buscarProjeto(int $id): array  
    {
        return $this->repositorySql->buscarProjeto($id);
    }
    public function desativar()
    {

    }
}