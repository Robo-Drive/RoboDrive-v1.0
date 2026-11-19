<?php

namespace app\services;

use app\models\Extra;
use app\repositories\ExtraRepositorySql;

class ExtraService
{
    private ExtraRepositorySql $repository;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->repository = new ExtraRepositorySql();
    }

    public function salvarExtras(array $extras, array $descricoes, object $projetoVersao): array
    {
        $arquivos = [];
        $total = count($extras['name'] ?? []);

        for ($i = 0; $i < $total; $i++)
        {
            if (empty($extras['name'][$i])) continue;
            foreach ($extras as $key => $valor)
            {
                $arquivos[$i][$key] = $valor[$i];
            }
        }

        if (empty($arquivos)) return [];

        $path = "/users/user-" . $_SESSION["usuario_logado"]->getId()
            . "/projects/project-" . $projetoVersao->getId() . "/extras";

        $this->uploadService = new UploadService($path);
        $destinos = $this->uploadService->uploadArray($arquivos);

        $salvos = [];
        foreach ($destinos as $i => $destino)
        {
            if (!$destino['status'])
            {
                throw new \RuntimeException($destino['mensagem']);
            }
            $extra = new Extra();
            $extra->setCaminho($destino['mensagem']);
            $extra->setDescricao($descricoes[$i] ?? 'Arquivo extra');
            $this->repository->cadastrar($extra, $projetoVersao->getId());
            $salvos[] = $extra;
        }

        return $salvos;
    }
}