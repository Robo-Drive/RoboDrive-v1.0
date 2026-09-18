<?php

namespace app\models;

use app\models\Usuario;

class Categoria
{
    private ?int $id;
    private ?string $nome;
    private ?Usuario $usuario;
    private ?bool $status;


    public static function map(array $categorias) :array
    {
        $categoriasObj = array();
        foreach($categorias as $categoria)
        {
            $categoriaObj = new Categoria();
            $categoriaObj->setId($categoria["id"]??null);
            $categoriaObj->setNome($categoria["nome"]??null);
            $categoriaObj->setUsuario(new Usuario()->setId($_SESSION["usuario_logado"]->getId()??null));
            $categoriaObj->setStatus($categoria["status"]??null);
            $categoriasObj[] = $categoriaObj;
        }
        return $categoriasObj;
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