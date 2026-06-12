<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Componente;
use app\repositories\ComponenteRepositoryInterface;
use PDO;

class ComponenteRepositorySql implements ComponenteRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Componente $componente): ?Componente
    {
        $sql = "INSERT INTO componente (nome, descricao, imagem)
                VALUES (:nome, :descricao, :imagem)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());
        $stmt->bindValue(':imagem', $componente->getImagem());
        
        if ($stmt->execute()) {
            $componente->setId($this->connection->lastInsertId());
            return $componente;
        }

        return null;
    }
    public function editar(Componente $componente): ?Componente
    {
           $sql = "UPDATE componente
                    SET 
                    nome = :nome,
                    descricao = :descricao,
                    imagem = :imagem
                    WHERE id = :id";
    
            $stmt = $this->connection->prepare($sql);
    
            $stmt->bindValue(':nome', $componente->getNome());
            $stmt->bindValue(':descricao', $componente->getDescricao());
            $stmt->bindValue(':imagem', $componente->getImagem());
            $stmt->bindValue(':id', $componente->getId(), PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $componente;
        }

        return null;
    }
    public function buscarId(Componente $componente): ?Componente
    {
        $sql = "SELECT * FROM componente WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $componente->getId());
        
        $stmt->execute();
        return Componente::map($stmt->fetchAll())[0];
    }
    public function buscarNome(Componente $componente): ?array
    {
        $sql = "SELECT * FROM componente WHERE nome LIKE :nome";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT c.*,pc.quantidade
                FROM componente c
                JOIN projeto_componente pc 
                ON pc.componente_id = c.id
                WHERE pc.projeto_id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id',$projetoId);

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM componente";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function deletar(Componente $componente): bool
    {
        $sql = "DELETE FROM componente WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $componente->getId());
        $stmt->execute();
        return true;
    }
    
}