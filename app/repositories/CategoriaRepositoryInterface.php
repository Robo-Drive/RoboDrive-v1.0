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
    public function obterOuCriarParaUsuario(string $nome, int $usuarioId): int;
    public function listarPublicas(): array;
    public function listarPorUsuario(int $usuarioId): array;
    public function pertenceAoUsuario(int $categoriaId, int $usuarioId): bool;
    public function reatribuirProjetosEDesativar(int $categoriaAntigaId, int $categoriaNovaId, int $usuarioId): bool;
}
