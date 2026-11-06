<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Projeto;
use app\models\Usuario;
use PDO;
use Throwable;

class ProjetoRepositorySql implements ProjetoRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Projeto $projeto): ?array
    {
        try {
            $this->connection->beginTransaction();

            $hash = strtoupper(bin2hex(random_bytes(6)));
            $usuarioId = (int) $_SESSION['usuario_logado']->getId();
            $equipeId = $projeto->getEquipe()?->getId();
            $categoriaId = $projeto->getCategoria()?->getId();

            $sql = "INSERT INTO projeto
                        (nome, hash_acesso, senha, visibilidade, descricao, categoria_id, usuario_id, equipe_id)
                    VALUES
                        (:nome, :hash_acesso, :senha, :visibilidade, :descricao, :categoria_id, :usuario_id, :equipe_id)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':nome', $projeto->getNome());
            $stmt->bindValue(':hash_acesso', $hash);
            $stmt->bindValue(':senha', password_hash($projeto->getSenha(), PASSWORD_DEFAULT));
            $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());
            $stmt->bindValue(':descricao', $projeto->getDescricao());
            $stmt->bindValue(
                ':categoria_id',
                $categoriaId,
                $categoriaId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            );
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindValue(
                ':equipe_id',
                $equipeId,
                $equipeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            );
            $stmt->execute();

            $projetoId = (int) $this->connection->lastInsertId();
            $projeto->setId($projetoId);
            $projeto->setHashAcesso($hash);

            $sqlVersao = "INSERT INTO projeto_versao
                            (projeto_id, usuario_id, versao, descricao_alteracao)
                          VALUES
                            (:projeto_id, :usuario_id, 1, :descricao_alteracao)";
            $stmtVersao = $this->connection->prepare($sqlVersao);
            $stmtVersao->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
            $stmtVersao->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmtVersao->bindValue(':descricao_alteracao', $projeto->getDescricao());
            $stmtVersao->execute();

            $versaoId = (int) $this->connection->lastInsertId();

            $sqlMembro = "INSERT INTO projeto_usuario
                            (projeto_id, usuario_id, papel, status)
                          VALUES
                            (:projeto_id, :usuario_id, 'coordenador', 'ativo')";
            $stmtMembro = $this->connection->prepare($sqlMembro);
            $stmtMembro->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
            $stmtMembro->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmtMembro->execute();

            $projetoVersao = new Projeto();
            $projetoVersao->setId($versaoId);

            $this->connection->commit();

            return [$projeto, $projetoVersao];
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $e;
        }
    }

    public function buscarPorHash(string $hash): ?Projeto
    {
        $sql = "SELECT *
                FROM projeto
                WHERE hash_acesso = :hash
                  AND status = 1
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':hash', trim($hash));
        $stmt->execute();

        $resultado = Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado[0] ?? null;
    }

    public function solicitarEntrada(int $projetoId, int $usuarioId): bool
    {
        $sqlChecar = "SELECT id, status
                      FROM projeto_usuario
                      WHERE projeto_id = :projeto_id
                        AND usuario_id = :usuario_id";

        $stmt = $this->connection->prepare($sqlChecar);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        $existente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existente) {
            if ($existente['status'] === 'ativo') {
                return false;
            }

            $sqlUpdate = "UPDATE projeto_usuario
                          SET status = 'pendente', papel = 'participante'
                          WHERE id = :id";
            $stmtUpdate = $this->connection->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':id', (int) $existente['id'], PDO::PARAM_INT);

            return $stmtUpdate->execute();
        }

        $sqlInsert = "INSERT INTO projeto_usuario
                        (projeto_id, usuario_id, papel, status)
                      VALUES
                        (:projeto_id, :usuario_id, 'participante', 'pendente')";
        $stmtInsert = $this->connection->prepare($sqlInsert);
        $stmtInsert->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmtInsert->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmtInsert->execute();
    }

    public function listarSolicitacoesPendentes(int $projetoId): array
    {
        $sql = "SELECT u.id, u.nome, u.nome_usuario, u.imagem, pu.papel
                FROM usuario u
                INNER JOIN projeto_usuario pu ON pu.usuario_id = u.id
                WHERE pu.projeto_id = :projeto_id
                  AND pu.status = 'pendente'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aprovarSolicitacao(int $projetoId, int $usuarioId): bool
    {
        $sql = "UPDATE projeto_usuario
                SET status = 'ativo'
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id
                  AND status = 'pendente'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function rejeitarSolicitacao(int $projetoId, int $usuarioId): bool
    {
        $sql = "DELETE FROM projeto_usuario
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id
                  AND status = 'pendente'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function editar(Projeto $projeto): ?Projeto
    {
        $sql = "UPDATE projeto
                SET nome = :nome,
                    descricao = :descricao,
                    visibilidade = :visibilidade,
                    categoria_id = :categoria_id,
                    equipe_id = :equipe_id
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->bindValue(':descricao', $projeto->getDescricao());
        $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());

        $categoriaId = $projeto->getCategoria()?->getId();
        $stmt->bindValue(
            ':categoria_id',
            $categoriaId,
            $categoriaId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $equipeId = $projeto->getEquipe()?->getId();
        $stmt->bindValue(
            ':equipe_id',
            $equipeId,
            $equipeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );

        $stmt->bindValue(':id', $projeto->getId(), PDO::PARAM_INT);

        return $stmt->execute() ? $projeto : null;
    }

    public function buscarId(Projeto $projeto): ?Projeto
    {
        $sql = "SELECT p.*, c.nome AS categoria_nome
                FROM projeto p
                LEFT JOIN categoria c ON c.id = p.categoria_id
                WHERE p.id = :id
                  AND p.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projeto->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $resultado = Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado[0] ?? null;
    }

    public function buscarSeguindo(Usuario $usuario): ?array
    {
        $sql = "SELECT p.*
                FROM projeto p
                INNER JOIN projeto_usuario pu ON pu.projeto_id = p.id
                WHERE pu.usuario_id = :usuario_id
                  AND pu.status = 'ativo'
                  AND p.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $resultado = Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado ?: null;
    }

    public function atualizarPapelMembro(int $projetoId, int $usuarioId, string $papel): bool
    {
        $sql = "UPDATE projeto_usuario
                SET papel = :papel
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id
                  AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':papel', $papel);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarUsuario(Usuario $usuario): ?array
    {
        $sql = "SELECT p.*
                FROM projeto p
                INNER JOIN projeto_usuario pu ON pu.projeto_id = p.id
                WHERE pu.usuario_id = :usuario_id
                  AND pu.status = 'ativo'
                  AND p.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscarEquipe(int $id): ?array
    {
        $sql = "SELECT p.*, c.nome AS categoria_nome
                FROM projeto p
                LEFT JOIN categoria c ON c.id = p.categoria_id
                WHERE p.equipe_id = :id
                  AND p.status = 1
                ORDER BY p.id DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado ?: null;
    }

    public function buscarNome(Projeto $projeto): ?array
    {
        $sql = "SELECT *
                FROM projeto
                WHERE nome LIKE :nome
                  AND status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', '%' . $projeto->getNome() . '%');
        $stmt->execute();

        return Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function existeNome(string $nome, ?int $id = null): bool
    {
        $sql = "SELECT COUNT(*)
                FROM projeto
                WHERE nome = :nome";

        if ($id !== null) {
            $sql .= " AND id <> :id";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', trim($nome));

        if ($id !== null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function buscarUltimaVersao(int $projetoId): ?array
    {
        $sql = "SELECT pv.*, u.nome_usuario AS autor_nome
                FROM projeto_versao pv
                LEFT JOIN usuario u ON u.id = pv.usuario_id
                WHERE pv.projeto_id = :id
                ORDER BY pv.versao DESC
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public function buscarTodasVersoes(int $projetoId): array
    {
        $sql = "SELECT pv.*, u.nome_usuario AS autor_nome
                FROM projeto_versao pv
                LEFT JOIN usuario u ON u.id = pv.usuario_id
                WHERE pv.projeto_id = :id
                ORDER BY pv.versao DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarVersao(
        int $projetoId,
        int $usuarioId,
        int $versao,
        string $descricaoAlteracao
    ): int {
        $sql = "INSERT INTO projeto_versao
                    (projeto_id, usuario_id, versao, descricao_alteracao)
                VALUES
                    (:projeto_id, :usuario_id, :versao, :descricao_alteracao)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':versao', $versao, PDO::PARAM_INT);
        $stmt->bindValue(':descricao_alteracao', $descricaoAlteracao);
        $stmt->execute();

        return (int) $this->connection->lastInsertId();
    }

    public function adicionarMembro(
        int $projetoId,
        int $usuarioId,
        string $papel = 'participante'
    ): bool {
        $sqlChecar = "SELECT id
                      FROM projeto_usuario
                      WHERE projeto_id = :projeto_id
                        AND usuario_id = :usuario_id";

        $stmtChecar = $this->connection->prepare($sqlChecar);
        $stmtChecar->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmtChecar->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmtChecar->execute();

        $registroId = $stmtChecar->fetchColumn();

        if ($registroId) {
            $sql = "UPDATE projeto_usuario
                    SET status = 'ativo', papel = :papel
                    WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':papel', $papel);
            $stmt->bindValue(':id', (int) $registroId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        $sql = "INSERT INTO projeto_usuario
                    (projeto_id, usuario_id, papel, status)
                VALUES
                    (:projeto_id, :usuario_id, :papel, 'ativo')";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':papel', $papel);

        return $stmt->execute();
    }

    public function removerMembro(int $projetoId, int $usuarioId): bool
    {
        // O ENUM só aceita 'pendente' ou 'ativo'; por isso a remoção exclui a associação.
        $sql = "DELETE FROM projeto_usuario
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT *
                FROM projeto
                WHERE status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listarPublico(): array
    {
        $sql = "SELECT *
                FROM projeto
                WHERE visibilidade = 'publico'
                  AND status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscarPublico(): array
    {
        $sql = "SELECT *
                FROM projeto p
                WHERE p.visibilidade = 'publico'
                  AND p.status = 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return Projeto::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function usuarioPertenceAoProjeto(int $projetoId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*)
                FROM projeto_usuario
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id
                  AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function usuarioECoordenador(int $projetoId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*)
                FROM projeto_usuario
                WHERE projeto_id = :projeto_id
                  AND usuario_id = :usuario_id
                  AND papel = 'coordenador'
                  AND status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function desativar(int $projetoId): bool
    {
        $sql = "UPDATE projeto
                SET status = 0
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projetoId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
