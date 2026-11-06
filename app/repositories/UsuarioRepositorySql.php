<?php

namespace app\repositories;

use app\database\ConnectionFactory;
use app\models\Usuario;
use app\repositories\UsuarioRepositoryInterface;
use PDO;
use PDOException;

class UsuarioRepositorySql implements UsuarioRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = ConnectionFactory::getConnection();
    }

    public function cadastrar(Usuario $usuario): ?Usuario
    {
        try {
            $sql = "INSERT INTO usuario (nome, nome_usuario, email, senha, regra)
                    VALUES (:nome, :nome_usuario, :email, :senha, :regra)";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':nome_usuario', $usuario->getNomeUsuario());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->bindValue(':regra', 'usuario');

            if (!$stmt->execute()) {
                return null;
            }

            $usuario->setId((int) $this->connection->lastInsertId());

            return $usuario;
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar usuário: ' . $e->getMessage());
            return null;
        }
    }

    public function editar(Usuario $usuario): ?Usuario
    {
        try {
            $campos = [];
            $parametros = [];

            if ($usuario->getNome() !== null) {
                $campos[] = 'nome = :nome';
                $parametros[':nome'] = $usuario->getNome();
            }

            if ($usuario->getNomeUsuario() !== null) {
                $campos[] = 'nome_usuario = :nome_usuario';
                $parametros[':nome_usuario'] = $usuario->getNomeUsuario();
            }

            if ($usuario->getSenha() !== null && $usuario->getSenha() !== '') {
                $campos[] = 'senha = :senha';
                $parametros[':senha'] = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);
            }

            if ($usuario->getBiografia() !== null) {
                $campos[] = 'biografia = :biografia';
                $parametros[':biografia'] = $usuario->getBiografia();
            }

            if ($usuario->getImagem() !== null) {
                $campos[] = 'imagem = :imagem';
                $parametros[':imagem'] = $usuario->getImagem();
            }

            if ($usuario->getEmail() !== null) {
                $campos[] = 'email = :email';
                $parametros[':email'] = $usuario->getEmail();
            }

            if ($usuario->getRegra() !== null) {
                $campos[] = 'regra = :regra';
                $parametros[':regra'] = $usuario->getRegra();
            }

            // Não há nada para atualizar.
            if (empty($campos)) {
                return $usuario;
            }

            $sql = 'UPDATE usuario SET ' . implode(', ', $campos) . ' WHERE id = :id';

            $stmt = $this->connection->prepare($sql);

            foreach ($parametros as $chave => $valor) {
                $stmt->bindValue($chave, $valor);
            }

            $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

            return $stmt->execute() ? $usuario : null;
        } catch (PDOException $e) {
            error_log('Erro ao editar usuário: ' . $e->getMessage());
            return null;
        }
    }

    public function buscarId(Usuario $usuario): ?Usuario
    {
        $sql = 'SELECT * FROM usuario WHERE id = :id LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $resultado = Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado[0] ?? null;
    }

    public function buscarEmail(Usuario $usuario): ?Usuario
    {
        $sql = 'SELECT * FROM usuario WHERE email = :email LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->execute();

        $resultado = Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado[0] ?? null;
    }

    public function buscarProjeto(int $projetoId): array
    {
        $sql = "SELECT u.*, pu.papel, pu.papel AS papelProjeto
                FROM usuario u
                INNER JOIN projeto_usuario pu ON pu.usuario_id = u.id
                WHERE pu.projeto_id = :id
                  AND pu.status = 'ativo'";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $projetoId, PDO::PARAM_INT);
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function salvarTokenRecuperacao(int $usuarioId, string $token, string $expiraEm): bool
    {
        $sql = "INSERT INTO recuperacao_senha (usuario_id, token, expira_em)
                VALUES (:usuario_id, :token, :expira_em)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expira_em', $expiraEm);

        return $stmt->execute();
    }

    public function buscarTokenValido(string $token): ?array
    {
        $sql = "SELECT *
                FROM recuperacao_senha
                WHERE token = :token
                  AND utilizado = false
                  AND expira_em > NOW()
                LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public function ativar(Usuario $usuario): bool
    {
        $sql = 'UPDATE usuario SET status = true WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function redefinirSenhaComToken(string $token, string $novaSenha): bool
    {
        try {
            $this->connection->beginTransaction();

            // Bloqueia o token durante a operação para impedir reutilização concorrente.
            $sqlToken = "SELECT *
                         FROM recuperacao_senha
                         WHERE token = :token
                           AND utilizado = false
                           AND expira_em > NOW()
                         LIMIT 1
                         FOR UPDATE";

            $stmtToken = $this->connection->prepare($sqlToken);
            $stmtToken->bindValue(':token', $token);
            $stmtToken->execute();

            $tokenData = $stmtToken->fetch(PDO::FETCH_ASSOC);

            if (!$tokenData) {
                $this->connection->rollBack();
                return false;
            }

            $sqlSenha = 'UPDATE usuario SET senha = :senha WHERE id = :id';
            $stmtSenha = $this->connection->prepare($sqlSenha);
            $stmtSenha->bindValue(':senha', password_hash($novaSenha, PASSWORD_DEFAULT));
            $stmtSenha->bindValue(':id', $tokenData['usuario_id'], PDO::PARAM_INT);
            $stmtSenha->execute();

            if ($stmtSenha->rowCount() === 0) {
                // Verifica se o usuário existe, inclusive quando a senha já tinha o mesmo hash.
                $sqlExiste = 'SELECT id FROM usuario WHERE id = :id LIMIT 1';
                $stmtExiste = $this->connection->prepare($sqlExiste);
                $stmtExiste->bindValue(':id', $tokenData['usuario_id'], PDO::PARAM_INT);
                $stmtExiste->execute();

                if (!$stmtExiste->fetchColumn()) {
                    $this->connection->rollBack();
                    return false;
                }
            }

            $sqlMarcar = 'UPDATE recuperacao_senha SET utilizado = true WHERE id = :id';
            $stmtMarcar = $this->connection->prepare($sqlMarcar);
            $stmtMarcar->bindValue(':id', $tokenData['id'], PDO::PARAM_INT);
            $stmtMarcar->execute();

            if ($stmtMarcar->rowCount() !== 1) {
                $this->connection->rollBack();
                return false;
            }

            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            error_log('Erro ao redefinir senha: ' . $e->getMessage());
            return false;
        }
    }

    public function buscarNome(Usuario $usuario): ?array
    {
        $sql = 'SELECT * FROM usuario WHERE nome LIKE :nome';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome', '%' . $usuario->getNome() . '%');
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function buscarNomeUsuario(Usuario $usuario): ?array
    {
        $sql = 'SELECT * FROM usuario WHERE nome_usuario = :nome_usuario';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome_usuario', $usuario->getNomeUsuario());
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function listarTodos(): array
    {
        $sql = 'SELECT * FROM usuario';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function deletar(Usuario $usuario): bool
    {
        $sql = 'UPDATE usuario SET status = false WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function buscarEmailDiferenteId(Usuario $usuario): ?Usuario
    {
        $sql = 'SELECT * FROM usuario WHERE email = :email AND id != :id LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        $resultado = Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));

        return $resultado[0] ?? null;
    }

    public function buscarNomeUsuarioDiferenteId(Usuario $usuario): ?array
    {
        $sql = 'SELECT * FROM usuario WHERE nome_usuario = :nome_usuario AND id != :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nome_usuario', $usuario->getNomeUsuario());
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return Usuario::map($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
