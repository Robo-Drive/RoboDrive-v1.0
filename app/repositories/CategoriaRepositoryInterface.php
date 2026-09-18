<?php

namespace app\repositories;

use app\models\Categoria;

Interface CategoriaRepositoryInterface
{
    public function cadastrar(Categoria $categoria): ?Categoria;
    public function buscarId(Categoria $categoria): ?Categoria;
    public function buscarNome(Categoria $categoria): ?array;
    public function buscarCategoria(int $projetoId): ?array;
    public function listarTodos(): array;
    public function editar(Categoria $categoria): ?Categoria;
    public function deletar(Categoria $categoria): bool;
}