<?php

namespace app\repositories;

use app\models\Forum;

interface ForumRepositoryInterface
{
    public function cadastrar(Forum $forum): ?Forum;
    public function buscarId(Forum $forum, bool $incluirDesativados = false): ?Forum;
    public function buscarConteudo(Forum $forum): ?array;
    public function listarTodos(): array;
    public function editar(Forum $forum): ?Forum;
    public function desativar(int $forumId): bool;
    public function ativar(int $forumId): bool;
    public function buscarEquipe(int $id, ?string $busca = null);
    public function listarAdminTodos(?string $busca = null): array;
    public function listarPorStatus(bool $status, ?string $busca = null): array;
}
