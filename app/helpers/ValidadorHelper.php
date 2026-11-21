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



    // Papel de integrante (equipe ou projeto)
    public function papel(string $papel, string $campo = "papel")
    {
        if (!in_array($papel, ["coordenador", "participante"], true))
        {
            $this->erros[$campo] = "Selecione um papel válido";
        }

        return $this;
    }

    // Valor que precisa estar dentro de uma lista de opções
    public function opcao(string $campo, mixed $valor, array $permitidos, string $mensagem)
    {
        if (!in_array($valor, $permitidos, true))
        {
            $this->erros[$campo] = $mensagem;
        }

        return $this;
    }

    // Tipo de conteúdo que pode ser denunciado
    public function tipoDenuncia(string $campo, mixed $valor)
    {
        return $this->opcao($campo, $valor, ["projeto", "forum", "usuario", "equipe", "comentario", "componente"], "Conteúdo da denúncia inválido.");
    }

    // Visibilidade do fórum: "publico", "privado", "equipe" ou o id de uma equipe
    public function visibilidadeForum(string $campo, mixed $valor)
    {
        $ehEquipe = is_scalar($valor) && filter_var($valor, FILTER_VALIDATE_INT) !== false && (int)$valor > 0;

        if (!$ehEquipe && !in_array($valor, ["publico", "privado", "equipe"], true))
        {
            $this->erros[$campo] = "Selecione uma visibilidade válida";
        }

        return $this;
    }

    // Justificativa de desativação feita por um admin
    public function justificativa(mixed $valor, string $campo = "justificativa")
    {
        $texto = trim((string)$valor);

        if ($texto === "")
        {
            $this->erros[$campo] = "A justificativa é obrigatória.";
        }
        elseif (mb_strlen($texto) < 10 || mb_strlen($texto) > 2000)
        {
            $this->erros[$campo] = "A justificativa deve ter entre 10 e 2000 caracteres.";
        }

        return $this;
    }

    // Dois ids que precisam ser positivos e diferentes entre si
    public function idsDiferentes(string $campo, mixed $primeiro, mixed $segundo, string $mensagem)
    {
        $a = filter_var($primeiro, FILTER_VALIDATE_INT);
        $b = filter_var($segundo, FILTER_VALIDATE_INT);

        if ($a === false || $b === false || $a <= 0 || $b <= 0 || $a === $b)
        {
            $this->erros[$campo] = $mensagem;
        }

        return $this;
    }

    // Categoria do projeto: uma existente ou "criar" (com nome da nova)
    public function categoria(mixed $categoria, mixed $novaCategoria)
    {
        $categoria = trim((string)$categoria);
        $this->obrigatorio("categoria", $categoria, "Selecione ou crie uma categoria.");

        if ($categoria === "criar")
        {
            $novaCategoria = trim((string)$novaCategoria);
            $this->obrigatorio("nova_categoria", $novaCategoria, "Informe o nome da nova categoria.");

            if (!isset($this->erros["nova_categoria"]))
            {
                $this->tamanho("nova_categoria", $novaCategoria, 3, 100, "A nova categoria precisa ter no mínimo 3 caracteres e no máximo 100");
            }
        }
        elseif ($categoria !== "" && (filter_var($categoria, FILTER_VALIDATE_INT) === false || (int)$categoria <= 0))
        {
            $this->erros["categoria"] = "Categoria inválida.";
        }

        return $this;
    }

    // Componentes do projeto: id positivo e quantidade maior que zero
    public function componentes(array $componentes)
    {
        foreach ($componentes as $id => $dados)
        {
            if ((int)$id <= 0)
            {
                continue;
            }

            $this->componenteQuantidade((int)($dados["quantidade"] ?? 0));
        }

        return $this;
    }

    // Verifica se algum arquivo foi realmente enviado em um campo múltiplo
    public function possuiArquivos(array $arquivoCampo): bool
    {
        if (empty($arquivoCampo["name"]) || !is_array($arquivoCampo["name"]))
        {
            return false;
        }

        foreach ($arquivoCampo["name"] as $nome)
        {
            if (trim((string)$nome) !== "")
            {
                return true;
            }
        }

        return false;
    }

    // Cada arquivo enviado precisa ter uma descrição
    public function descricoesArquivos(array $arquivos, array $descricoes, string $campoPrefixo, string $rotulo)
    {
        if (!$this->possuiArquivos($arquivos))
        {
            return $this;
        }

        foreach ($arquivos["name"] as $i => $nomeArquivo)
        {
            $nomeLimpo = trim((string)$nomeArquivo);

            if ($nomeLimpo === "")
            {
                continue;
            }

            $campo = $campoPrefixo . "_" . $i;
            $desc = trim((string)($descricoes[$i] ?? ""));

            $this->obrigatorio($campo, $desc, "A descrição de {$rotulo} {$nomeLimpo} é obrigatória.");

            if (!isset($this->erros[$campo]))
            {
                $this->tamanho($campo, $desc, 3, 255, "Descrição de {$rotulo}");
            }
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
        if (($imagem["imagem"]["error"] ?? UPLOAD_ERR_NO_FILE) != UPLOAD_ERR_NO_FILE)
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
