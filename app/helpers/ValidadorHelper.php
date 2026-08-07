<?php

namespace app\helpers;

class ValidadorHelper
{
    private array $erros = [];

    public function obrigatorio(string $campo,mixed $valor,?string $mensagem=null)
    {
        if(empty($valor) && $valor !== '0')
        {
            $nomeCampo = str_replace('_', ' ', $campo);
            $this->erros[$campo] = $mensagem ?? "O campo {$nomeCampo} é obrigatório";
        }
        return $this;
    }
    public function tamanho(string $campo,mixed $valor,int $min,int $max)
    {
        $nomeCampo = str_replace('_', ' ', $campo);
        if(strlen($valor) < $min)
            $this->erros[$campo] = "O campo {$nomeCampo} pede no mínimo {$min} caracteres";
        if(strlen($valor) > $max)
            $this->erros[$campo] = "O campo {$nomeCampo} suporta no máximo {$max} caracteres";
        return $this;
    }
    public function email(string $email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->erros["email"] = "Email inválido";
        }
    }
    public function confirmarValor(string $valorInicial,string $valorConfirmado,string $campo,?string $mensagem=null)
    {
        if($valorInicial != $valorConfirmado)
        {
            $this->erros[$campo] = $mensagem;
        }
    }
    public function temErros() :bool 
    {
        return !empty($this->erros);
    }
    public function getErros()
    {
        return $this->erros;
    }
}