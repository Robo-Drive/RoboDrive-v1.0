<?php

namespace app\services;

use app\models\Projeto;
use app\services\UploadService;

class CodigoService
{
    private UploadService $uploadService;

    public function transformarObjetoArquivo(array $codigos,Projeto $projeto)
    {
        $arquivos = array();
        for($i = 0 ; $i < count($codigos["name"]) ; $i++)
        {
            foreach($codigos as $key => $c)
            {
                $arquivos[$i][$key] = $c[$i];
            }
        }
        $path = "/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-".$projeto->getId()."/code";
        $this->uploadService = new UploadService($path);
        $this->uploadService->uploadArray($arquivos);
    }
}