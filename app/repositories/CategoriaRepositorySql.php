<?php

namespace app\repositories;

use PDO;
use app\models\Categoria;
use app\database\ConnectionFactory;
use app\repositories\CategoriaRepositoryInterface;

class CategoriaRepositorySql implements CategoriaRepositoryInterface
{
    
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Categoria $categoria): ?Categoria
    {
        return new Categoria();
    }
    public function buscarId(Categoria $categoria): ?Categoria
    {
        return new Categoria();
    }
    public function buscarNome(Categoria $categoria): ?array
    {
        return [];
    }
    public function buscarCategoria(int $projetoId): ?array
    {
        return [];
    }
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM categoria WHERE usuario_id = :usuario_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":usuario_id",$_SESSION["usuario_logado"]->getId());
        $stmt->execute();
        return Categoria::map($stmt->fetchAll());
    }
    public function editar(Categoria $categoria): ?Categoria
    {
        return new Categoria();
    }
    public function deletar(Categoria $categoria): bool
    {
        return true;
    }
}