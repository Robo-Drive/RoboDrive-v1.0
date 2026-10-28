<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\repositories\CodigoRepositoryInterface;
use PDO;

class CodigoRepositorySql implements CodigoRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(array $posts): bool
    {
        $sql = "INSERT INTO codigo(caminho,descricao) VALUES(:caminho, :descricao)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":caminho",$posts["caminho"]);
        $stmt->bindValue(":descricao",$posts["descricao"]);
        $stmt->execute()
        return true;
    }
}