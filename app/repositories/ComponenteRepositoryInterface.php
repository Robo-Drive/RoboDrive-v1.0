<?php

namespace app\repositories;

use app\models\Componente;

interface ComponenteRepositoryInterface
{
    public function cadastrar(Componente $componente):?Componente;
    public function buscarId(Componente $componente):?Componente;
    public function buscarNome(Componente $componente): ?array;
    public function buscarProjeto(int $projetoId): ?array;
    public function listarTodos():array;
    public function editar(Componente $componente):?Componente;
    public function deletar(Componente $componente):bool;
}