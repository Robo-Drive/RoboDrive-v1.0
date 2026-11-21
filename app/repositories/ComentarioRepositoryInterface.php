<?php

namespace app\repositories;

use app\models\Comentario;

interface ComentarioRepositoryInterface
{
    public function salvarForum(int $forumId, int $usuarioId, string $conteudo): bool;
    public function salvarProjeto(int $projetoId, int $usuarioId, string $conteudo): bool;
    public function buscarPorForum(int $forumId): array;
    public function buscarPorProjeto(int $projetoId): array;
    public function buscarPorId(string $tipo, int $id): ?Comentario;
    public function desativar(int $id, ?string $tipo = null): bool;
}
