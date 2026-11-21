<?php

namespace app\repositories;

use app\models\Projeto;
use app\models\Usuario;

interface ProjetoRepositoryInterface
{
    public function cadastrar(Projeto $projeto): ?array;
    public function buscarId(Projeto $projeto, bool $incluirDesativados = false): ?Projeto;
    public function buscarNome(Projeto $projeto): ?array;
    public function buscarEquipe(int $id): ?array;
    public function buscarUsuario(Usuario $usuario): ?array;
    public function buscarSeguindo(Usuario $usuario): ?array;
    public function listarTodos(): array;
    public function listarPublico(): array;    
    public function buscarPublicoFiltrado($busca = null, $categoriaId = null): array;
    public function listarMembros(int $projetoId): array;
    public function atualizarPapelMembro(int $projetoId, int $usuarioId, string $papel): bool;
    public function removerMembro(int $projetoId, int $usuarioId): bool;
    public function editar(Projeto $projeto): ?Projeto;
    public function desativar(int $projetoId): bool;
    public function ativar(int $projetoId): bool;
    public function transferirProprietario(int $projetoId, int $novoDonoId): bool;
    
}