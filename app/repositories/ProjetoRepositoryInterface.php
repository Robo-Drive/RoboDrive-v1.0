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
    public function atualizarNome(int $projetoId, string $nome): bool;
    public function transferirProprietario(int $projetoId, int $novoDonoId): bool;
    public function atualizarCategoria(int $projetoId, int $categoriaId): bool;
    
    public function buscarPorHash(string $hash): ?Projeto;
    public function solicitarEntrada(int $projetoId, int $usuarioId): bool;
    public function listarSolicitacoesPendentes(int $projetoId): array;
    public function aprovarSolicitacao(int $projetoId, int $usuarioId): bool;
    public function rejeitarSolicitacao(int $projetoId, int $usuarioId): bool;
    public function existeNomeDoUsuario(string $nome, int $usuarioId, ?int $ignorarProjetoId = null): bool;
    public function buscarUltimaVersao(int $projetoId): ?array;
    public function buscarTodasVersoes(int $projetoId): array;
    public function criarVersao(int $projetoId, int $usuarioId, int $versao, string $descricaoAlteracao): int;
    public function adicionarMembro(int $projetoId, int $usuarioId, string $papel = 'participante'): bool;
    public function listarAdminTodos(): array;
    public function listarPorStatus(bool $status): array;
    public function buscarPublico(): array;
    public function usuarioPertenceAoProjeto(int $projetoId, int $usuarioId): bool;
    public function usuarioECoordenador(int $projetoId, int $usuarioId): bool;
    public function buscarUsuarioFiltrado(int $usuarioId, $busca = null, $categoriaId = null): array;
    public function contarAtivos(): int;
}
