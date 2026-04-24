<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Projeto;
use app\models\Usuario;
use app\models\Equipe;
use app\repositories\ProjetoRepositoryInterface;
use PDO;

class ProjetoRepositorySql implements ProjetoRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Projeto $projeto): ?Projeto
    {
        $sql = "INSERT INTO projeto (nome, visibilidade)
                VALUES (:nome, :visibilidade)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());

        if ($stmt->execute()) {
            $projeto->setId($this->connection->lastInsertId());
            return $projeto;
        }

        return null;
    }
    public function editar(Projeto $projeto): ?Projeto
    {
        $sql = "UPDATE projeto
                SET 
                nome = :nome,
                visibilidade = :visibilidade
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());
        $stmt->bindValue(':id', $projeto->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $projeto;
        }

        return null;
    }
    public function buscarId(Projeto $projeto): ?Projeto
    {
        $sql = "SELECT * FROM projeto WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $projeto->getId());
        
        $stmt->execute();
        return Projeto::map($stmt->fetchAll())[0];
    }
    public function buscarSeguindo(Usuario $usuario): ?array
    {
        $sql = "SELECT * FROM projeto WHERE email = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return Projeto::map($stmt->fetchAll())[0];
    }
    public function buscarUsuario(Usuario $usuario): ?array
    {
        $sql = "SELECT p.*
                FROM projeto p
                JOIN projeto_usuario pu ON pu.projeto_id = p.id
                WHERE pu.usuario_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return Projeto::map($stmt->fetchAll())[0];
    }
    public function buscarEquipe(Equipe $equipe): ?array
    {
        $sql = "SELECT DISTINCT p.*
                FROM projeto p
                JOIN projeto_usuario pu ON pu.projeto_id = p.id
                JOIN usuario u ON u.id = pu.usuario_id
                JOIN equipe_usuario eu ON eu.usuario_id = u.id
                WHERE eu.equipe_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $equipe->getId());
        $stmt->execute();
        return Projeto::map($stmt->fetchAll())[0];
    }
    public function buscarNome(Projeto $projeto): ?array
    {
        $sql = "SELECT * FROM projeto WHERE nome LIKE :nome";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->execute();
        return Projeto::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM projeto p WHERE p.visibilidade = 'publico'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Projeto::map($stmt->fetchAll());
    }
    public function deletar(Projeto $projeto): bool
    {
        $sql = "DELETE FROM projeto WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projeto->getId());
        $stmt->execute();
        return true;
    }
    
}