<?php

namespace app\services;

use app\helpers\Validador;
use app\models\Equipe;
use app\repositories\EquipeRepository;

class EquipeService
{
    private EquipeRepository $repository;
    private Validador $validador;

    public function __construct()
    {
        $this->repository = new EquipeRepository();
        $this->validador = new Validador();
    }

    /**
     * Obter todas as equipes
     */
    public function getEquipes(): array
    {
        return $this->repository->getEquipes();
    }

    /**
     * Obter equipe por ID
     */
    public function getEquipeById(int $id): ?array
    {
        return $this->repository->getEquipeById($id);
    }

    /**
     * Validar e salvar nova equipe
     */
    public function saveEquipe(string $nomeEquipe, string $senha, int $professorId): array
    {
        $this->validador->limpar();

        // Validações
        $this->validador->obrigatorio('nome', $nomeEquipe, 'Nome da equipe é obrigatório');
        $this->validador->minLength('nome', $nomeEquipe, 3, 'Nome deve ter no mínimo 3 caracteres');
        $this->validador->maxLength('nome', $nomeEquipe, 100, 'Nome deve ter no máximo 100 caracteres');

        $this->validador->obrigatorio('senha', $senha, 'Senha é obrigatória');
        $this->validador->minLength('senha', $senha, 6, 'Senha deve ter no mínimo 6 caracteres');

        // Regra de negócio: Verificar se nome já existe
        if ($this->repository->nomeEquipeJaExiste($nomeEquipe)) {
            $this->validador->addErro('nome', 'Nome de equipe já cadastrado');
        }

        if ($this->validador->temErros()) {
            return [
                'sucesso' => false,
                'erros' => $this->validador->getErros()
            ];
        }

        $equipe = new Equipe(
            null,
            $nomeEquipe,
            $senha,
            $professorId
        );

        $resultado = $this->repository->saveEquipe($equipe);

        return [
            'sucesso' => $resultado,
            'mensagem' => $resultado ? 'Equipe criada com sucesso' : 'Erro ao criar equipe'
        ];
    }

    /**
     * Validar e atualizar equipe
     */
    public function updateEquipe(int $id, string $nomeEquipe, string $senha): array
    {
        $this->validador->limpar();

        // Validações
        $this->validador->obrigatorio('nome', $nomeEquipe, 'Nome da equipe é obrigatório');
        $this->validador->minLength('nome', $nomeEquipe, 3, 'Nome deve ter no mínimo 3 caracteres');
        $this->validador->maxLength('nome', $nomeEquipe, 100, 'Nome deve ter no máximo 100 caracteres');

        $this->validador->obrigatorio('senha', $senha, 'Senha é obrigatória');
        $this->validador->minLength('senha', $senha, 6, 'Senha deve ter no mínimo 6 caracteres');

        // Regra de negócio: Verificar se nome já existe (excluindo o atual)
        if ($this->repository->nomeEquipeJaExiste($nomeEquipe, $id)) {
            $this->validador->addErro('nome', 'Nome de equipe já cadastrado');
        }

        if ($this->validador->temErros()) {
            return [
                'sucesso' => false,
                'erros' => $this->validador->getErros()
            ];
        }

        $equipe = new Equipe(
            $id,
            $nomeEquipe,
            $senha
        );

        $resultado = $this->repository->updateEquipe($equipe);

        return [
            'sucesso' => $resultado,
            'mensagem' => $resultado ? 'Equipe atualizada com sucesso' : 'Erro ao atualizar equipe'
        ];
    }

    /**
     * Deletar equipe (com regra de negócio)
     */
    public function deleteEquipe(int $id): array
    {
        // Regra de negócio: Verificar se há membros antes de deletar
        $membros = $this->repository->countMembrosEquipe($id);

        if ($membros > 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'Não é possível deletar uma equipe com membros'
            ];
        }

        $resultado = $this->repository->deleteEquipe($id);

        return [
            'sucesso' => $resultado,
            'mensagem' => $resultado ? 'Equipe deletada com sucesso' : 'Erro ao deletar equipe'
        ];
    }
}
