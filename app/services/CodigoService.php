<?php

namespace app\services;

use app\models\Projeto;
use app\services\UploadService;
use app\repositories\CodigoRepositorySql;

class CodigoService
{
    private UploadService $uploadService;
    private CodigoRepositorySql $repository;

    public function salvarCodigo(array $codigos,Projeto $projeto)
    {
        $arquivos = array();
        for($i = 0 ; $i < count($codigos["name"]) ; $i++)
        {
            foreach($codigos as $key => $c)
            {
                $arquivos[$i][$key] = $c[$i];
            }
        }
        $path = "/users/user-".$_SESSION["usuario_logado"]->getId()."/projects/project-".$projeto->getId()."/code";
        $this->uploadService = new UploadService($path);
        $destinos = $this->uploadService->uploadArray($arquivos);
        $this->repository->cadastrar($codigos);
    }
}