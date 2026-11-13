<?php

namespace app\models;

use DateTimeImmutable;

class Comentario
{
    private ?int $id = null;
    private ?string $conteudo = null;
    private ?int $origemId = null;
    private ?Usuario $usuario = null;
    private bool $status = true;
    private ?DateTimeImmutable $criadoEm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getOrigemId(): ?int
    {
        return $this->origemId;
    }

    public function setOrigemId(?int $origemId): self
    {
        $this->origemId = $origemId;
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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;
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