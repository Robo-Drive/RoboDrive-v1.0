<?php

namespace app\services;

use app\models\Codigo;
use app\models\Projeto;
use app\repositories\CodigoRepositorySql;

class CodigoService
{
    private UploadService $uploadService;
    private CodigoRepositorySql $repository;

    public function __construct()
    {
        $this->repository = new CodigoRepositorySql();
    }

    public function salvarCodigo(array $codigos,array $descricoes,Projeto $projetoVersao): array 
    {
        $arquivos = [];

        for ($i = 0; $i < count($codigos['name']); $i++) {
            foreach ($codigos as $key => $valor) {
                $arquivos[$i][$key] = $valor[$i];
            }
        }

        $projeto = $projetoVersao;

        $path = "/users/user-"
            . $_SESSION["usuario_logado"]->getId()
            . "/projects/project-"
            . $projeto->getId()
            . "/code";

        $this->uploadService = new UploadService($path);

        $destinos = $this->uploadService->uploadArray($arquivos);

        $codigosObj = [];

        foreach ($destinos as $i => $destino) {
            if (!$destino['status']) {
                throw new \RuntimeException($destino['mensagem']);
            }

            $codigo = new Codigo();
            $codigo->setCaminho($destino['mensagem']);
            $codigo->setDescricao($descricoes[$i] ?? null);

            $this->repository->cadastrar(
                $codigo,
                $projetoVersao->getId()
            );

            $codigosObj[] = $codigo;
        }

        return $codigosObj;
    }
    public function buscarProjeto(int $projetoId): array
    {
        return $this->repository->buscarProjeto($projetoId);
    }
}
