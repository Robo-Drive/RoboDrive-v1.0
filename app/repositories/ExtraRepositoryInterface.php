<?php

namespace app\repositories;

use app\models\Extra;

interface ExtraRepositoryInterface
{
    public function cadastrar(Extra $extra, int $projetoVersaoId): ?Extra;
    public function associarVersao(int $extraId, int $projetoVersaoId): bool;
    public function buscarPorVersao(int $projetoVersaoId): array;
}
