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
        $equipeId = $forum->getEquipe()?->getId();

        $sql = "INSERT INTO postagem_forum (conteudo, visibilidade, usuario_id";

        if ($equipeId !== null)
        {
            $sql .= ", equipe_id";
        }

        $sql .= ") VALUES (:conteudo, :visibilidade, :usuario_id";

        if ($equipeId !== null)
        {
            $sql .= ", :equipe_id";
        }

        $sql .= ")";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':conteudo', $forum->getConteudo());
        $stmt->bindValue(':visibilidade', $forum->getVisibilidade());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());

        if ($equipeId !== null)
        {
            $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        }

        if ($stmt->execute())
        {
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
                usuario_id = :usuario_id,
                equipe_id = :equipe_id
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':conteudo', $forum->getConteudo());
        $stmt->bindValue(':visibilidade', $forum->getVisibilidade());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());
        $stmt->bindValue(':equipe_id', $forum->getEquipe()?->getId() ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':id', $forum->getId(), PDO::PARAM_INT);
    
        if ($stmt->execute())
        {
            return $forum;
        }

        return null;
    }
    public function buscarId(Forum $Forum, bool $incluirDesativados = false): ?Forum
    {
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem, u.email 
                FROM postagem_forum f
                JOIN usuario u ON u.id = f.usuario_id
                WHERE f.id = :id"
                . ($incluirDesativados ? "" : " AND f.status = true");
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $Forum->getId(), PDO::PARAM_INT);
        $stmt->execute();
        $resultado = Forum::map($stmt->fetchAll());
        return !empty($resultado) ? $resultado[0] : null;
    }
    public function buscarEquipe(int $id, ?string $busca = null)
    {
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem 
                FROM postagem_forum f 
                JOIN usuario u ON u.id = f.usuario_id 
                WHERE f.equipe_id = :id AND f.status = true
                  AND (:busca IS NULL OR f.conteudo LIKE :busca_like)
                ORDER BY f.criado_em DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":busca", $busca ?: null);
        $stmt->bindValue(":busca_like", "%" . $busca . "%");
        $stmt->execute();
        return Forum::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function buscarConteudo(Forum $forum): ?array
    {
        $conteudo = trim($forum->getConteudo() ?? "");

        if (empty($conteudo))
        {
            return $this->listarTodos();
        }

        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem 
                FROM postagem_forum f 
                JOIN usuario u ON u.id = f.usuario_id 
                WHERE f.visibilidade = 'publico' 
                  AND f.status = true 
                  AND f.conteudo LIKE :busca
                ORDER BY f.criado_em DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':busca', '%' . $conteudo . '%');
        $stmt->execute();

        return Forum::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function listarTodos(): array
    {
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem 
                FROM postagem_forum f 
                JOIN usuario u ON u.id = f.usuario_id 
                WHERE f.visibilidade = 'publico' AND f.status = true 
                ORDER BY f.criado_em DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Forum::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function listarAdminTodos(?string $busca = null): array
    {
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem 
                FROM postagem_forum f 
                JOIN usuario u ON u.id = f.usuario_id 
                WHERE (:busca IS NULL OR f.conteudo LIKE :busca_like)
                ORDER BY f.criado_em DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();
        return Forum::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function listarPorStatus(bool $status, ?string $busca = null): array
    {
        $sql = "SELECT f.*, u.nome, u.nome_usuario, u.imagem 
                FROM postagem_forum f 
                JOIN usuario u ON u.id = f.usuario_id 
                WHERE f.status = :status 
                  AND (:busca IS NULL OR f.conteudo LIKE :busca_like)
                ORDER BY f.criado_em DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_BOOL);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();
        return Forum::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function ativar(int $forumId): bool
    {
        $sql = "UPDATE postagem_forum SET status = true WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $forumId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function desativar(int $forumId): bool
    {
        $sql = "UPDATE postagem_forum SET status = false WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $forumId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}