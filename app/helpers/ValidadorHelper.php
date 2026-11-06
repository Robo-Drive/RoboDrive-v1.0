<?php

namespace app\helpers;

class ValidadorHelper
{
    private array $erros = [];

    private array $extensoesPermitidas = [
        // Imagens
        "jpg", "jpeg", "png", "gif", "svg", "webp",

        // Documentos
        "pdf", "doc", "docx", "txt", "md", "odt",

        // Planilhas
        "xls", "xlsx", "csv", "ods",

        // Apresentações
        "ppt", "pptx", "odp",

        // Código
        "ino", "py", "cpp", "c", "h", "java", "js",
        "html", "css", "json", "xml", "yaml", "yml",

        // Modelagem 3D / CAD
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

    public function validarArquivo(string $campo, array $arquivos)
    {
        if (empty($arquivos) || !isset($arquivos['name']))
        {
            return $this;
        }

        $nomes = is_array($arquivos['name']) ? $arquivos['name']: [$arquivos['name']];

        $tamanhos = is_array($arquivos['size']) ? $arquivos['size'] : [$arquivos['size']];

        $errosUpload = is_array($arquivos['error']) ? $arquivos['error'] : [$arquivos['error']];

        foreach ($nomes as $i => $nome)
        {
            $erro = $errosUpload[$i] ?? UPLOAD_ERR_NO_FILE;

            if ($erro === UPLOAD_ERR_NO_FILE)
            {
                continue;
            }
            if ($erro !== UPLOAD_ERR_OK)
            {
                $this->erros[$campo] = "Falha no envio do arquivo: " . $nome;
                break;
            }

            $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));

            if (!in_array($ext, $this->extensoesPermitidas, true))
            {
                $this->erros[$campo] = "Extensão de arquivo não permitida: " . $nome;
                break;
            }
            if (($tamanhos[$i] ?? 0) > $this->tamanhoMaximo)
            {
                $this->erros[$campo] = "O arquivo " . $nome . " excede 5MB";
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
