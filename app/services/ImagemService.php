<?php

namespace app\services;

use app\models\Projeto;
use app\services\UploadService;

class ImagemService
{
    private UploadService $uploadService;

    public function transformarObjetoArquivo(array $imagens,Projeto $projeto)
    {
        $arquivos = array();
        for($i = 0 ; $i < count($imagens["name"]) ; $i++)
        {
            foreach($imagens as $key => $img)
            {
                $arquivos[$i][$key] = $img[$i];
            }
        }
        $path = "/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-".$projeto->getId()."/img";
        $this->uploadService = new UploadService($path);
        $this->uploadService->uploadArray($arquivos);
    }
}