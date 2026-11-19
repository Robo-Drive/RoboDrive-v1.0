<?php

namespace app\models;

use ArrayAccess;

class Denuncia implements ArrayAccess
{
    private ?int $id = null;
    private ?string $tipo = null;
    private ?int $alvoId = null;
    private ?string $alvoNome = null;
    private ?int $usuarioId = null;
    private ?string $motivo = null;
    private ?string $descricao = null;
    private ?int $quantidade = null;
    private ?string $primeiraDenuncia = null;
    private array $motivos = [];
    private ?int $responsavelId = null;
    private ?string $responsavelNome = null;
    private ?string $responsavelUsuario = null;
    private ?string $responsavelEmail = null;
    private ?string $criadoEm = null;
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getAlvoId(): ?int
    {
        return $this->alvoId;
    }

    public function setAlvoId(?int $alvoId): void
    {
        $this->alvoId = $alvoId;
    }

    public function getAlvoNome(): ?string
    {
        return $this->alvoNome;
    }

    public function setAlvoNome(?string $alvoNome): void
    {
        $this->alvoNome = $alvoNome;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(?int $usuarioId): void
    {
        $this->usuarioId = $usuarioId;
    }

    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    public function setMotivo(?string $motivo): void
    {
        $this->motivo = $motivo;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(?int $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    public function getPrimeiraDenuncia(): ?string
    {
        return $this->primeiraDenuncia;
    }

    public function setPrimeiraDenuncia(?string $primeiraDenuncia): void
    {
        $this->primeiraDenuncia = $primeiraDenuncia;
    }

    public function getMotivos(): array
    {
        return $this->motivos;
    }

    public function setMotivos(array $motivos): void
    {
        $this->motivos = $motivos;
    }

    public function getResponsavelId(): ?int
    {
        return $this->responsavelId;
    }

    public function setResponsavelId(?int $responsavelId): void
    {
        $this->responsavelId = $responsavelId;
    }

    public function getResponsavelNome(): ?string
    {
        return $this->responsavelNome;
    }

    public function setResponsavelNome(?string $responsavelNome): void
    {
        $this->responsavelNome = $responsavelNome;
    }

    public function getResponsavelUsuario(): ?string
    {
        return $this->responsavelUsuario;
    }

    public function setResponsavelUsuario(?string $responsavelUsuario): void
    {
        $this->responsavelUsuario = $responsavelUsuario;
    }

    public function getResponsavelEmail(): ?string
    {
        return $this->responsavelEmail;
    }

    public function setResponsavelEmail(?string $responsavelEmail): void
    {
        $this->responsavelEmail = $responsavelEmail;
    }

    public function getCriadoEm(): ?string
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(?string $criadoEm): void
    {
        $this->criadoEm = $criadoEm;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    public function offsetExists(mixed $offset): bool
    {
        return in_array($offset, [
            "id", "tipo", "alvo_id", "alvo_nome", "usuario_id",
            "motivo", "descricao", "quantidade", "primeira_denuncia",
            "motivos", "responsavel_id", "responsavel_nome",
            "responsavel_usuario", "responsavel_email", "criado_em", "status"
        ], true);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset)
        {
            "id" => $this->id,
            "tipo" => $this->tipo,
            "alvo_id" => $this->alvoId,
            "alvo_nome" => $this->alvoNome,
            "usuario_id" => $this->usuarioId,
            "motivo" => $this->motivo,
            "descricao" => $this->descricao,
            "quantidade" => $this->quantidade,
            "primeira_denuncia" => $this->primeiraDenuncia,
            "motivos" => $this->motivos,
            "responsavel_id" => $this->responsavelId,
            "responsavel_nome" => $this->responsavelNome,
            "responsavel_usuario" => $this->responsavelUsuario,
            "responsavel_email" => $this->responsavelEmail,
            "criado_em" => $this->criadoEm,
            "status" => $this->status,
            default => null,
        };
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        match ($offset)
        {
            "id" => $this->setId($value),
            "tipo" => $this->setTipo($value),
            "alvo_id" => $this->setAlvoId($value),
            "alvo_nome" => $this->setAlvoNome($value),
            "usuario_id" => $this->setUsuarioId($value),
            "motivo" => $this->setMotivo($value),
            "descricao" => $this->setDescricao($value),
            "quantidade" => $this->setQuantidade($value),
            "primeira_denuncia" => $this->setPrimeiraDenuncia($value),
            "motivos" => $this->setMotivos($value),
            "responsavel_id" => $this->setResponsavelId($value),
            "responsavel_nome" => $this->setResponsavelNome($value),
            "responsavel_usuario" => $this->setResponsavelUsuario($value),
            "responsavel_email" => $this->setResponsavelEmail($value),
            "criado_em" => $this->setCriadoEm($value),
            "status" => $this->setStatus($value),
            default => null,
        };
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->offsetSet($offset, null);
    }

    public static function map(array $dados): array
    {
        $denuncias = [];
        foreach ($dados as $item)
        {
            $denuncia = new self();
            $denuncia->setId(isset($item["id"]) ? (int)$item["id"] : null);
            $denuncia->setTipo($item["tipo"] ?? null);
            $denuncia->setAlvoId(isset($item["alvo_id"]) ? (int)$item["alvo_id"] : null);
            $denuncia->setAlvoNome($item["alvo_nome"] ?? null);
            $denuncia->setUsuarioId(isset($item["usuario_id"]) ? (int)$item["usuario_id"] : null);
            $denuncia->setMotivo($item["motivo"] ?? null);
            $denuncia->setDescricao($item["descricao"] ?? null);
            $denuncia->setQuantidade(isset($item["quantidade"]) ? (int)$item["quantidade"] : null);
            $denuncia->setPrimeiraDenuncia($item["primeira_denuncia"] ?? null);
            $denuncia->setMotivos($item["motivos"] ?? []);
            $denuncia->setResponsavelId(isset($item["responsavel_id"]) ? (int)$item["responsavel_id"] : null);
            $denuncia->setResponsavelNome($item["responsavel_nome"] ?? null);
            $denuncia->setResponsavelUsuario($item["responsavel_usuario"] ?? null);
            $denuncia->setResponsavelEmail($item["responsavel_email"] ?? null);
            $denuncia->setCriadoEm($item["criado_em"] ?? null);
            $denuncia->setStatus(isset($item["status"]) ? (bool)$item["status"] : null);

            $denuncias[] = $denuncia;
        }
        return $denuncias;
    }
}
