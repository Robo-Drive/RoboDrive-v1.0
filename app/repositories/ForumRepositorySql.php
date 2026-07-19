<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Forum;
use app\repositories\ForumRepositoryInterface;
use PDO;

class ForumRepositorySql implements ForumRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }
    public function cadastrar(Forum $forum): ?Forum
    {
        $sql = "INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id)
                VALUES (:conteudo, :visibilidade, :usuario_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':conteudo', $forum->getConteudo());
        $stmt->bindValue(':visibilidade', $forum->getVisibilidade());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());
        
        if ($stmt->execute()) {
            $forum->setId($this->connection->lastInsertId());
            return $forum;
        }

        return null;
    }
    public function editar(Forum $forum): ?Forum
    {
           $sql = "UPDATE postagem_forum
                    SET 
                    conteudo = :conteudo,
                    visibilidade = :visibilidade,
                    usuario_id = :usuario_id
                    equipe_id = :equipe_id
                    WHERE id = :id";
    
            $stmt = $this->connection->prepare($sql);
    
            $stmt->bindValue(':conteudo', $forum->getConteudo());
            $stmt->bindValue(':visibilidade', $forum->getVisibilidade());
            $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());
            $stmt->bindValue(':equipe_id', $forum->getEquipe()->getId()??null);
            $stmt->bindValue(':id', $forum->getId(), PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $forum;
        }

        return null;
    }
    public function buscarId(Forum $Forum): ?Forum
    {
        $sql = "SELECT * FROM postagem_forum WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $Forum->getId());
        
        $stmt->execute();
        return Forum::map($stmt->fetchAll())[0];
    }
    public function buscarConteudo(Forum $forum): ?array
    {
        $palavras = explode(" ", strtolower($forum->getConteudo()));

        $condicoes = [];
        $params = [];

        foreach ($palavras as $p)
        {
            $condicoes[] = "conteudo LIKE ?";
            $params[] = "%$p%";
        }

        $sql = "SELECT * FROM forum WHERE " . implode(" OR ", $condicoes);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return Forum::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT *, u.nome_usuario 
        FROM postagem_forum f 
        JOIN usuario u ON u.id = f.usuario_id WHERE f.visibilidade='publico'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Forum::map($stmt->fetchAll());
    }
    public function deletar(Forum $forum): bool
    {
        $sql = "DELETE FROM postagem_forum WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $forum->getId());
        $stmt->execute();
        return true;
    }
    
}