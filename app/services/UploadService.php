<?php

namespace app\services;

class UploadService
{
    private array $extensoesPermitidas = [
    // Imagens
    "jpg",
    "jpeg",
    "png",
    "gif",
    "svg",
    "webp",

    // Documentos
    "pdf",
    "doc",
    "docx",
    "txt",
    "md",
    "odt",

    // Planilhas
    "xls",
    "xlsx",
    "csv",
    "ods",

    // Apresentações
    "ppt",
    "pptx",
    "odp",

    // Código
    "ino",
    "py",
    "cpp", 
    "c",
    "h",
    "java",
    "js",
    "html",
    "css",
    "json",
    "xml",
    "yaml",
    "yml",

    // Modelagem 3D / CAD
    "stl",
    "obj",
    "step",
    "stp",

    // Compactados
    "zip",
    "rar",
    "7z",
    "tar",
    "gz"
];
    private int $tamanhoMaximo =  5242880; //5MB
    private string $uploadPath; 
    public function __construct(?string $path=null)
    {
        $this->uploadPath = STORE_PATH.$path ?? STORE_PATH;
        if(!is_dir($this->uploadPath))
        {
            mkdir($this->uploadPath,0777,true);
        }
    }
    public function upload(array $file): array
    {
        if($file["size"] > $this->tamanhoMaximo)
        {
            return [
                "status" => false,
                "mensagem" => "Arquivo muito grande. Tamanho máximo permitido é de: 5MB"];
        }
        $extensao = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
        if(!in_array($extensao,$this->extensoesPermitidas))
        {
            return [
                "status" => false,
                "mensagem" => "Extensão de arquivo não permitido"];
        }

        $novaImagem = bin2hex(random_bytes(16)).".".$extensao;

        $destino = $this->uploadPath."/".$novaImagem;
        if(move_uploaded_file($file["tmp_name"],$destino))
        {
            return [
                "status" => true,
                "mensagem" => $destino];
        }
        return [
            "status" => false,
            "mensagem" => "Falha ao redirecionar a imagem"];
    }

    public function uploadArray(array $files)
    {
        foreach($files as $file)
        {
            $this->upload($file);
        }

    }

    
}