<?php

namespace app\repositories;

use app\models\Usuario;

interface UsuarioRepositoryInterface
{
    public function cadastrar(Usuario $usuario):?Usuario;
    public function buscarId(Usuario $usuario):?Usuario;
    public function buscarEmail(Usuario $usuario): ?Usuario;
    public function buscarNome(Usuario $usuario): ?array;
    public function buscarProjeto(int $projetoId): ?array;
    public function listarTodos(?string $busca = null):array;
    public function editar(Usuario $usuario):?Usuario;
    public function deletar(Usuario $usuario):bool;
    public function salvarTokenRecuperacao(int $usuarioId, string $token, string $expiraEm): bool;
    public function buscarTokenValido(string $token): ?array;
    public function ativar(Usuario $usuario): bool;
    public function redefinirSenhaComToken(string $token, string $novaSenha): bool;
    public function buscarNomeUsuario(Usuario $usuario): ?array;
    public function buscarEmailDiferenteId(Usuario $usuario): ?Usuario;
    public function buscarNomeUsuarioDiferenteId(Usuario $usuario): ?array;
    public function contarAtivos(): int;
}
