<?php

namespace app\models;

use DateTimeImmutable;

class User
{
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $perfil;
    private DateTimeImmutable $criadoEm;

    public function __construct(
        ?int $id = null,
        string $nome = '',
        string $email = '',
        string $senha = '',
        string $perfil = 'aluno',
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->perfil = $perfil;
        $this->criadoEm = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getPerfil(): string
    {
        return $this->perfil;
    }

    public function setPerfil(string $perfil): void
    {
        $this->perfil = $perfil;
    }

    public function isProfessor(): bool
    {
        return $this->perfil === 'professor';
    }

    public function isAluno(): bool
    {
        return $this->perfil === 'aluno';
    }

    public function getCriadoEm(): DateTimeImmutable
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(DateTimeImmutable $criadoEm): self
    {
        $this->criadoEm = $criadoEm;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'perfil' => $this->perfil,
            'criado_em' => $this->criadoEm,
        ];
    }

    public static function arrayToObject(array $user): self
    {
        return new self(
            $user['id'] ?? null,
            $user['nome'] ?? '',
            $user['email'] ?? '',
            $user['senha'] ?? '',
            $user['perfil'] ?? 'aluno'
        );
    }
}
