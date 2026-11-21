<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Comentario;
use PDO;

class ComentarioRepositorySql implements ComentarioRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function salvarForum(int $forumId, int $usuarioId, string $conteudo): bool
    {
        $sql = "INSERT INTO comentario_postagem_forum (postagem_forum_id, usuario_id, conteudo, status) 
                VALUES (:forum_id, :usuario_id, :conteudo, true)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':forum_id', $forumId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':conteudo', $conteudo);
        return $stmt->execute();
    }

    public function salvarProjeto(int $projetoId, int $usuarioId, string $conteudo): bool
    {
        $sql = "INSERT INTO comentario_projeto (projeto_id, usuario_id, conteudo, status) 
                VALUES (:projeto_id, :usuario_id, :conteudo, true)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':conteudo', $conteudo);
        return $stmt->execute();
    }

    public function buscarPorForum(int $forumId): array
    {
        $sql = "SELECT c.*, u.nome, u.nome_usuario, u.imagem, u.regra 
                FROM comentario_postagem_forum c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE c.postagem_forum_id = :forum_id AND c.status = true
                ORDER BY c.criado_em ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':forum_id', $forumId, PDO::PARAM_INT);
        $stmt->execute();
        return (new Comentario)::map($stmt->fetchAll(PDO::FETCH_ASSOC), 'postagem_forum_id');
    }

    public function buscarPorProjeto(int $projetoId): array
    {
        $sql = "SELECT c.*, u.nome, u.nome_usuario, u.imagem, u.regra 
                FROM comentario_projeto c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE c.projeto_id = :projeto_id AND c.status = true
                ORDER BY c.criado_em ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();
        return (new Comentario)::map($stmt->fetchAll(PDO::FETCH_ASSOC), 'projeto_id');
    }

    public function buscarPorId(string $tipo, int $id): ?Comentario
    {
        $tabela = ($tipo === 'projeto') ? 'comentario_projeto' : 'comentario_postagem_forum';
        $chaveOrigem = ($tipo === 'projeto') ? 'projeto_id' : 'postagem_forum_id';
        $sql = "SELECT * FROM {$tabela} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$dados)
        {
            return null;
        }
        $comentarios = (new Comentario)::map([$dados], $chaveOrigem);
        return $comentarios[0] ?? null;
    }

    public function desativar(int $id, ?string $tipo = null): bool
    {
        if ($tipo === 'projeto')
        {
            $sql = "UPDATE comentario_projeto SET status = false WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        if ($tipo === 'forum')
        {
            $sql = "UPDATE comentario_postagem_forum SET status = false WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        $sql1 = "UPDATE comentario_postagem_forum SET status = false WHERE id = :id";
        $stmt1 = $this->connection->prepare($sql1);
        $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = "UPDATE comentario_projeto SET status = false WHERE id = :id";
        $stmt2 = $this->connection->prepare($sql2);
        $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        return true;
    }
}
