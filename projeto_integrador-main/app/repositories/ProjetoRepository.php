<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Projeto;
use PDO;

class ProjetoRepository
{

    private PDO $connection;


    function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function getProjetos(): array
    {

        $stm = $this->connection->prepare("SELECT * FROM projeto");
        $stm->execute();

        $projetos = $stm->fetchAll();

        return $projetos;
    }

    public function getProjeto(int $id)
    {

        $stm = $this->connection->prepare("SELECT * FROM projeto WHERE id = :id");
        $stm->bindValue('id', $id);

        $stm->execute();

        $projeto = $stm->fetch();

        return $projeto;
    }

    public function nomeJaExiste(string $nome, ?int $excludeId = null): bool
    {
        $nomeNormalizado = mb_strtolower(str_replace(' ', '', trim($nome)));

        if ($excludeId === null) {
            $stm = $this->connection->prepare("SELECT id FROM projeto WHERE LOWER(REPLACE(nome, ' ', '')) = :nome");
            $stm->bindValue(':nome', $nomeNormalizado);
        } else {
            $stm = $this->connection->prepare("SELECT id FROM projeto WHERE LOWER(REPLACE(nome, ' ', '')) = :nome AND id != :id");
            $stm->bindValue(':nome', $nomeNormalizado);
            $stm->bindValue(':id', $excludeId);
        }

        $stm->execute();

        return $stm->fetch() !== false;
    }

    public function saveProjeto(Projeto $projeto)
    {

        $sql = "INSERT INTO projeto (nome, visibilidade) " .
            "VALUES(:nome, :visibilidade)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());

        return $stmt->execute();
    }

    public function updateProjeto(int $id, Projeto $projeto)
    {

        $sql = "UPDATE projeto SET nome = :nome, visibilidade = :visibilidade WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $projeto->getNome());
        $stmt->bindValue(':visibilidade', $projeto->getVisibilidade());
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function deleteProjeto(int $id)
    {

        $stm = $this->connection->prepare("DELETE FROM projeto WHERE id = :id");
        $stm->bindValue('id', $id);

        return $stm->execute();
    }
}