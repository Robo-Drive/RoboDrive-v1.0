<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Componente;
use PDO;

class ComponenteRepositorySql implements ComponenteRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Componente $componente): ?Componente
    {
        $sql = "INSERT INTO componente
                (nome, descricao, imagem, usuario_id)
                VALUES
                (:nome, :descricao, :imagem, :usuario_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());
        $stmt->bindValue(':imagem', $componente->getImagem());
        $stmt->bindValue(
            ':usuario_id',
            $_SESSION["usuario_logado"]->getId(),
            PDO::PARAM_INT
        );

        if ($stmt->execute())
        {
            $componente->setId($this->connection->lastInsertId());

            return $componente;
        }

        return null;
    }

    public function existe(Componente $componente): bool
    {
        // Ao editar, ignora o próprio componente para não acusar duplicidade com ele mesmo
        $id = $componente->getId();

        $sql = "SELECT COUNT(*)
                FROM componente
                WHERE nome = :nome"
                . ($id ? " AND id <> :id" : "");

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());

        if ($id)
        {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function editar(Componente $componente): ?Componente
    {
        $imagem = $componente->getImagem();

        if ($imagem != null)
        {
            $sql = "UPDATE componente
                    SET
                        nome = :nome,
                        descricao = :descricao,
                        imagem = :imagem
                    WHERE id = :id";
        }
        else
        {
            $sql = "UPDATE componente
                    SET
                        nome = :nome,
                        descricao = :descricao
                    WHERE id = :id";
        }

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());

        if ($imagem != null)
        {
            $stmt->bindValue(':imagem', $imagem);
        }

        $stmt->bindValue(
            ':id',
            $componente->getId(),
            PDO::PARAM_INT
        );

        if ($stmt->execute())
        {
            return $componente;
        }

        return null;
    }

    public function associar(array $posts): void
    {
        $sqlVerificar = "SELECT COUNT(*)
                         FROM projeto_versao_componente
                         WHERE componente_id = :componente_id
                         AND projeto_versao_id = :projeto_versao_id";

        $stmtVerificar = $this->connection->prepare($sqlVerificar);

        $sqlInserir = "INSERT INTO projeto_versao_componente
                       (quantidade, componente_id, projeto_versao_id)
                       VALUES
                       (:quantidade, :componente_id, :projeto_versao_id)";

        $stmtInserir = $this->connection->prepare($sqlInserir);

        foreach ($posts['componentes'] as $componente)
        {
            $componenteId = $componente->getId();
            $projetoVersaoId = $posts['projeto']->getId();

            $stmtVerificar->execute([
                ':componente_id' => $componenteId,
                ':projeto_versao_id' => $projetoVersaoId
            ]);

            $existe = $stmtVerificar->fetchColumn() > 0;

            if ($existe)
            {
                continue;
            }

            $stmtInserir->execute([
                ':quantidade' => $componente->getQuantidade(),
                ':componente_id' => $componenteId,
                ':projeto_versao_id' => $projetoVersaoId
            ]);
        }
    }

    public function buscarId(Componente $componente, bool $incluirDesativados = false): ?Componente
    {
        $sql = "SELECT c.*, u.nome_usuario
                FROM componente c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE c.id = :id"
                . ($incluirDesativados ? "" : " AND c.status = true");

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':id',
            $componente->getId(),
            PDO::PARAM_INT
        );

        $stmt->execute();

        $resultado = Componente::map($stmt->fetchAll());

        return $resultado[0] ?? null;
    }

    public function buscarNome(Componente $componente): ?array
    {
        $sql = "SELECT *
                FROM componente
                WHERE nome LIKE :nome
                AND status = true";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':nome',
            "%" . $componente->getNome() . "%"
        );

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }

    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT c.*, pc.quantidade
                FROM componente c
                JOIN projeto_versao_componente pc
                    ON pc.componente_id = c.id
                JOIN projeto_versao pv
                    ON pv.id = pc.projeto_versao_id
                WHERE pv.projeto_id = :id
                AND c.status = true";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id', $projetoId, PDO::PARAM_INT);

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }

    public function listarTodos(?string $busca = null): array
    {
        $sql = "SELECT c.*, u.nome_usuario
                FROM componente c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE c.status = true
                  AND (:busca IS NULL OR c.nome LIKE :busca_like)
                ORDER BY c.nome";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }

    public function listarAdminTodos(?string $busca = null): array
    {
        $sql = "SELECT c.*, u.nome_usuario
                FROM componente c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE (:busca IS NULL OR c.nome LIKE :busca_like)
                ORDER BY c.nome";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }

    public function listarPorStatus(bool $status, ?string $busca = null): array
    {
        $sql = "SELECT c.*, u.nome_usuario
                FROM componente c
                JOIN usuario u ON u.id = c.usuario_id
                WHERE c.status = :status
                  AND (:busca IS NULL OR c.nome LIKE :busca_like)
                ORDER BY c.nome";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_BOOL);
        $stmt->bindValue(':busca', $busca ?: null);
        $stmt->bindValue(':busca_like', '%' . $busca . '%');
        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }

    public function ativar(Componente $componente): bool
    {
        $sql = "UPDATE componente
                SET status = true
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':id',
            $componente->getId(),
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function desativar(Componente $componente): bool
    {
        $sql = "UPDATE componente
                SET status = false
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':id',
            $componente->getId(),
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    // Integrantes (com e-mail) dos projetos ativos cuja versão atual usa o componente
    public function buscarIntegrantesDeProjetosComComponente(int $componenteId): array
    {
        $sql = "SELECT DISTINCT u.id AS usuario_id, u.nome, u.email, p.id AS projeto_id, p.nome AS projeto_nome
                FROM projeto_versao_componente pvc
                JOIN projeto_versao pv ON pv.id = pvc.projeto_versao_id
                JOIN projeto p ON p.id = pv.projeto_id AND p.status = true
                JOIN projeto_usuario pu ON pu.projeto_id = p.id AND pu.status = 'ativo'
                JOIN usuario u ON u.id = pu.usuario_id
                WHERE pvc.componente_id = :id
                AND pv.versao = (SELECT MAX(v.versao) FROM projeto_versao v WHERE v.projeto_id = p.id)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $componenteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorVersao(int $projetoVersaoId): array
    {
        $sql = "SELECT c.*, pvc.quantidade
                FROM componente c
                JOIN projeto_versao_componente pvc
                    ON pvc.componente_id = c.id
                WHERE pvc.projeto_versao_id = :versao_id
                AND c.status = true";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':versao_id',
            $projetoVersaoId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }
}
