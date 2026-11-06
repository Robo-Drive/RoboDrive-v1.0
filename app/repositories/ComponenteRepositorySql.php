<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Componente;
use app\repositories\ComponenteRepositoryInterface;
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
        $sql = "INSERT INTO componente (nome, descricao, imagem, usuario_id)
                VALUES (:nome, :descricao, :imagem, :usuario_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->bindValue(':descricao', $componente->getDescricao());
        $stmt->bindValue(':imagem', $componente->getImagem());
        $stmt->bindValue(':usuario_id', $_SESSION["usuario_logado"]->getId());
        
        if ($stmt->execute()) {
            $componente->setId($this->connection->lastInsertId());
            return $componente;
        }

        return null;
    }
    public function editar(Componente $componente): ?Componente
    {   
        
        $imagem = $componente->getImagem();
        if($imagem != null)
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
        if($imagem != null)
        {
            $stmt->bindValue(':imagem', $componente->getImagem());
        }
        $stmt->bindValue(':id', $componente->getId(), PDO::PARAM_INT);
        
        if ($stmt->execute()) {
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

            $existe =  $stmtVerificar->fetchColumn() > 0;

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
    public function buscarId(Componente $componente): ?Componente
    {
        $sql = "SELECT c.*, u.nome_usuario FROM componente c JOIN usuario u ON u.id = c.usuario_id WHERE c.id = :id  AND c.status=true";
        
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindValue(':id', $componente->getId());
        
        $stmt->execute();
        return Componente::map($stmt->fetchAll())[0];
    }
    public function buscarNome(Componente $componente): ?array
    {
        $sql = "SELECT * FROM componente WHERE nome LIKE :nome AND c.status=true";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', $componente->getNome());
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT c.*, pc.quantidade
        FROM componente c
        JOIN projeto_versao_componente pc ON pc.componente_id = c.id
        JOIN projeto_versao pv ON pv.id = pc.projeto_versao_id
        WHERE pv.projeto_id = :id AND c.status = true";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id',$projetoId);

        $stmt->execute();

        return Componente::map($stmt->fetchAll());
    }
    public function listarTodos(): array
    {
        $sql = "SELECT c.*, u.nome_usuario  FROM componente c
        JOIN usuario u
        ON u.id = c.usuario_id WHERE c.status=true";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return Componente::map($stmt->fetchAll());
    }
    public function desativar(Componente $componente): bool
    {
        $sql = "UPDATE componente
                SET 
                status = false
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $componente->getId());
        $stmt->execute();
        return true;
    }
    public function buscarPorVersao(int $projetoVersaoId): array
    {
        $sql = "SELECT c.*, pvc.quantidade
                FROM componente c
                JOIN projeto_versao_componente pvc ON pvc.componente_id = c.id
                WHERE pvc.projeto_versao_id = :versao_id AND c.status = true";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':versao_id', $projetoVersaoId, \PDO::PARAM_INT);
        $stmt->execute();
        return \app\models\Componente::map($stmt->fetchAll());
    }
}