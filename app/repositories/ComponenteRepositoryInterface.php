<?php

namespace app\repositories;

use app\models\Componente;

interface ComponenteRepositoryInterface
{
    public function cadastrar(Componente $componente):?Componente;
    public function associar(array $posts);
    public function buscarId(Componente $componente, bool $incluirDesativados = false):?Componente;
    public function buscarNome(Componente $componente): ?array;
    public function buscarProjeto(int $projetoId): ?array;
    public function listarTodos(?string $busca = null):array;
    public function existe(Componente $componente) :bool;
    public function editar(Componente $componente):?Componente;
    public function desativar(Componente $componente):bool;
    public function ativar(Componente $componente):bool;
}