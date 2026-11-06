<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Codigo;
use PDO;

class CodigoRepositorySql implements CodigoRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Codigo $codigo, int $projetoVersaoId): bool
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO codigo (caminho, descricao)
                    VALUES (:caminho, :descricao)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':caminho', $codigo->getCaminho());
            $stmt->bindValue(':descricao', $codigo->getDescricao());
            $stmt->execute();

            $codigoId =  $this->connection->lastInsertId();

            $sqlAssoc = "INSERT INTO codigo_versao
                            (codigo_id, projeto_versao_id)
                         VALUES (:codigo_id, :projeto_versao_id)";

            $stmtAssoc = $this->connection->prepare($sqlAssoc);
            $stmtAssoc->bindValue(':codigo_id', $codigoId, PDO::PARAM_INT);
            $stmtAssoc->bindValue(
                ':projeto_versao_id',
                $projetoVersaoId,
                PDO::PARAM_INT
            );
            $stmtAssoc->execute();

            $this->connection->commit();

            return true;
        } catch (\Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $e;
        }
    }
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT c.* 
                FROM codigo c
                JOIN codigo_versao cv ON cv.codigo_id = c.id
                JOIN projeto_versao pv ON pv.id = cv.projeto_versao_id
                WHERE pv.projeto_id = :projeto_id AND c.status = true
                ORDER BY c.id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();
        return Codigo::map($stmt->fetchAll());
    }
    public function buscarPorVersao(int $projetoVersaoId): array
    {
        $sql = "SELECT c.* 
                FROM codigo c
                JOIN codigo_versao cv ON cv.codigo_id = c.id
                WHERE cv.projeto_versao_id = :versao_id AND c.status = true
                ORDER BY c.id ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':versao_id', $projetoVersaoId, \PDO::PARAM_INT);
        $stmt->execute();
        return \app\models\Codigo::map($stmt->fetchAll());
    }
    public function associarVersao(int $codigoId, int $projetoVersaoId): bool
    {
        $sql = "INSERT INTO codigo_versao (codigo_id, projeto_versao_id)
                VALUES (:codigo_id, :projeto_versao_id)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':codigo_id', $codigoId, \PDO::PARAM_INT);
        $stmt->bindValue(':projeto_versao_id', $projetoVersaoId, \PDO::PARAM_INT);
        return $stmt->execute();
    }
}
