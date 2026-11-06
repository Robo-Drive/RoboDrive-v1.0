<?php

namespace app\services;

use app\models\Imagem;
use app\models\Projeto;
use app\repositories\ImagemRepositorySql;

class ImagemService
{
    private UploadService $uploadService;
    private ImagemRepositorySql $repository;

    public function __construct()
    {
        $this->repository = new ImagemRepositorySql();
    }

    public function salvarImagem(array $imagens, Projeto $projeto): array
    {
        $arquivos = [];

        for ($i = 0; $i < count($imagens['name']); $i++)
        {
            foreach ($imagens as $key => $valor)
            {
                $arquivos[$i][$key] = $valor[$i];
            }
        }

        $path = "/users/user-"
            . $_SESSION["usuario_logado"]->getId()
            . "/projects/project-"
            . $projeto->getId()
            . "/img";

        $this->uploadService = new UploadService($path);

        $destinos = $this->uploadService->uploadArray($arquivos);

        $imagensObj = [];

        foreach ($destinos as $destino) {
            if (!$destino['status']) {
                throw new \RuntimeException($destino['mensagem']);
            }

            $imagem = new Imagem();

            $imagem->setCaminho($destino['mensagem']);
            $imagem->setProjetoId($projeto->getId());

            $this->repository->cadastrar($imagem);

            $imagensObj[] = $imagem;
        }

        return $imagensObj;
    }
    public function buscarProjeto(int $projetoId): array
    {
        return $this->repository->buscarProjeto($projetoId);
    }
}
