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

    public function cadastro(array $posts, ?int $proprietarioId = null): Categoria
    {
        $usuarioId = $proprietarioId ?: ($_SESSION["usuario_logado"]->getId() ?? null);
        $cadastradas = $usuarioId ? $this->repositorySql->listarPorUsuario($usuarioId) : $this->repositorySql->listarTodos();

        foreach($cadastradas as $cad)
        {
            if(mb_strtolower($cad->getNome()) === mb_strtolower($posts["categoria_nome"]))
            {
                return $cad;
            }
        }

        $dados = [
            "nome" => $posts["categoria_nome"],
            "usuario_id" => $usuarioId
        ];
        $categoria = Categoria::map([$dados])[0];
        return $this->repositorySql->cadastrar($categoria);
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        return $this->repositorySql->listarPorUsuario($usuarioId);
    }
    public function listarTodos() :array|null
    {
        return $this->repositorySql->listarTodos();
    }
    public function listarPublicas(): array
    {
        return $this->repositorySql->listarPublicas();
    }
}