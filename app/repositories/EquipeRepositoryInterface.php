<?php

namespace app\repositories;

use app\models\Equipe;
use app\models\Usuario;

interface EquipeRepositoryInterface
{
    public function cadastrar(Equipe $projeto):?Equipe;
    public function buscarId(Equipe $projeto):?Equipe;
    public function buscarNome(Equipe $projeto): ?array;
    public function buscarUsuario(Usuario $usuario): ?array;
    public function listarTodos():array;
    public function editar(Equipe $projeto):?Equipe;
    public function deletar(Equipe $projeto):bool;
}