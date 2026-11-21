<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Equipe;
use app\models\Usuario;
use PDO;
use Throwable;

class EquipeRepositorySql implements EquipeRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Equipe $equipe): ?Equipe
    {
        if (!isset($_SESSION["usuario_logado"]) || !$_SESSION["usuario_logado"] instanceof Usuario)
        {
            return null;
        }

        try
        {
            $this->connection->beginTransaction();

            $hash = strtoupper(bin2hex(random_bytes(6)));

            $sql = "INSERT INTO equipe
                    (nome, hash_acesso, descricao, senha)
                    VALUES
                    (:nome, :hash_acesso, :descricao, :senha)";

            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':nome', $equipe->getNome());
            $stmt->bindValue(':hash_acesso', $hash);
            $stmt->bindValue(':descricao', $equipe->getDescricao());
            $stmt->bindValue(':senha', password_hash($equipe->getSenha(), PASSWORD_DEFAULT));

            $stmt->execute();

            $equipeId = $this->connection->lastInsertId();

            $sqlAssoc = "INSERT INTO equipe_usuario
                         (equipe_id, usuario_id, papel, status)
                         VALUES
                         (:equipe_id, :usuario_id, 'coordenador', 'ativo')";

            $stmtAssoc = $this->connection->prepare($sqlAssoc);

            $stmtAssoc->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
            $stmtAssoc->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId(), PDO::PARAM_INT);

            $stmtAssoc->execute();

            $equipe->setId($equipeId);
            $equipe->setHashAcesso($hash);

            $this->connection->commit();

            return $equipe;
        }
        catch (Throwable $e)
        {
            if ($this->connection->inTransaction())
            {
                $this->connection->rollBack();
            }

            error_log($e->getMessage());

            return null;
        }
    }

    public function buscarPorHash(string $hash): ?Equipe
    {
        $sql = "SELECT *
                FROM equipe
                WHERE hash_acesso = :hash
                AND status = 1
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':hash', trim($hash));

        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados)
        {
            return null;
        }

        $res = Equipe::map([$dados]);

        return $res[0] ?? null;
    }

    public function solicitarEntrada(int $equipeId, int $usuarioId): bool
    {
        $sqlChecar = "SELECT id, status
                      FROM equipe_usuario
                      WHERE equipe_id = :equipe_id
                      AND usuario_id = :usuario_id
                      LIMIT 1";

        $stmt = $this->connection->prepare($sqlChecar);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        $stmt->execute();

        $existente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existente)
        {
            if ($existente["status"] === "ativo")
            {
                return false;
            }

            $sqlUp = "UPDATE equipe_usuario
                      SET status = 'pendente',
                          papel = 'participante'
                      WHERE id = :id";

            $stmtUp = $this->connection->prepare($sqlUp);
            $stmtUp->bindValue(':id', $existente["id"], PDO::PARAM_INT);

            return $stmtUp->execute();
        }

        $sql = "INSERT INTO equipe_usuario
                (equipe_id, usuario_id, papel, status)
                VALUES
                (:equipe_id, :usuario_id, 'participante', 'pendente')";

        $stmtIns = $this->connection->prepare($sql);

        $stmtIns->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmtIns->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmtIns->execute();
    }

    public function listarSolicitacoesPendentes(int $equipeId): array
    {
        $sql = "SELECT
                    u.id,
                    u.nome,
                    u.nome_usuario,
                    u.imagem,
                    eu.papel,
                    eu.id AS associacao_id
                FROM usuario u
                INNER JOIN equipe_usuario eu
                    ON eu.usuario_id = u.id
                WHERE eu.equipe_id = :equipe_id
                AND eu.status = 'pendente'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aprovarSolicitacao(int $equipeId, int $usuarioId): bool
    {
        $sql = "UPDATE equipe_usuario
                SET status = 'ativo'
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'pendente'";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function rejeitarSolicitacao(int $equipeId, int $usuarioId): bool
    {
        $sql = "DELETE FROM equipe_usuario
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'pendente'";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function buscarPapelUsuario(int $equipeId, int $usuarioId): ?string
    {
        $sql = "SELECT papel
                FROM equipe_usuario
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'ativo'
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        $stmt->execute();

        $res = $stmt->fetchColumn();

        return $res !== false ? $res : null;
    }

    public function adicionarMembro(int $equipeId, int $usuarioId, string $papel = 'participante'): bool
    {
        if (!in_array($papel, ["coordenador", "participante"], true))
        {
            return false;
        }

        $sqlChecar = "SELECT id
                      FROM equipe_usuario
                      WHERE equipe_id = :equipe_id
                      AND usuario_id = :usuario_id
                      LIMIT 1";

        $stmtChecar = $this->connection->prepare($sqlChecar);

        $stmtChecar->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmtChecar->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        $stmtChecar->execute();

        $registroId = $stmtChecar->fetchColumn();

        if ($registroId)
        {
            $sql = "UPDATE equipe_usuario
                    SET status = 'ativo',
                        papel = :papel
                    WHERE id = :id";

            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':papel', $papel);
            $stmt->bindValue(':id', $registroId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        $sql = "INSERT INTO equipe_usuario
                (equipe_id, usuario_id, papel, status)
                VALUES
                (:equipe_id, :usuario_id, :papel, 'ativo')";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':papel', $papel);

        return $stmt->execute();
    }

    public function adicionarMembroDireto(int $equipeId, int $usuarioId, string $papel = 'participante'): bool
    {
        return $this->adicionarMembro($equipeId, $usuarioId, $papel);
    }

    public function removerMembro(int $equipeId, int $usuarioId): bool
    {
        $sql = "DELETE FROM equipe_usuario
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function buscarUsuariosEquipe(int $equipeId): array
    {
        $sql = "SELECT
                    u.*,
                    eu.papel,
                    eu.papel AS papelEquipe
                FROM usuario u
                INNER JOIN equipe_usuario eu
                    ON eu.usuario_id = u.id
                WHERE eu.equipe_id = :equipe_id
                AND eu.status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function usuarioECoordenador(int $equipeId, int $usuarioId): bool
    {
        $sql = "SELECT papel
                FROM equipe_usuario
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'ativo'
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn() === "coordenador";
    }

    public function usuarioPertenceAEquipe(int $equipeId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*)
                FROM equipe_usuario
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function listarMembros(int $equipeId): array
    {
        $sql = "SELECT
                    u.id,
                    u.nome,
                    u.nome_usuario,
                    u.imagem,
                    eu.papel
                FROM usuario u
                INNER JOIN equipe_usuario eu
                    ON eu.usuario_id = u.id
                WHERE eu.equipe_id = :equipe_id
                AND eu.status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editar(Equipe $equipe): ?Equipe
    {
        $senha = $equipe->getSenha();

        if ($senha !== null && $senha !== "")
        {
            $sql = "UPDATE equipe
                    SET nome = :nome,
                        descricao = :descricao,
                        senha = :senha
                    WHERE id = :id
                    AND status = 1";

            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':senha', password_hash($senha, PASSWORD_DEFAULT));
        }
        else
        {
            $sql = "UPDATE equipe
                    SET nome = :nome,
                        descricao = :descricao
                    WHERE id = :id
                    AND status = 1";

            $stmt = $this->connection->prepare($sql);
        }

        $stmt->bindValue(':nome', $equipe->getNome());
        $stmt->bindValue(':descricao', $equipe->getDescricao());
        $stmt->bindValue(':id', $equipe->getId(), PDO::PARAM_INT);

        if (!$stmt->execute())
        {
            return null;
        }

        return $equipe;
    }

    public function buscarId(Equipe $equipe, bool $incluirDesativados = false): ?Equipe
    {
        $sql = "SELECT *
                FROM equipe
                WHERE id = :id"
                . ($incluirDesativados ? "" : " AND status = 1") . "
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $equipe->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados)
        {
            return null;
        }

        $res = Equipe::map([$dados]);

        return $res[0] ?? null;
    }

    public function buscarSeguindo(Usuario $usuario): ?array
    {
        $sql = "SELECT e.*
                FROM equipe e
                INNER JOIN equipe_usuario eu
                    ON eu.equipe_id = e.id
                WHERE eu.usuario_id = :id
                AND eu.status = 'ativo'
                AND e.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function atualizarPapelMembro(int $equipeId, int $usuarioId, string $papel): bool
    {
        if (!in_array($papel, ["coordenador", "participante"], true))
        {
            return false;
        }

        $sql = "UPDATE equipe_usuario
                SET papel = :papel
                WHERE equipe_id = :equipe_id
                AND usuario_id = :usuario_id
                AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':papel', $papel);
        $stmt->bindValue(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function alterarPapelMembro(int $equipeId, int $usuarioId, string $papel): bool
    {
        return $this->atualizarPapelMembro($equipeId, $usuarioId, $papel);
    }

    public function buscarUsuario(Usuario $usuario): ?array
    {
        $sql = "SELECT e.*
                FROM equipe e
                INNER JOIN equipe_usuario eu
                    ON eu.equipe_id = e.id
                WHERE eu.usuario_id = :id
                AND eu.status = 'ativo'
                AND e.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscarUsuarioFiltrado(Usuario $usuario, string $busca): ?array
    {
        $sql = "SELECT e.*
                FROM equipe e
                INNER JOIN equipe_usuario eu
                    ON eu.equipe_id = e.id
                WHERE eu.usuario_id = :id
                AND eu.status = 'ativo'
                AND e.status = 1
                AND e.nome LIKE :busca";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':busca', '%' . $busca . '%');
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscarNome(Equipe $equipe): ?array
    {
        $sql = "SELECT *
                FROM equipe
                WHERE nome LIKE :nome
                AND status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', '%' . $equipe->getNome() . '%');
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listarTodos(): array
    {
        $sql = "SELECT *
                FROM equipe
                WHERE status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listarAdminTodos(?string $busca = null): array
    {
        $sql = "SELECT *
                FROM equipe
                WHERE (:busca IS NULL OR nome LIKE :busca_like)
                ORDER BY id DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listarPorStatus(bool $status, ?string $busca = null): array
    {
        $sql = "SELECT *
                FROM equipe
                WHERE status = :status
                  AND (:busca IS NULL OR nome LIKE :busca_like)
                ORDER BY id DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':status', $status ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();

        return Equipe::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function deletar(Equipe $equipe): bool
    {
        $sql = "UPDATE equipe
                SET status = 0
                WHERE id = :id
                AND status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $equipe->getId(), PDO::PARAM_INT);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function ativar(int $equipeId): bool
    {
        $sql = "UPDATE equipe
                SET status = 1
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $equipeId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function desativar(int $equipeId): bool
    {
        $equipe = new Equipe();
        $equipe->setId($equipeId);

        return $this->deletar($equipe);
    }
}
