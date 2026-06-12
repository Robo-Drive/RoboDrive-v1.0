<?php

namespace app\repositories;

use app\models\Projeto;
use app\models\Usuario;
use app\models\Equipe;

interface ProjetoRepositoryInterface
{
    public function cadastrar(Projeto $projeto):?Projeto;
    public function buscarId(Projeto $projeto):?Projeto;
    public function buscarNome(Projeto $projeto): ?array;
    public function buscarEquipe(Equipe $usuario):?array;
    public function buscarUsuario(Usuario $usuario):?array;
    public function buscarSeguindo(Usuario $usuario):?array;
    public function listarTodos():array;
    public function listarPublico():array;
    public function editar(Projeto $projeto):?Projeto;
    public function deletar(Projeto $projeto):bool;
}