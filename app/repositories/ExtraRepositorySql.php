<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Extra;
use PDO;

class ExtraRepositorySql implements ExtraRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Extra $extra, int $projetoVersaoId): ?Extra
    {
        $sql = "INSERT INTO extra (caminho, descricao, status) VALUES (:caminho, :descricao, true)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':caminho', $extra->getCaminho());
        $stmt->bindValue(':descricao', $extra->getDescricao());

        if ($stmt->execute())
        {
            $extraId = (int)$this->connection->lastInsertId();
            $extra->setId($extraId);
            $this->associarVersao($extraId, $projetoVersaoId);
            return $extra;
        }
        return null;
    }

    public function associarVersao(int $extraId, int $projetoVersaoId): bool
    {
        $sql = "INSERT INTO extra_versao (extra_id, projeto_versao_id) VALUES (:extra_id, :projeto_versao_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':extra_id', $extraId, PDO::PARAM_INT);
        $stmt->bindValue(':projeto_versao_id', $projetoVersaoId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarPorVersao(int $projetoVersaoId): array
    {
        $sql = "SELECT e.* 
                FROM extra e
                JOIN extra_versao ev ON ev.extra_id = e.id
                WHERE ev.projeto_versao_id = :versao_id AND e.status = true
                ORDER BY e.id ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':versao_id', $projetoVersaoId, PDO::PARAM_INT);
        $stmt->execute();
        return Extra::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
