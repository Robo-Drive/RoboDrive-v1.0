<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function getUserByEmail(string $email): ?array
    {
        $stm = $this->connection->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stm->bindValue(':email', $email);
        $stm->execute();

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function getUserById(int $id): ?array
    {
        $stm = $this->connection->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stm->bindValue(':id', $id);
        $stm->execute();

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    public function emailJaExiste(string $email, ?int $idAtual = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE email = :email";

        if ($idAtual) {
            $sql .= " AND id != :id";
        }

        $stm = $this->connection->prepare($sql);
        $stm->bindValue(':email', $email);

        if ($idAtual) {
            $stm->bindValue(':id', $idAtual);
        }

        $stm->execute();
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        return (int)($resultado['total'] ?? 0) > 0;
    }

    public function updatePasswordHash(int $id, string $hash): bool
    {
        $stm = $this->connection->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        $stm->bindValue(':senha', $hash);
        $stm->bindValue(':id', $id);
        return $stm->execute();
    }
}
