<?php

namespace app\models;

class Categoria
{
    private ?int $id;
    private ?string $nome;
    private ?Usuario $usuario;
    private ?bool $status;

    
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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }
    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}