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
            $sql = "INSERT INTO usuario(nome, nome_usuario, email, senha, regra)
            VALUES(:nome, :nome_usuario, :email, :senha, :regra)";
            
            $stmt = $this->connection->prepare($sql);
            
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':nome_usuario', $usuario->getNomeUsuario());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->bindValue(':regra', 'usuario');
            
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
        try
        {
            $sql = "UPDATE usuario SET ";
            if(null != $usuario->getNome())
            {
                $sql .= "nome = :nome";
            }
            if(null != $usuario->getNomeUsuario())
            {
                $sql .= ",nome_usuario = :nome_usuario";
            }
            if(null != $usuario->getSenha())
            {
                $sql .= ",senha = :senha";
            }
            if(null !== $usuario->getBiografia())
            {
                $sql .= ",biografia = :biografia";
            }
            if(null != $usuario->getImagem())
            {
                $sql .= ",imagem = :imagem";
            }
            if(null != $usuario->getEmail())
            {
                $sql .= ",email = :email";
            }
            if(null != $usuario->getRegra())
            {
                $sql .= ",regra = :regra ";
            }

            $sql .= "WHERE id = :id";

            //print_r($sql);
            //die;

            $stmt = $this->connection->prepare($sql);

            if(null != $usuario->getNome())
            {
                $stmt->bindValue(":nome", $usuario->getNome());  
            }
            if(null != $usuario->getNomeUsuario());
            {
                $stmt->bindValue(":nome_usuario", $usuario->getNomeUsuario());  
            }
            if(null != $usuario->getSenha())
            {
                $stmt->bindValue(":senha", $usuario->getSenha());  
            }
            if(null !== $usuario->getBiografia())
            {
                $stmt->bindValue(":biografia", $usuario->getBiografia());  
            }
            if(null != $usuario->getImagem())
            {
                $stmt->bindValue(":imagem", $usuario->getImagem());  
            }
            if(null != $usuario->getEmail())
            {
                $stmt->bindValue(":email", $usuario->getEmail());  
            }
            if(null != $usuario->getRegra())
            {
                $stmt->bindValue(":regra", $usuario->getRegra());  
            }
            $stmt->bindValue(":id", $usuario->getId());    

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            print_r($e);
            die;
        }
        
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
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT u.*,pu.papel
                FROM usuario u
                JOIN projeto_usuario pu 
                ON pu.usuario_id = u.id
                WHERE pu.projeto_id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projetoId);
        $stmt->execute();
        return Usuario::map($stmt->fetchAll());
    }
    public function buscarNome(Usuario $usuario): ?array
    {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :nome";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', "%".$usuario->getNome()."%");
        $stmt->execute();
        return Usuario::map($stmt->fetchAll());
    }
    public function buscarNomeUsuario(Usuario $usuario): ?array
    {
        $sql = "SELECT * FROM usuario WHERE nome_usuario = :nome_usuario;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome_usuario', $usuario->getNomeUsuario());
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
        $sql = "UPDATE usuario
                SET 
                status = false
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId());
        $stmt->execute();
        return true;
    }
    
}