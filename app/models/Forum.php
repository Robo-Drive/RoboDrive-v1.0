<?php

namespace app\models;

use DateTimeImmutable;
use app\models\Usuario;
use app\models\Equipe;

class Forum
{
    private ?int $id;
    private ?string $conteudo;
    private ?string $visibilidade;
    private ?Usuario $usuario;
    private ?Equipe $equipe;
    private ?DateTimeImmutable $criadoEm;

    public static function map(?array $foruns): array
    {
        if ($foruns == null) {
            return [];
        }
        $forunsObj = array();
        foreach ($foruns as $forum) {
            $forumObj = new Forum();
            $forumObj->setId($forum["id"]??null);
            $forumObj->setConteudo($forum["conteudo"]??null);
            $forumObj->setVisibilidade($forum["visibilidade"]??null);
            $forumObj->setUsuario((new Usuario)->setId($forum["usuario_id"]??null)->setNomeUsuario($forum["nome_usuario"]));
            $forumObj->setEquipe(isset($forum["usuario_id"]) ? (new Equipe)->setId($forum["usuario_id"]??null)->setNome($forum["nome_equipe"]??null):null);
            $forumObj->setCriadoEm(isset($forum["criado_em"])
                ? new DateTimeImmutable($forum["criado_em"]??null)
                : null);
            $forunsObj[] = $forumObj;
        }
        return $forunsObj;
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

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }
    public function setConteudo(?string $conteudo): self
    {
        $this->conteudo = $conteudo;
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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
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