<?php

namespace app\models;

use DateTimeImmutable;

class Usuario
{
    private ?int $id;
    private ?string $nome;
    private ?string $email;
    private ?string $senha;
    private ?string $biografia;
    private ?string $imagem;
    private ?string $regra;
    private ?string $tipo;
    private ?string $categoria;
    private ?DateTimeImmutable $criadoEm;

    public static function map(?array $usuarios): array
    {
        if ($usuarios == null) {
            return [];
        }
        $usuariosObj = array();
        foreach ($usuarios as $usuario) {
            $usuarioObj = new Usuario();
            $usuarioObj->setId($usuario["id"] ?? null);
            $usuarioObj->setNome($usuario["nome"] ?? null);
            $usuarioObj->setEmail($usuario["email"] ?? null);
            $usuarioObj->setSenha($usuario["senha"] ?? null);
            $usuarioObj->setBiografia($usuario["biografia"] ?? null);
            $usuarioObj->setImagem($usuario["imagem"] ?? null);
            $usuarioObj->setRegra($usuario["regra"] ?? null);
            $usuarioObj->setTipo($usuario["tipo"] ?? null);
            $usuarioObj->setCategoria($usuario["categoria"] ?? null);
            $usuarioObj->setCriadoEm(isset($usuario["criado_em"])
                ? new DateTimeImmutable($usuario["criado_em"])
                : null);
            $usuariosObj[] = $usuarioObj;
        }
        return $usuariosObj;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): self
    {
        $this->email = $email;
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

    public function getImagem(): ?string
    {
        return $this->imagem;
    }
    public function setImagem(?string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    public function getRegra(): ?string
    {
        return $this->regra;
    }
    public function setRegra(?string $regra): self
    {
        $this->regra = $regra;
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

    public function getBiografia(): ?string
    {
        return $this->biografia;
    }
    public function setBiografia(?string $biografia): self
    {
        $this->biografia = $biografia;
        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }
    public function setCategoria(?string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
