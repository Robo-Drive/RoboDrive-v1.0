<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Comentario;
use app\models\Usuario;
use DateTimeImmutable;
use PDO;

class ComentarioRepositorySql
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
        return $this->hidratar($stmt->fetchAll(PDO::FETCH_ASSOC), 'postagem_forum_id');
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
        return $this->hidratar($stmt->fetchAll(PDO::FETCH_ASSOC), 'projeto_id');
    }

    public function buscarPorId(string $tipo, int $id): ?array
    {
        $tabela = ($tipo === 'projeto') ? 'comentario_projeto' : 'comentario_postagem_forum';
        $sql = "SELECT * FROM {$tabela} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ?: null;
    }

    public function desativar(int $id, ?string $tipo = null): bool
    {
        if ($tipo === 'projeto') {
            $sql = "UPDATE comentario_projeto SET status = false WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        if ($tipo === 'forum') {
            $sql = "UPDATE comentario_postagem_forum SET status = false WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        // Caso o tipo não venha explícito, tenta atualizar em ambas
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

    private function hidratar(array $linhas, string $campoOrigem): array
    {
        $comentarios = [];
        foreach ($linhas as $dado) {
            $c = new Comentario();
            $c->setId((int)$dado['id']);
            $c->setConteudo($dado['conteudo']);
            $c->setOrigemId((int)$dado[$campoOrigem]);
            $c->setStatus((bool)$dado['status']);
            $c->setCriadoEm(isset($dado['criado_em']) ? new DateTimeImmutable($dado['criado_em']) : null);

            $u = new Usuario();
            $u->setId((int)$dado['usuario_id']);
            $u->setNome($dado['nome'] ?? '');
            $u->setNomeUsuario($dado['nome_usuario'] ?? '');
            $u->setImagem($dado['imagem'] ?? null);
            $u->setRegra($dado['regra'] ?? 'usuario');

            $c->setUsuario($u);
            $comentarios[] = $c;
        }
        return $comentarios;
    }
}