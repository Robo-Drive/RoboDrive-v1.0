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
        $sql = "INSERT INTO componente (nome, descricao, imagem, usuario_id)
                VALUES (:nome, :descricao, :imagem, :usuario_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());
        $stmt->bindValue(':imagem', $componente->getImagem());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());
        
        if ($stmt->execute()) {
            $componente->setId($this->connection->lastInsertId());
            return $componente;
        }

        return null;
    }
    public function editar(Componente $componente): ?Componente
    {   
        
        $imagem = $componente->getImagem();
        if($imagem != null)
        {
            
            $sql = "UPDATE componente
                     SET 
                     nome = :nome,
                     descricao = :descricao,
                     imagem = :imagem
                     WHERE id = :id";
     
        }
        else
        {
        
            $sql = "UPDATE componente
                    SET 
                    nome = :nome,
                    descricao = :descricao
                    WHERE id = :id";
        }   
        $stmt = $this->connection->prepare($sql);
    
        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());
        if($imagem != null)
        {
            $stmt->bindValue(':imagem', $componente->getImagem());
        }
        $stmt->bindValue(':id', $componente->getId(), PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $componente;
        }

        return null;
    }
    public function buscarId(Componente $componente): ?Componente
    {
        $sql = "SELECT c.*, u.nome_usuario FROM componente c JOIN usuario u ON u.id = c.usuario_id WHERE c.id = :id  AND c.status=true";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $componente->getId());
        
        $stmt->execute();
        return Componente::map($stmt->fetchAll())[0];
    }
    public function buscarNome(Componente $componente): ?array
    {
        $sql = "SELECT * FROM componente WHERE nome LIKE :nome AND c.status=true";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT c.*,pc.quantidade
                FROM componente c
                JOIN projeto_versao_componente pc 
                ON pc.componente_id = c.id
                WHERE pc.projeto_versao_id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id',$projetoId);

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT c.*, u.nome_usuario  FROM componente c
        JOIN usuario u
        ON u.id = c.usuario_id WHERE c.status=true";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function deletar(Componente $componente): bool
    {
        $sql = "UPDATE componente
                SET 
                status = false
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $componente->getId());
        $stmt->execute();
        return true;
    }
    
}