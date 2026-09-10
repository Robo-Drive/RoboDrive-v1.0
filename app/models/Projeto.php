<?php

namespace app\models;

use app\models\Categoria;
use app\models\Equipe;
use DateTimeImmutable;

class Projeto
{
    private ?int $id;
    private ?string $nome;
    private ?string $descricao;
    private ?string $visibilidade;
    private ?Categoria $categoria;
    private ?Equipe $equipe;
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
            $projetoObj->setId($projeto["id"]??null);
            $projetoObj->setNome($projeto["nome"]??null);
            $projetoObj->setDescricao($projeto["descricao"]??null);
            $projetoObj->setVisibilidade($projeto["visibilidade"])??null;
            //$projetoObj->setCategoria(new Categoria()->setId($projeto["categoria_id"])->setNome($projeto["categoria_nome"])??null);
            //$projetoObj->setEquipe(new Equipe()->setId($projeto["equipe_id"])->setNome($projeto["equipe_nome"])??null);
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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }
    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }
    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }
}
