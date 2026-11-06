<?php

namespace app\models;

use app\models\Categoria;
use app\models\Equipe;
use DateTimeImmutable;

class Projeto
{
    private ?int $id = null;
    private ?string $nome = null;
    private ?string $descricao = null;
    private ?string $visibilidade = null;
    private ?Categoria $categoria = null;
    private ?Equipe $equipe = null;
    private ?DateTimeImmutable $criadoEm = null;
    private ?string $hashAcesso = null;
    private ?string $senha = null;

    public static function map(?array $projetos): array
    {
        if ($projetos === null) {
            return [];
        }

        $projetosObj = [];

        foreach ($projetos as $projeto) {
            $projetoObj = new Projeto();

            $projetoObj->setId($projeto["id"] ?? null);
            $projetoObj->setNome($projeto["nome"] ?? null);
            $projetoObj->setDescricao($projeto["descricao"] ?? null);
            $projetoObj->setVisibilidade($projeto["visibilidade"] ?? null);
            
            // Adicionado o mapeamento do hash e da senha
            $projetoObj->setHashAcesso($projeto["hash_acesso"] ?? null);
            $projetoObj->setSenha($projeto["senha"] ?? null);

            // Categoria: somente cria o objeto se existir ID
            if (!empty($projeto["categoria_id"])) {
                $categoria = new Categoria();
                $categoria->setId($projeto["categoria_id"]);
                $categoria->setNome($projeto["categoria_nome"] ?? null);

                $projetoObj->setCategoria($categoria);
            } else {
                $projetoObj->setCategoria(null);
            }

            // Equipe: somente cria o objeto se existir ID
            if (!empty($projeto["equipe_id"])) {
                $equipe = new Equipe();
                $equipe->setId($projeto["equipe_id"]);
                $equipe->setNome($projeto["equipe_nome"] ?? null);

                $projetoObj->setEquipe($equipe);
            } else {
                $projetoObj->setEquipe(null);
            }

            $projetoObj->setCriadoEm(
                isset($projeto["criado_em"])
                    ? new DateTimeImmutable($projeto["criado_em"])
                    : null
            );

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

    public function getHashAcesso(): ?string
    {
        return $this->hashAcesso;
    }

    public function setHashAcesso(?string $hashAcesso): self
    {
        $this->hashAcesso = $hashAcesso;
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