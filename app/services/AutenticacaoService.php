<?php

namespace app\services;

use app\models\User;
use app\repositories\UserRepository;

class AutenticacaoService
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function logar(string $email, string $senha): bool
    {
        $usuario = $this->repository->getUserByEmail($email);

        if (!$usuario) {
            return false;
        }

        $senhaValida = password_verify($senha, $usuario['senha']);

        if (!$senhaValida && hash_equals((string)$usuario['senha'], $senha)) {
            $senhaValida = true;
            $this->repository->updatePasswordHash((int)$usuario['id'], password_hash($senha, PASSWORD_BCRYPT));
            $usuario['senha'] = password_hash($senha, PASSWORD_BCRYPT);
        }

        if (!$senhaValida) {
            return false;
        }

        $user = new User(
            (int)$usuario['id'],
            $usuario['nome'],
            $usuario['email'],
            $usuario['senha'],
            $usuario['perfil']
        );

        $_SESSION['user_logado'] = $user;
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user_logado']);
        session_destroy();
    }
}
