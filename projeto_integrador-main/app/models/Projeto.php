<?php 

namespace app\models;

use DateTimeImmutable;

class Projeto {

    private int $id;
    private string $nome;
    private string $visibilidade;
    private DateTimeImmutable $criadoEm;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nome
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     */
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of visibilidade
     */
    public function getVisibilidade(): string
    {
        return $this->visibilidade;
    }

    /**
     * Set the value of visibilidade
     */
    public function setVisibilidade(string $visibilidade): self
    {
        $this->visibilidade = $visibilidade;

        return $this;
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
}