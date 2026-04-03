<?php

namespace app\models;

class ImagemProjeto
{
    private ?int $id;
    private ?string $caminho;
    private ?int $projetoId;

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

    public function getProjetoId(): ?int
    {
        return $this->projetoId;
    }
    public function setProjetoId(?int $projetoId): self
    {
        $this->projetoId = $projetoId;
        return $this;
    }
}