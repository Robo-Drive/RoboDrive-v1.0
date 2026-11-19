<?php

namespace app\repositories;

use app\models\Codigo;

interface CodigoRepositoryInterface
{
    public function cadastrar(Codigo $codigo, int $projetoVersaoId): bool;
    public function buscarProjeto(int $projetoId): array;
    public function buscarPorVersao(int $projetoVersaoId): array;
    public function associarVersao(int $codigoId, int $projetoVersaoId): bool;
}
