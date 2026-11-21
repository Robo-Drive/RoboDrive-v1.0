<?php

namespace app\repositories;

use app\models\Imagem;

interface ImagemRepositoryInterface
{
    public function cadastrar(Imagem $imagem): bool;
    public function buscarProjeto(int $projetoId): array;
    public function desativarImagensExceto(int $projetoId, array $idsManter): bool;
}
