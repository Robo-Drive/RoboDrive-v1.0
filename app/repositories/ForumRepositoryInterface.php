<?php

namespace app\repositories;

use app\models\Forum;

interface ForumRepositoryInterface
{
    public function cadastrar(Forum $forum):?Forum;
    public function buscarId(Forum $forum):?Forum;
    public function buscarConteudo(Forum $forum): ?array;
    public function listarTodos():array;
    public function editar(Forum $forum):?Forum;
    public function deletar(Forum $forum):bool;
}