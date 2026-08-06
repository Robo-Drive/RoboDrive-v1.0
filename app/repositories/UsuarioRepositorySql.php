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
            $bind = array();
            $itens = ["nome","email","senha","imagem","regra"];
            $sql = "UPDATE usuario SET ";
            $itensNaoNulos = array();
            foreach($itens as $i)
            {
                $metodo = "get".ucfirst($i);
                if($usuario->$metodo() != null)
                {
                    $itensNaoNulos[] = $i;
                }
            }
            for($i = 0 ; $i < count($itensNaoNulos); $i++)
            {
                $metodo = "get".ucfirst($itensNaoNulos[$i]);
                
                if($i != (count($itensNaoNulos)-1))
                {
                    $sql .= "$itensNaoNulos[$i] = :$itensNaoNulos[$i], ";
                    $bind[] = [
                        "posicao" => ":".$itensNaoNulos[$i],
                        "metodo" => $metodo
                    ];
                }
                else
                {
                    $sql .= "$itensNaoNulos[$i] = :$itensNaoNulos[$i] ";
                    $bind[] = [
                        "posicao" => ":".$itensNaoNulos[$i],
                        "metodo" => $metodo
                    ];
                }
            }
            $sql .= "WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            if($usuario->getSenha() != null)
            {
                $stmt->bindValue(':senha',password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            
            }
            foreach($bind as $b)
            {
                if($b["posicao"] == ":senha")
                {
                    continue;
                }
                $stmt->bindValue($b["posicao"], $usuario->{$b["metodo"]}());
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