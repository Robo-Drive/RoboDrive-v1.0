<?php

namespace app\models;

use DateTimeImmutable;

class Projeto
{
    private ?int $id;
    private ?string $nome;
    private ?string $visibilidade;
    private ?DateTimeImmutable $criadoEm;

    public static function map(?array $projetos): array
    {
        if($projetos == null)
        {
            return [];
        }
        $projetosObj = array();
        foreach($projetos as $projeto)
        {
            $projetoObj = new Projeto();
            $projetoObj->setId($projeto["id"]);
            $projetoObj->setNome($projeto["nome"]);
            $projetoObj->setVisibilidade($projeto["visibilidade"]);
            $projetoObj->setCriadoEm($projeto["criado_em"]);
            $projetosObj[] = $projetosObj;
        }
        return $projetosObj;
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