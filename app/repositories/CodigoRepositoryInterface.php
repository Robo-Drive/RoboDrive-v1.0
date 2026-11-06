<?php

namespace app\repositories;

use app\models\Codigo;

interface CodigoRepositoryInterface
{
    public function cadastrar(Codigo $codigo, int $projetoVersaoId): bool;
}