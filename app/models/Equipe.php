<?php

namespace app\models;

use DateTimeImmutable;

class Equipe
{
    private ?int $id;
    private string $nomeEquipe;
    private string $senha;
    private int $professorId;
    private DateTimeImmutable $criadoEm;

    public function __construct(
        ?int $id = null,
        string $nomeEquipe = '',
        string $senha = '',
        int $professorId = 0,
    ) {
        $this->id = $id;
        $this->nomeEquipe = $nomeEquipe;
        $this->senha = $senha;
        $this->professorId = $professorId;
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

    public function getNomeEquipe(): string
    {
        return $this->nomeEquipe;
    }

    public function setNomeEquipe(string $nomeEquipe): void
    {
        $this->nomeEquipe = $nomeEquipe;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getProfessorId(): int
    {
        return $this->professorId;
    }

    public function setProfessorId(int $professorId): void
    {
        $this->professorId = $professorId;
    }

    public function validarSenhaEquipe(string $senhaInformada): bool
    {
        return $this->senha === $senhaInformada;
    }

    /**
     * Get the value of criadoEm
     */
    public function getCriadoEm(): DateTimeImmutable
    {
        return $this->criadoEm;
    }

    /**
     * Set the value of criadoEm
     */
    public function setCriadoEm(DateTimeImmutable $criadoEm): self
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome_equipe' => $this->nomeEquipe,
            'senha' => $this->senha,
            'professor_id' => $this->professorId,
            'criado_em' => $this->criadoEm
        ];
    }

}