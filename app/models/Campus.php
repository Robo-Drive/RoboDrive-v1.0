<?php

namespace app\models;

class Campus
{
    private ?int $id;
    private ?string $nomeCampus;
    private ?string $nomeEquipe;
    private ?string $identidade;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNomeCampus(): ?string
    {
        return $this->nomeCampus;
    }
    public function setNomeCampus(?string $nomeCampus): self
    {
        $this->nomeCampus = $nomeCampus;
        return $this;
    }

    public function getNomeEquipe(): ?string
    {
        return $this->nomeEquipe;
    }
    public function setNomeEquipe(?string $nomeEquipe): self
    {
        $this->nomeEquipe = $nomeEquipe;
        return $this;
    }
    
    public function getIdentidade(): ?string
    {
        return $this->identidade;
    }
    public function setIdentidade(?string $identidade): self
    {
        $this->identidade = $identidade;
        return $this;
    }
}