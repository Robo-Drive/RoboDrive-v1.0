<?php

namespace app\repositories;

use app\models\Equipe;
use app\models\Usuario;

interface EquipeRepositoryInterface
{
    public function cadastrar(Equipe $equipe):?Equipe;
    public function buscarId(Equipe $equipe):?Equipe;
    public function buscarNome(Equipe $equipe): ?array;
    public function buscarUsuario(Usuario $usuario): ?array;
    public function listarTodos():array;
    public function editar(Equipe $equipe):?Equipe;
    public function deletar(Equipe $equipe):bool;
}