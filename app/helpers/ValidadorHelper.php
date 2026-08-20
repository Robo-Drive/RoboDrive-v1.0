<?php

namespace app\helpers;

class ValidadorHelper
{
    private array $erros = [];

    public function obrigatorio(string $campo, mixed $valor, ?string $mensagem = null)
    {
        if(empty($valor) && $valor !== '0')
        {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} é obrigatório";
        }
        return $this;
    }
    public function tamanho(string $campo, mixed $valor, int $min, int $max, ?string $campo_mensgem = null)
    {
        $msg = $campo_mensgem ?? $campo;
        if (strlen($valor) < $min)
            $this->erros[$campo] = $mensagem ?? "O campo {$msg} pede no mínimo {$min} caracteres";
        if (strlen($valor) > $max)
            $this->erros[$campo] = $mensagem ?? "O campo {$msg} suporta no máximo {$max} caracteres";
        return $this;
    }
    public function email(string $email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->erros["email"] = "Email inválido";
        }
    }
    public function confirmarValor(string $valorInicial, string $valorConfirmado, string $campo, ?string $mensagem = null)
    {
        if($valorInicial != $valorConfirmado)
        {
            $this->erros[$campo] = $mensagem;
        }
    }
    public function temImagem(array $imagem,bool $erro=true)
    {
        if($imagem["imagem"]["error"] != 4)
        {
            return [
                "status" => true,
                "arquivo" => $imagem["imagem"],
            ];
        }
        if($erro)
        {
            $this->setErroImagem("Você deve adicionar uma imagem!");
        }
        return [
            "status" => false,
        ]; 
    }
    public function setErroImagem(string $mensagem)
    {
        $this->erros["imagem"] = $mensagem;
    }
    public function temErros(): bool
    {
        return !empty($this->erros);
    }
    public function getErros()
    {
        return $this->erros;
    }
}
