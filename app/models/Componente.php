<?php

namespace app\models;

use app\models\Usuario;

class Componente
{
    private ?int $id;
    private ?string $nome;
    private ?string $descricao;
    private ?string $imagem;
    private ?int $quantidade;
    private ?Usuario $usuario;

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
            $componenteObj->setId($componente["id"]??null);
            $componenteObj->setNome($componente["nome"]??null);
            $componenteObj->setDescricao($componente["descricao"]??null);
            $componenteObj->setImagem($componente["imagem"]??null);
            $componenteObj->setQuantidade($componente["quantidade"]??null);
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


    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }
    public function setQuantidade(?int $quantidade): self
    {
        $this->quantidade = $quantidade;
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
}