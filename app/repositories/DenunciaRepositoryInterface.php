<?php

namespace app\repositories;

interface DenunciaRepositoryInterface
{
    public function salvar(string $tipo, int $alvoId, int $usuarioId, string $motivo, string $descricao): bool;
    public function listarAgrupadas(): array;
    public function dispensar(string $tipo, int $alvoId): bool;
}
