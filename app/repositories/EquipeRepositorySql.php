<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Equipe;
use app\models\Usuario;
use app\repositories\EquipeRepositoryInterface;
use PDO;

class EquipeRepositorySql implements EquipeRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Equipe $equipe): ?Equipe
    {
        $sql = "INSERT INTO equipe (nome, senha)
                VALUES (:nome, :senha)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $equipe->getNome());
        $stmt->bindValue(':senha', $equipe->getSenha());

        if ($stmt->execute()) {
            $equipe->setId($this->connection->lastInsertId());
            return $equipe;
        }

        return null;
    }
    public function editar(Equipe $equipe): ?Equipe
    {
        $sql = "UPDATE equipe
                SET 
                nome = :nome,
                senha = :senha
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $equipe->getNome());
        $stmt->bindValue(':senha', $equipe->getSenha());
        $stmt->bindValue(':id', $equipe->getId(), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $equipe;
        }

        return null;
    }
    public function buscarId(Equipe $equipe): ?Equipe
    {
        $sql = "SELECT * FROM equipe WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $equipe->getId());
        
        $stmt->execute();
        return Equipe::map($stmt->fetchAll())[0];
    }
    public function buscarSeguindo(Usuario $usuario): ?array
    {
        $sql = "SELECT * FROM equipe WHERE email = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return Equipe::map($stmt->fetchAll())[0];
    }
   public function buscarUsuario(Usuario $usuario): ?array
    {
        $sql = "SELECT p.*
                FROM equipe p
                JOIN equipe_usuario pu ON pu.equipe_id = p.id
                WHERE pu.usuario_id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();

        return Equipe::map($stmt->fetchAll());
    }
    public function buscarNome(Equipe $equipe): ?array
    {
        $sql = "SELECT * FROM equipe WHERE nome LIKE :nome";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $equipe->getNome());
        $stmt->execute();
        return Equipe::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM equipe p WHERE p.senha = 'publico'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Equipe::map($stmt->fetchAll());
    }
    public function deletar(Equipe $equipe): bool
    {
        $sql = "DELETE FROM equipe WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $equipe->getId());
        $stmt->execute();
        return true;
    }
    
}