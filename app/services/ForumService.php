<?php

namespace app\services;

use app\models\Forum;
use app\repositories\ForumRepositorySql;


class ForumService
{
    private ForumRepositorySql $repositorySql;
    
    public function __construct()
    {
        $this->repositorySql = new ForumRepositorySql();
    }
    public function salvarForum(Forum $forum): bool
    {
        $this->repositorySql->cadastrar($forum);
        return true;
    }
    public function editarForum(Forum $forum): bool
    {
        try
        {
            $this->repositorySql->editar($forum);
            return true;
        }
        catch (\Throwable $e)
        {
            error_log($e->getMessage());
            return false;
        }
    }
}