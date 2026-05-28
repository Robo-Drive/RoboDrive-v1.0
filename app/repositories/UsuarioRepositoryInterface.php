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
    public function listarTodos():array;
    public function editar(Usuario $usuario):?Usuario;
    public function deletar(Usuario $usuario):bool;
}