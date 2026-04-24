<?php

namespace app\models;

class Componente
{
    private ?int $id;
    private ?string $nome;
    private ?string $descricao;
    private ?string $imagem;

    public static function map(?array $componentes): array
    {
        if($componentes  == null)
        {
            return [];
        }
        $ComponentesObj = array();
        foreach($componentes as $componente)
        {
            $componenteObj = new Componente();
            $componenteObj->setId($componente["id"]);
            $componenteObj->setNome($componente["nome"]);
            $componenteObj->setDescricao($componente["descricao"]);
            $componenteObj->setImagem($componente["imagem"]);
            $ComponentesObj[] = $componenteObj;
        }
        return $ComponentesObj;
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
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
}