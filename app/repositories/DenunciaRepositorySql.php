<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use PDO;

class DenunciaRepositorySql
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function salvar(string $tipo, int $alvoId, int $usuarioId, string $motivo, string $descricao): bool
    {
        $sql = "INSERT INTO denuncia (tipo, alvo_id, usuario_id, motivo, descricao)
                VALUES (:tipo, :alvo_id, :usuario_id, :motivo, :descricao)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':alvo_id', $alvoId, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':motivo', $motivo);
        $stmt->bindValue(':descricao', $descricao);
        return $stmt->execute();
    }

    public function listarAgrupadas(): array
    {
        $sql = "SELECT d.tipo, d.alvo_id, COUNT(*) as quantidade, MIN(d.criado_em) as primeira_denuncia
                FROM denuncia d
                WHERE d.status = true
                GROUP BY d.tipo, d.alvo_id
                ORDER BY quantidade DESC, primeira_denuncia ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];

        foreach ($grupos as $g) {
            $tipo = $g['tipo'];
            $alvoId = (int)$g['alvo_id'];

            // Busca os motivos agrupados
            $sqlMotivos = "SELECT motivo, COUNT(*) as qtd FROM denuncia WHERE tipo = :tipo AND alvo_id = :alvo_id AND status = true GROUP BY motivo";
            $stmtMotivos = $this->connection->prepare($sqlMotivos);
            $stmtMotivos->bindValue(':tipo', $tipo);
            $stmtMotivos->bindValue(':alvo_id', $alvoId, PDO::PARAM_INT);
            $stmtMotivos->execute();
            $motivos = [];
            foreach ($stmtMotivos->fetchAll(PDO::FETCH_ASSOC) as $m) {
                $motivos[$m['motivo']] = (int)$m['qtd'];
            }

            // Identifica o alvo e seu responsável de acordo com o tipo
            $alvoNome = "Item #{$alvoId}";
            $respId = 1;
            $respNome = "Usuário";
            $respUser = "usuario";
            $respEmail = "";

            if ($tipo === 'projeto') {
                $stmtItem = $this->connection->prepare("SELECT p.nome, u.id as u_id, u.nome as u_nome, u.nome_usuario, u.email FROM projeto p JOIN usuario u ON u.id = p.usuario_id WHERE p.id = :id");
                $stmtItem->bindValue(':id', $alvoId, PDO::PARAM_INT);
                $stmtItem->execute();
                if ($item = $stmtItem->fetch(PDO::FETCH_ASSOC)) {
                    $alvoNome = $item['nome'];
                    $respId = $item['u_id'];
                    $respNome = $item['u_nome'];
                    $respUser = $item['nome_usuario'];
                    $respEmail = $item['email'];
                }
            } elseif ($tipo === 'forum') {
                $stmtItem = $this->connection->prepare("SELECT f.conteudo, u.id as u_id, u.nome as u_nome, u.nome_usuario, u.email FROM postagem_forum f JOIN usuario u ON u.id = f.usuario_id WHERE f.id = :id");
                $stmtItem->bindValue(':id', $alvoId, PDO::PARAM_INT);
                $stmtItem->execute();
                if ($item = $stmtItem->fetch(PDO::FETCH_ASSOC)) {
                    $alvoNome = mb_strimwidth($item['conteudo'], 0, 80, '...');
                    $respId = $item['u_id'];
                    $respNome = $item['u_nome'];
                    $respUser = $item['nome_usuario'];
                    $respEmail = $item['email'];
                }
            } elseif ($tipo === 'usuario') {
                $stmtItem = $this->connection->prepare("SELECT id, nome, nome_usuario, email FROM usuario WHERE id = :id");
                $stmtItem->bindValue(':id', $alvoId, PDO::PARAM_INT);
                $stmtItem->execute();
                if ($item = $stmtItem->fetch(PDO::FETCH_ASSOC)) {
                    $alvoNome = $item['nome'];
                    $respId = $item['id'];
                    $respNome = $item['nome'];
                    $respUser = $item['nome_usuario'];
                    $respEmail = $item['email'];
                }
            } elseif ($tipo === 'equipe') {
                $stmtItem = $this->connection->prepare("
                    SELECT e.nome, u.id as u_id, u.nome as u_nome, u.nome_usuario, u.email 
                    FROM equipe e 
                    LEFT JOIN equipe_usuario eu ON eu.equipe_id = e.id AND eu.papel = 'coordenador' AND eu.status = true
                    LEFT JOIN usuario u ON u.id = eu.usuario_id 
                    WHERE e.id = :id 
                    LIMIT 1
                ");
                $stmtItem->bindValue(':id', $alvoId, PDO::PARAM_INT);
                $stmtItem->execute();
                if ($item = $stmtItem->fetch(PDO::FETCH_ASSOC)) {
                    $alvoNome = $item['nome'];
                    if (!empty($item['u_id'])) {
                        $respId = $item['u_id'];
                        $respNome = $item['u_nome'];
                        $respUser = $item['nome_usuario'];
                        $respEmail = $item['email'] ?? '';
                    }
                }
            }

            $resultado[] = [
                "tipo"                => $tipo,
                "alvo_id"             => $alvoId,
                "alvo_nome"           => $alvoNome,
                "quantidade"          => (int)$g['quantidade'],
                "primeira_denuncia"   => $g['primeira_denuncia'],
                "motivos"             => $motivos,
                "responsavel_id"      => (int)$respId,
                "responsavel_nome"    => $respNome,
                "responsavel_usuario" => $respUser,
                "responsavel_email"   => $respEmail
            ];
        }

        return $resultado;
    }
    
    public function dispensar(string $tipo, int $alvoId): bool
    {
        $sql = "UPDATE denuncia SET status = false WHERE tipo = :tipo AND alvo_id = :alvo_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':alvo_id', $alvoId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}