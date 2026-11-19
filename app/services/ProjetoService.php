<?php

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepositorySql;
use Exception;

class ProjetoService
{
    private ProjetoRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new ProjetoRepositorySql();
    }

    public function salvarProjeto(array $posts): array
    {
        if ($this->repositorySql->existeNome(trim($posts["nome"] ?? ""))) 
        {
            throw new Exception("Já existe um projeto cadastrado com esse nome.");
        }

        // RN13: Visibilidade de equipe requer equipe vinculada
        if (($posts["visibilidade"] ?? "") === "equipe" && empty($posts["equipe_id"])) 
        {
            throw new Exception("A visibilidade para equipe requer vínculo com uma equipe.");
        }

        $projeto = Projeto::map([$posts])[0];
        return $this->repositorySql->cadastrar($projeto);
    }

    public function editarProjeto(Projeto $projeto): bool
    {
        if ($this->repositorySql->existeNome($projeto->getNome(), $projeto->getId())) 
        {
            throw new Exception("Já existe um projeto cadastrado com esse nome.");
        }

        $this->repositorySql->editar($projeto);
        return true;
    }

    public function validarSaidaCoordenador($projetoId, $usuarioId): bool
    {
        $membros = $this->repositorySql->listarMembros($projetoId);
        $coordenadores = array_filter($membros, fn($m) => $m["papel"] === "coordenador");

        foreach ($membros as $m) 
        {
            if ($m["id"] == $usuarioId && $m["papel"] === "coordenador" && count($coordenadores) <= 1) 
            {
                // RN25: Não pode sair se for o último coordenador
                return false;
            }
        }

        return true;
    }
}