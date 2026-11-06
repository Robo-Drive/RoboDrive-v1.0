<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Imagem;
use PDO;

class ImagemRepositorySql implements ImagemRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Imagem $imagem): bool
    {
        $sql = "INSERT INTO imagem_projeto (caminho, projeto_id)
                VALUES (:caminho, :projeto_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':caminho', $imagem->getCaminho());
        $stmt->bindValue(
            ':projeto_id',
            $imagem->getProjetoId(),
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT * FROM imagem_projeto WHERE projeto_id = :projeto_id AND status = true";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();
        return Imagem::map($stmt->fetchAll());
    }
    public function desativarImagensExceto(int $projetoId, array $idsManter): bool
    {
        if (empty($idsManter)) {
           $sql = "UPDATE imagem_projeto SET status = false WHERE projeto_id = :projeto_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':projeto_id', $projetoId, \PDO::PARAM_INT);
            return $stmt->execute();
        }
        $placeholders = implode(',', array_fill(0, count($idsManter), '?'));
        $sql = "UPDATE imagem_projeto 
                SET status = false 
                WHERE projeto_id = ? AND id NOT IN ($placeholders)";

        $stmt = $this->connection->prepare($sql);
        $parametros = array_merge([$projetoId], array_map('intval', $idsManter));
        return $stmt->execute($parametros);
    }
    
}
