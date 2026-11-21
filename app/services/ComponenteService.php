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
    public function salvarComponente(Componente $componente): array
    {
        if($this->repositorySql->existe($componente))
        {
            return ["resposta" => false, "erro" => "Já existe um componente com esse nome."];
        }

        $this->repositorySql->cadastrar($componente);
        return ["resposta" => true];
    }
    public function editarComponente(Componente $componente): array
    {
        try
        {
            if($this->repositorySql->existe($componente))
            {
                return ["resposta" => false, "erro" => "Já existe um componente com esse nome."];
            }

            $this->repositorySql->editar($componente);
            return ["resposta" => true];
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());
            return ["resposta" => false, "erro" => "Erro interno ao atualizar o componente."];
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