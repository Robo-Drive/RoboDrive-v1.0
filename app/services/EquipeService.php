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

    public function validarSenhaEquipe(Equipe $equipe, string $senha): bool
    {
        return password_verify($senha, $equipe->getSenha());
    }

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

    public function alterarPapel(int $equipeId, int $usuarioId, string $papel): array
    {
        try
        {
            $membros = $this->repositorySql->listarMembros($equipeId);
            $totalCoordenadores = 0;
            $papelAtual = null;

            foreach ($membros as $m)
            {
                if ($m["papel"] === "coordenador")
                {
                    $totalCoordenadores++;
                }

                if ((int)$m["id"] === $usuarioId)
                {
                    $papelAtual = $m["papel"];
                }
            }

            if ($papelAtual === null)
            {
                return ["resposta" => false, "erro" => "Integrante não encontrado na equipe.", "status" => 404];
            }

            if ($papelAtual === "coordenador" && $papel !== "coordenador" && $totalCoordenadores <= 1)
            {
                return ["resposta" => false, "erro" => "A equipe não pode ficar sem nenhum coordenador.", "status" => 422];
            }

            if (!$this->repositorySql->alterarPapelMembro($equipeId, $usuarioId, $papel))
            {
                return ["resposta" => false, "erro" => "Não foi possível alterar o papel do integrante.", "status" => 500];
            }

            return ["resposta" => true];
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return ["resposta" => false, "erro" => "Erro ao alterar o papel do integrante.", "status" => 500];
        }
    }

    public function adicionarMembro(int $equipeId, int $usuarioId, string $papel): array
    {
        try
        {
            if (!$this->repositorySql->adicionarMembroDireto($equipeId, $usuarioId, $papel))
            {
                return ["resposta" => false, "erro" => "Não foi possível adicionar o membro.", "status" => 500];
            }

            return ["resposta" => true];
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return ["resposta" => false, "erro" => "Erro ao adicionar membro.", "status" => 500];
        }
    }

    public function removerMembro(int $equipeId, int $usuarioAlvoId, ?int $novoCoordenadorId): array
    {
        try
        {
            $membros = $this->repositorySql->listarMembros($equipeId);
            $totalCoordenadores = 0;
            $alvoEraCoordenador = false;
            $alvoEncontrado = false;

            foreach ($membros as $m)
            {
                if ($m["papel"] === "coordenador")
                {
                    $totalCoordenadores++;
                }

                if ((int)$m["id"] === $usuarioAlvoId)
                {
                    $alvoEncontrado = true;
                    $alvoEraCoordenador = ($m["papel"] === "coordenador");
                }
            }

            if (!$alvoEncontrado)
            {
                return ["resposta" => false, "erro" => "Integrante não encontrado na equipe.", "status" => 404];
            }

            if (count($membros) <= 1)
            {
                return ["resposta" => false, "erro" => "A equipe deve ter pelo menos um integrante.", "status" => 422];
            }

            if ($alvoEraCoordenador && $totalCoordenadores <= 1)
            {
                if (!$novoCoordenadorId || $novoCoordenadorId === $usuarioAlvoId)
                {
                    return [
                        "resposta" => false,
                        "erro" => "Você é o único coordenador da equipe. Selecione outro membro para assumir a coordenação antes de sair.",
                        "status" => 422
                    ];
                }

                $this->repositorySql->alterarPapelMembro($equipeId, $novoCoordenadorId, "coordenador");
            }

            if (!$this->repositorySql->removerMembro($equipeId, $usuarioAlvoId))
            {
                return ["resposta" => false, "erro" => "Não foi possível remover o membro.", "status" => 500];
            }

            return ["resposta" => true];
        }
        catch (Throwable $e)
        {
            error_log($e->getMessage());

            return ["resposta" => false, "erro" => "Erro ao remover integrante.", "status" => 500];
        }
    }
}
