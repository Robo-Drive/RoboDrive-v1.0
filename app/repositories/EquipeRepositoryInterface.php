<?php

namespace app\repositories;

use app\models\Equipe;
use app\models\Usuario;

interface EquipeRepositoryInterface
{
    public function cadastrar(Equipe $equipe):?Equipe;
    public function buscarId(Equipe $equipe, bool $incluirDesativados = false):?Equipe;
    public function buscarNome(Equipe $equipe): ?array;
    public function buscarUsuario(Usuario $usuario): ?array;
    public function listarTodos():array;
    public function editar(Equipe $equipe):?Equipe;
    public function deletar(Equipe $equipe):bool;
    public function buscarPorHash(string $hash): ?Equipe;
    public function solicitarEntrada(int $equipeId, int $usuarioId): bool;
    public function listarSolicitacoesPendentes(int $equipeId): array;
    public function aprovarSolicitacao(int $equipeId, int $usuarioId): bool;
    public function rejeitarSolicitacao(int $equipeId, int $usuarioId): bool;
    public function buscarPapelUsuario(int $equipeId, int $usuarioId): ?string;
    public function adicionarMembro(int $equipeId, int $usuarioId, string $papel = 'participante'): bool;
    public function adicionarMembroDireto(int $equipeId, int $usuarioId, string $papel = 'participante'): bool;
    public function removerMembro(int $equipeId, int $usuarioId): bool;
    public function buscarUsuariosEquipe(int $equipeId): array;
    public function usuarioECoordenador(int $equipeId, int $usuarioId): bool;
    public function usuarioPertenceAEquipe(int $equipeId, int $usuarioId): bool;
    public function listarMembros(int $equipeId): array;
    public function buscarSeguindo(Usuario $usuario): ?array;
    public function atualizarPapelMembro(int $equipeId, int $usuarioId, string $papel): bool;
    public function alterarPapelMembro(int $equipeId, int $usuarioId, string $papel): bool;
    public function buscarUsuarioFiltrado(Usuario $usuario, string $busca): ?array;
    public function listarAdminTodos(?string $busca = null): array;
    public function listarPorStatus(bool $status, ?string $busca = null): array;
    public function ativar(int $equipeId): bool;
    public function desativar(int $equipeId): bool;
    public function contarAtivos(): int;
}
