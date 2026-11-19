<?php

namespace app\models;

use DateTimeImmutable;

class Extra
{
    private ?int $id = null;
    private ?string $caminho = null;
    private ?string $descricao = null;
    private ?DateTimeImmutable $criadoEm = null;
    private bool $status = true;

    public static function map(?array $extras): array
    {
        if ($extras == null)
        {
            return [];
        }    
        $extrasObj = [];
        foreach($extras as $extra)
        {
            $extraObj = new Extra();
            $extraObj->setId($extra['id'] ?? null);
            $extraObj->setCaminho($extra['caminho'] ?? null);
            $extraObj->setDescricao($extra['descricao'] ?? null);
            $extraObj->setStatus((bool)($extra['status'] ?? true));
            $extraObj->setCriadoEm(isset($extra["criado_em"])? new DateTimeImmutable($extra["criado_em"] ?? null) : null);
            $extrasObj[] = $extraObj;
        }
        return $extrasObj;
    }

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
        $this->descricao = $descricao; return $this;
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

    public function getStatus(): bool 
    { 
        return $this->status; 
    }
    public function setStatus(bool $status): self 
    { 
        $this->status = $status; 
        return $this; 
    }
}