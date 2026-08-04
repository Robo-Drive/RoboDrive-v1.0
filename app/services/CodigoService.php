<?php

namespace app\services;

use app\services\UploadService;

class CodigoService
{
    private UploadService $uploadService;

    public function transformarObjetoArquivo(array $codigos,string $path)
    {
        $arquivos = array();
        for($i = 0 ; $i < count($codigos["name"]) ; $i++)
        {
            foreach($codigos as $key => $c)
            {
                $arquivos[$i][$key] = $c[$i];
            }
        }
        $path = "/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-4/code";
        $this->uploadService = new UploadService($path);
        $this->uploadService->uploadArray($arquivos);
    }
}