<?php

namespace app\models;

use DateTimeImmutable;

class Imagem
{
    private ?int $id;
    private ?string $caminho;
    private ?int $projetoId;
    private ?DateTimeImmutable $criadoEm;

    public static function map(?array $codigos): array
    {
        if($codigos  == null)
        {
            return [];
        }
        $imagensObj = array();
        foreach($codigos as $imagem)
        {
            $imagemObj = new Imagem();
            $imagemObj->setId($imagem["id"]);
            $imagemObj->setCaminho($imagem["caminho"]);
            $imagemObj->setCriadoEm(isset($projeto["criado_em"])
            ? new DateTimeImmutable($projeto["criado_em"])
            : null);
            $imagensObj[] = $imagemObj;
        }
        return $imagensObj;
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

    public function getProjetoId(): ?int
    {
        return $this->projetoId;
    }
    public function setProjetoId(?int $projetoId): self
    {
        $this->projetoId = $projetoId;
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