<?php

namespace app\models;

use DateTimeImmutable;

class Projeto
{
    private ?int $id;
    private ?string $nome;
    private ?string $descricao;
    private ?string $visibilidade;
    private ?DateTimeImmutable $criadoEm;
    private array $componentes;
    
    public static function map(?array $projetos): array
    {
        if ($projetos == null) {
            return [];
        }
        $projetosObj = array();
        foreach ($projetos as $projeto) {
            $projetoObj = new Projeto();
            $projetoObj->setId($projeto["id"]);
            $projetoObj->setNome($projeto["nome"]);
            $projetoObj->setDescricao($projeto["descricao"]);
            $projetoObj->setVisibilidade($projeto["visibilidade"]);
            $projetoObj->setCriadoEm(isset($projeto["criado_em"])
                ? new DateTimeImmutable($projeto["criado_em"])
                : null);
            $projetosObj[] = $projetoObj;
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getComponentes(): array
    {
        return $this->componentes;
    }
    public function setComponentes(array $componentes): self
    {
        $this->componentes = $componentes;
        return $this;
    }
}
