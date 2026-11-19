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
        $sql = "INSERT INTO categoria(nome,usuario_id) VALUES(:nome,:usuario_id)";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':nome', $categoria->getNome());
        $stmt->bindValue(':usuario_id', $categoria->getUsuario()->getId());
        
        $stmt->execute();
        
        $categoria->setId($this->connection->lastInsertId());

        return $categoria;
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
    public function listarPublicas(): array
    {
        $sql = "SELECT DISTINCT c.* 
                FROM categoria c
                INNER JOIN projeto p ON p.categoria_id = c.id
                WHERE p.visibilidade = 'publico' AND p.status = 1
                ORDER BY c.nome ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Categoria::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
        public function listarPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT * FROM categoria WHERE usuario_id = :usuario_id AND status = true ORDER BY nome ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        return Categoria::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function reatribuirProjetosEDesativar(int $categoriaAntigaId, int $categoriaNovaId, int $usuarioId): bool
    {
        // Garante que ambas as categorias pertençam ao usuário
        $sqlVerifica = "SELECT COUNT(*) FROM categoria WHERE id IN (:antiga, :nova) AND usuario_id = :usuario_id AND status = true";
        $stmtVerifica = $this->connection->prepare($sqlVerifica);
        $stmtVerifica->bindValue(':antiga', $categoriaAntigaId, PDO::PARAM_INT);
        $stmtVerifica->bindValue(':nova', $categoriaNovaId, PDO::PARAM_INT);
        $stmtVerifica->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmtVerifica->execute();

        if ((int)$stmtVerifica->fetchColumn() < 2) 
        {
            return false;
        }

        // Reatribui todos os projetos da categoria antiga para a nova
        $sqlReatribuir = "UPDATE projeto SET categoria_id = :nova WHERE categoria_id = :antiga";
        $stmtReatribuir = $this->connection->prepare($sqlReatribuir);
        $stmtReatribuir->bindValue(':nova', $categoriaNovaId, PDO::PARAM_INT);
        $stmtReatribuir->bindValue(':antiga', $categoriaAntigaId, PDO::PARAM_INT);
        $stmtReatribuir->execute();

        // Desativação lógica da categoria antiga
        $sqlDesativar = "UPDATE categoria SET status = false WHERE id = :antiga AND usuario_id = :usuario_id";
        $stmtDesativar = $this->connection->prepare($sqlDesativar);
        $stmtDesativar->bindValue(':antiga', $categoriaAntigaId, PDO::PARAM_INT);
        $stmtDesativar->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        return $stmtDesativar->execute();
    }
}