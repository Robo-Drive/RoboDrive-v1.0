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
        // Recupera o ID da equipe, caso exista
        $equipeId = $forum->getEquipe()?->getId();

        // Monta a consulta SQL
        $sql = "INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id";

        if ($equipeId !== null) {
            $sql .= ", equipe_id";
        }

        $sql .= ") VALUES (:conteudo, :visibilidade, :usuario_id";

        if ($equipeId !== null) {
            $sql .= ", :equipe_id";
        }

        $sql .= ")";

        // Prepara a consulta
        $stmt = $this->connection->prepare($sql);

        // Vincula os parâmetros obrigatórios
        $stmt->bindValue(':conteudo', $forum->getConteudo());
        $stmt->bindValue(':visibilidade', $forum->getVisibilidade());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());

        // Vincula a equipe somente quando existir
        if ($equipeId !== null) {
            $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        }

        // Executa a consulta
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
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem, u.email 
                FROM postagem_forum f
                JOIN usuario u ON u.id = f.usuario_id
                WHERE f.id = :id AND f.status = true";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $Forum->getId(), PDO::PARAM_INT);
        $stmt->execute();
        $resultado = Forum::map($stmt->fetchAll());
        return !empty($resultado) ? $resultado[0] : null;
    }
    public function buscarEquipe(int $id)
    {
        $sql = "SELECT *, u.nome_usuario FROM postagem_forum f JOIN usuario u ON u.id = f.usuario_id WHERE f.equipe_id = :id AND f.status = true";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        return Forum::map($stmt->fetchAll());
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

        $sql = "SELECT * FROM postagem_forum WHERE " . implode(" OR ", $condicoes);

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return Forum::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT *, u.nome_usuario 
        FROM postagem_forum f 
        JOIN usuario u ON u.id = f.usuario_id WHERE f.visibilidade='publico' AND f.status = true";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Forum::map($stmt->fetchAll());
    }
    public function desativar(int $forumId): bool
    {
        $sql = "UPDATE postagem_forum SET status = false WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $forumId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}