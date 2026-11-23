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
    public function obterOuCriarParaUsuario(string $nome, int $usuarioId): int
    {
        $sqlBusca = "SELECT id FROM categoria
                     WHERE nome = :nome AND usuario_id = :usuario_id AND status = 1
                     LIMIT 1";

        $stmt = $this->connection->prepare($sqlBusca);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        $categoriaId = $stmt->fetchColumn();

        if ($categoriaId !== false)
        {
            return (int)$categoriaId;
        }

        $sqlInsercao = "INSERT INTO categoria (nome, usuario_id) VALUES (:nome, :usuario_id)";

        $stmt = $this->connection->prepare($sqlInsercao);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return (int)$this->connection->lastInsertId();
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
        $sql = "SELECT * FROM categoria WHERE usuario_id = :usuario_id AND status = true ORDER BY nome ASC";
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

    public function pertenceAoUsuario(int $categoriaId, int $usuarioId): bool
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM categoria WHERE id = :id AND usuario_id = :usuario_id AND status = true");
        $stmt->bindValue(':id', $categoriaId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return (int)$stmt->fetchColumn() > 0;
    }

    public function reatribuirProjetosEDesativar(int $categoriaAntigaId, int $categoriaNovaId, int $usuarioId): bool
    {
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

        $sqlReatribuir = "UPDATE projeto SET categoria_id = :nova WHERE categoria_id = :antiga";
        $stmtReatribuir = $this->connection->prepare($sqlReatribuir);
        $stmtReatribuir->bindValue(':nova', $categoriaNovaId, PDO::PARAM_INT);
        $stmtReatribuir->bindValue(':antiga', $categoriaAntigaId, PDO::PARAM_INT);
        $stmtReatribuir->execute();

        $sqlDesativar = "UPDATE categoria SET status = false WHERE id = :antiga AND usuario_id = :usuario_id";
        $stmtDesativar = $this->connection->prepare($sqlDesativar);
        $stmtDesativar->bindValue(':antiga', $categoriaAntigaId, PDO::PARAM_INT);
        $stmtDesativar->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        return $stmtDesativar->execute();
    }
}