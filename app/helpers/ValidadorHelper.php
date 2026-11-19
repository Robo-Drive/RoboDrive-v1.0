<?php

namespace app\helpers;

class ValidadorHelper
{
    private array $erros = [];

        // Extensões específicas por finalidade
    private array $extensoesImagem = ["jpg", "jpeg", "png", "gif", "svg", "webp"];
    private array $extensoesCodigo = ["ino", "py", "cpp", "c", "h", "java", "js", "html", "css", "json", "xml", "yaml", "yml"];
    private array $extensoesExtra  = [
        // Documentos / Manuais
        "pdf", "doc", "docx", "txt", "md", "odt",
        // Planilhas
        "xls", "xlsx", "csv", "ods",
        // Apresentações
        "ppt", "pptx", "odp",
        // 3D / CAD / Modelagem
        "stl", "obj", "step", "stp",
        // Compactados
        "zip", "rar", "7z", "tar", "gz"
    ];


    private int $tamanhoMaximo = 5242880; // 5MB

    public function obrigatorio(string $campo, mixed $valor, ?string $mensagem = null)
    {
        if (empty($valor) && $valor !== '0')
        {
            $this->erros[$campo] = $mensagem ?? "O campo {$campo} é obrigatório";
        }

        return $this;
    }

    public function componenteQuantidade(int $quantidade)
    {
        if($quantidade < 1)
        {
            $this->erros["componentes"] = "Não pode ter quantidade nula ou negativa negativa de componentes";
        }
    }

    public function tamanho(string $campo, mixed $valor, int $min, int $max, ?string $campo_mensgem = null)
    {
        $msg = $campo_mensgem ?? $campo;

        if (strlen($valor) < $min)
        {
            $this->erros[$campo] = "O campo {$msg} pede no mínimo {$min} caracteres";
        }

        if (strlen($valor) > $max)
        {
            $this->erros[$campo] = "O campo {$msg} suporta no máximo {$max} caracteres";
        }

        return $this;
    }

    public function email(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->erros["email"] = "Email inválido";
        }
    }

    public function confirmarValor(string $valorInicial, string $valorConfirmado, string $campo, ?string $mensagem = null)
    {
        if ($valorInicial != $valorConfirmado)
        {
            $this->erros[$campo] = $mensagem;
        }
    }
    public function visibilidadeValida(string $visibilidade)
    {
        if(!in_array($visibilidade,['privado', 'publico', 'equipe'],true))
        {
            $this->erros["visibilidade"] = "Selecione uma visibilidade válida";
        }
    }
    
    public function validarImagens(string $campo, array $arquivos)
    {
        return $this->validarGrupoArquivo($campo, $arquivos, $this->extensoesImagem, "imagem");
    }

    public function validarCodigos(string $campo, array $arquivos)
    {
        return $this->validarGrupoArquivo($campo, $arquivos, $this->extensoesCodigo, "código");
    }

    public function validarExtras(string $campo, array $arquivos)
    {
        return $this->validarGrupoArquivo($campo, $arquivos, $this->extensoesExtra, "arquivo extra/documentação");
    }

    public function validarVisibilidade(string $campo, $visibilidade, $equipeId): void
    {
        $permitidos = ["time", "publico", "equipe"];
        if (!in_array($visibilidade, $permitidos, true)) 
        {
            $this->erros[$campo] = "Visibilidade inválida.";
            return;
        }

        // RN13: Visibilidade para equipe somente se o projeto estiver vinculado a uma equipe
        if ($visibilidade === "equipe" && empty($equipeId)) 
        {
            $this->erros[$campo] = "A visibilidade para equipe só é permitida quando o projeto pertencer a uma equipe.";
        }
    }

    public function validarHash(string $campo, $hash): void
    {
        if (empty($hash) || strlen(trim($hash)) < 4) 
        {
            $this->erros[$campo] = "O código hash é obrigatório e deve ser válido.";
        }
    }

    public function tipoComentario(string $campo, mixed $valor)
    {
        if (!in_array($valor, ["forum", "projeto"], true))
        {
            $this->erros[$campo] = "Selecione um tipo de comentário válido";
        }

        return $this;
    }

    public function idPositivo(string $campo, mixed $valor)
    {
        if (!is_scalar($valor) || filter_var($valor, FILTER_VALIDATE_INT) === false || filter_var($valor, FILTER_VALIDATE_INT) <= 0)
        {
            $this->erros[$campo] = "O identificador informado é inválido";
        }

        return $this;
    }

    public function papelEquipe(string $papel)
    {
        if (!in_array($papel, ["coordenador", "participante"], true))
        {
            $this->erros["papel"] = "Selecione um papel válido para a equipe";
        }

        return $this;
    }



    private function validarGrupoArquivo(string $campo, array $arquivos, array $extensoesPermitidas, string $tipoNome)
    {
        if (empty($arquivos) || !isset($arquivos['name']))
        {
            return $this;
        }

        $nomes = is_array($arquivos['name']) ? $arquivos['name'] : [$arquivos['name']];
        $tamanhos = is_array($arquivos['size']) ? $arquivos['size'] : [$arquivos['size']];
        $errosUpload = is_array($arquivos['error']) ? $arquivos['error'] : [$arquivos['error']];

        foreach ($nomes as $i => $nome)
        {
            $erro = $errosUpload[$i] ?? UPLOAD_ERR_NO_FILE;
            if ($erro === UPLOAD_ERR_NO_FILE || empty($nome))
            {
                continue;
            }
            if ($erro !== UPLOAD_ERR_OK)
            {
                $this->erros[$campo] = "Falha no envio do arquivo: " . $nome;
                break;
            }

            $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
            if (!in_array($ext, $extensoesPermitidas, true))
            {
                $this->erros[$campo] = "O arquivo \"{$nome}\" não é um arquivo de {$tipoNome} válido permitida (. " . implode(', .', $extensoesPermitidas) . ").";
                break;
            }
            if (($tamanhos[$i] ?? 0) > $this->tamanhoMaximo)
            {
                $this->erros[$campo] = "O arquivo " . $nome . " excede o limite de 5MB.";
                break;
            }
        }

        return $this;
    }

    public function temImagem(array $imagem, bool $erro = true)
    {
        if ($imagem["imagem"]["error"] != 4)
        {
            return [
                "status" => true,
                "arquivo" => $imagem["imagem"],
            ];
        }

        if ($erro)
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
