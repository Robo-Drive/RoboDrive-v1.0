<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Equipe;
use PDO;

class EquipeRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    /**
     * Obter todas as equipes
     */
    public function getEquipes(): array
    {
        $stm = $this->connection->prepare("
            SELECT e.id, e.nome_equipe, e.professor_id, u.nome as professor_nome, e.criado_em
            FROM equipe e
            INNER JOIN usuarios u ON e.professor_id = u.id
            ORDER BY e.criado_em DESC
        ");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obter equipe por ID
     */
    public function getEquipeById(int $id): ?array
    {
        $stm = $this->connection->prepare("
            SELECT e.*, u.nome as professor_nome
            FROM equipe e
            INNER JOIN usuarios u ON e.professor_id = u.id
            WHERE e.id = :id
        ");
        $stm->bindValue(':id', $id);
        $stm->execute();

        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    /**
     * Verificar se nome da equipe já existe (regra de negócio)
     */
    public function nomeEquipeJaExiste(string $nomeEquipe, ?int $idAtual = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM equipe WHERE nome_equipe = :nome";
        
        if ($idAtual) {
            $sql .= " AND id != :id";
        }

        $stm = $this->connection->prepare($sql);
        $stm->bindValue(':nome', $nomeEquipe);
        
        if ($idAtual) {
            $stm->bindValue(':id', $idAtual);
        }

        $stm->execute();
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

    /**
     * Salvar nova equipe
     */
    public function saveEquipe(Equipe $equipe): bool
    {
        $sql = "INSERT INTO equipe (nome_equipe, senha, professor_id) 
                VALUES(:nome, :senha, :professor_id)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $equipe->getNomeEquipe());
        $stmt->bindValue(':senha', password_hash($equipe->getSenha(), PASSWORD_BCRYPT));
        $stmt->bindValue(':professor_id', $equipe->getProfessorId());

        return $stmt->execute();
    }

    /**
     * Atualizar equipe
     */
    public function updateEquipe(Equipe $equipe): bool
    {
        $sql = "UPDATE equipe SET nome_equipe = :nome, senha = :senha WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $equipe->getNomeEquipe());
        $stmt->bindValue(':senha', password_hash($equipe->getSenha(), PASSWORD_BCRYPT));
        $stmt->bindValue(':id', $equipe->getId());

        return $stmt->execute();
    }

    /**
     * Deletar equipe
     */
    public function deleteEquipe(int $id): bool
    {
        $sql = "DELETE FROM equipe WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    /**
     * Contar membros da equipe (regra de negócio: não deletar se tiver membros)
     */
    public function countMembrosEquipe(int $equipeId): int
    {
        // Adaptado para verificar se há alunos associados
        return 0; // Por enquanto, sempre permite deletar
    }
}
