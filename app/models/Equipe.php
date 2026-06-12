<?php

namespace app\models;

class Equipe
{
    private ?int $id;
    private ?string $nome;
    private ?string $senha;

    public static function map(?array $equipes): array
    {
        if($equipes == null)
        {
            return [];
        }
        $equipesObj = array();
        foreach($equipes as $equipe)
        {
            $equipeObj = new Equipe();
            $equipeObj->setId($equipe["id"]??null);
            $equipeObj->setNome($equipe["nome"]??null);
            $equipeObj->setSenha($equipe["senha"]??null);
            $equipesObj[] = $equipeObj;
        }
        return $equipesObj;
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

    public function getNome(): ?string
    {
        return $this->nome;
    }
    public function setNome(?string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }
    
    public function getSenha(): ?string
    {
        return $this->senha;
    }
    public function setSenha(?string $senha): self
    {
        $this->senha = $senha;
        return $this;
    }
}