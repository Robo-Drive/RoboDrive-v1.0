<?php

namespace app\services;

use app\helpers\Validador;
use app\repositories\UserRepository;

class UsuarioService
{
    private UserRepository $repository;
    private Validador $validador;

    public function __construct()
    {
        $this->repository = new UserRepository();
        $this->validador = new Validador();
    }

    public function registrar(string $nome, string $email, string $senha, string $perfil = 'aluno'): array
    {
        $this->validador->limpar();

        $this->validador->obrigatorio('nome', $nome, 'Nome é obrigatório');
        $this->validador->minLength('nome', $nome, 3, 'Nome deve ter no mínimo 3 caracteres');
        $this->validador->maxLength('nome', $nome, 100, 'Nome deve ter no máximo 100 caracteres');

        $this->validador->obrigatorio('email', $email, 'Email é obrigatório');
        $this->validador->email('email', $email, 'Email inválido');

        $this->validador->obrigatorio('senha', $senha, 'Senha é obrigatória');
        $this->validador->minLength('senha', $senha, 6, 'Senha deve ter no mínimo 6 caracteres');

        if (!in_array($perfil, ['aluno', 'professor'], true)) {
            $this->validador->addErro('perfil', 'Perfil inválido');
        }

        if ($this->repository->emailJaExiste($email)) {
            $this->validador->addErro('email', 'Email já cadastrado');
        }

        if ($this->validador->temErros()) {
            return [
                'sucesso' => false,
                'erros' => $this->validador->getErros(),
            ];
        }

        try {
            $sql = "INSERT INTO usuarios (nome, email, senha, perfil)
                    VALUES (:nome, :email, :senha, :perfil)";

            $conn = \app\database\ConnectionFactory::getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', password_hash($senha, PASSWORD_BCRYPT));
            $stmt->bindValue(':perfil', $perfil);

            $resultado = $stmt->execute();

            return [
                'sucesso' => $resultado,
                'mensagem' => $resultado ? 'Usuário registrado com sucesso!' : 'Erro ao registrar',
            ];
        } catch (\Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao registrar: ' . $e->getMessage(),
                'erros' => ['geral' => 'Não foi possível concluir o cadastro.'],
            ];
        }
    }
}
