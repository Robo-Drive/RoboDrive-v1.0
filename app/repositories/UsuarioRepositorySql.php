<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Usuario;
use app\repositories\UsuarioRepositoryInterface;
use PDO;
use PDOException;

class UsuarioRepositorySql implements UsuarioRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Usuario $usuario): ?Usuario
    {

        try
        {
            $sql = "INSERT INTO usuario (nome, email, senha, imagem, regra) VALUES(:nome, :email, :senha, :imagem, :regra)";
            
            $stmt = $this->connection->prepare($sql);
            
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->bindValue(':imagem', $usuario->getImagem());
            $stmt->bindValue(':regra', $usuario->getRegra());
            
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            print_r($e);
            die;
        }

        return $usuario;
    }
    public function editar(Usuario $usuario): ?Usuario
    {
        return $usuario;
    }
    public function buscarId(Usuario $usuario): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return Usuario::map($stmt->fetchAll())[0];
    }
    public function buscarEmail(Usuario $usuario): ?Usuario
    {
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->execute();
        return Usuario::map($stmt->fetchAll())[0]??null;
    }
    public function buscarNome(Usuario $usuario): ?array
    {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :nome";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->execute();
        return Usuario::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT * FROM usuario";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Usuario::map($stmt->fetchAll());
    }
    public function deletar(Usuario $usuario): bool
    {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return true;
    }
    
}