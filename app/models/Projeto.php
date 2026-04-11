<?php

namespace app\models;

use DateTimeImmutable;

class Projeto
{
    private ?int $id;
    private ?string $nome;
    private ?string $campusId;
    private ?string $visibilidade;
    private ?DateTimeImmutable $criadoEm;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }
    public function setNome(?string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getCampusId(): ?string
    {
        return $this->campusId;
    }
    public function setCampusId(?string $campusId): self
    {
        $this->campusId = $campusId;
        return $this;
    }

    public function getVisibilidade(): ?string
    {
        return $this->visibilidade;
    }
    public function setVisibilidade(?string $visibilidade): self
    {
        $this->visibilidade = $visibilidade;
        return $this;
    }

    public function getCriadoEm(): ?DateTimeImmutable
    {
        return $this->criadoEm;
    }
    public function setCriadoEm(?DateTimeImmutable $criadoEm): self
    {
        $this->criadoEm = $criadoEm;
        return $this;
    }
}