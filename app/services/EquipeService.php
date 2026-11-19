<?php

namespace app\services;

use app\models\Equipe;
use app\repositories\EquipeRepositorySql;
use Throwable;

class EquipeService
{
    private EquipeRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new EquipeRepositorySql();
    }

    // Cadastrar equipe
    public function salvarEquipe(Equipe $equipe): ?Equipe
    {
        try
        {
            return $this->repositorySql->cadastrar($equipe);
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return null;
        }
    }

    // Editar equipe
    public function editarEquipe(Equipe $equipe): ?Equipe
    {
        try
        {
            return $this->repositorySql->editar($equipe);
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return null;
        }
    }

    // Validar senha da equipe
    public function validarSenhaEquipe(Equipe $equipe, string $senha): bool
    {
        return password_verify($senha, $equipe->getSenha());
    }

    // Solicitar entrada em uma equipe
    public function solicitarEntradaEquipe(int $equipeId, int $usuarioId): bool
    {
        try
        {
            return $this->repositorySql->solicitarEntrada($equipeId, $usuarioId);
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return false;
        }
    }

    // Aprovar solicitação de entrada
    public function aprovarSolicitacao(int $equipeId, int $usuarioId): bool
    {
        try
        {
            return $this->repositorySql->aprovarSolicitacao($equipeId, $usuarioId);
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return false;
        }
    }

    // Rejeitar solicitação de entrada
    public function rejeitarSolicitacao(int $equipeId, int $usuarioId): bool
    {
        try
        {
            return $this->repositorySql->rejeitarSolicitacao($equipeId, $usuarioId);
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return false;
        }
    }
}
