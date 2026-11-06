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
        $nome = trim($posts['nome'] ?? '');

        if ($this->repositorySql->existeNome($nome))
        {
            throw new Exception('Já existe um projeto com esse nome.');
        }
        $posts['nome'] = $nome;
        $projeto = Projeto::map([$posts])[0];
        return $this->repositorySql->cadastrar($projeto);
    }
    public function editarProjeto(Projeto $projeto):bool
    {
        $nome = $projeto->getNome();
        $id = $projeto->getId();
        if ($this->repositorySql->existeNome($nome,$id))
        {
            throw new Exception('Já existe um projeto com esse nome.');
        }
        $this->repositorySql->editar($projeto);
        return true;
    }
}