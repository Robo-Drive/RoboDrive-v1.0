<?php

namespace app\models;

use DateTimeImmutable;

class Codigo
{
    private ?int $id;
    private ?string $caminho;
    private ?string $descricao;
    private ?int $projetoId;
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

    public function getCaminho(): ?string
    {
        return $this->caminho;
    }
    public function setCaminho(?string $caminho): self
    {
        $this->caminho = $caminho;
        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getProjetoId(): ?int
    {
        return $this->projetoId;
    }
    public function setProjetoId(?int $projetoId): self
    {
        $this->projetoId = $projetoId;
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