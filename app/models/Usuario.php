<?php

namespace app\models;

use DateTimeImmutable;
class Usuario
{
    private ?int $id;
    private ?string $name;
    private ?string $email;
    private ?string $password;
    private ?string $imagem;
    private ?string $regra;
    private ?int $campusId;
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

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getImagem(): ?string
    {
        return $this->imagem;
    }
    public function setImagem(?string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    public function getRegra(): ?string
    {
        return $this->regra;
    }
    public function setRegra(?string $regra): self
    {
        $this->regra = $regra;
        return $this;
    }

    public function getCampusId(): ?int
    {
        return $this->campusId;
    }
    public function setCampusId(?int $campusId): self
    {
        $this->campusId = $campusId;
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