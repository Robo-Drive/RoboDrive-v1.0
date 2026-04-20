<?php

namespace app\helpers;

class Validador
{
    private array $erros = [];

    public function obrigatorio(string $campo, mixed $valor, ?string $mensagem = null): self
    {
        if (empty($valor) && $valor !== '0') {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} é obrigatório";
        }

        return $this;
    }

    public function minLength(string $campo, mixed $valor, int $minLength, ?string $mensagem = null): self
    {
        if (!empty($valor) && strlen((string)$valor) < $minLength) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ter no mínimo {$minLength} caracteres";
        }

        return $this;
    }

    public function maxLength(string $campo, mixed $valor, int $maxLength, ?string $mensagem = null): self
    {
        if (!empty($valor) && strlen((string)$valor) > $maxLength) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ter no máximo {$maxLength} caracteres";
        }

        return $this;
    }

    public function email(string $campo, mixed $valor, ?string $mensagem = null): self
    {
        if (!empty($valor) && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ser um email válido";
        }

        return $this;
    }

    public function unico(string $campo, mixed $valor, callable $verificador, ?string $mensagem = null): self
    {
        if (!empty($valor) && $verificador($valor)) {
            $this->erros[$campo] = $mensagem ?? "O valor de {$campo} já existe";
        }

        return $this;
    }

    public function min(string $campo, mixed $valor, float $min, ?string $mensagem = null): self
    {
        if (!empty($valor) && (float)$valor < $min) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ser no mínimo {$min}";
        }

        return $this;
    }

    public function max(string $campo, mixed $valor, float $max, ?string $mensagem = null): self
    {
        if (!empty($valor) && (float)$valor > $max) {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} deve ser no máximo {$max}";
        }

        return $this;
    }

    public function addErro(string $campo, string $mensagem): self
    {
        $this->erros[$campo] = $mensagem;
        return $this;
    }

    public function temErros(): bool
    {
        return !empty($this->erros);
    }

    public function getErros(): array
    {
        return $this->erros;
    }

    public function limpar(): self
    {
        $this->erros = [];
        return $this;
    }
}
