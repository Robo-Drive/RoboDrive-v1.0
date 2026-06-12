<?php

namespace app\services;

use app\models\Forum;
use app\repositories\ForumRepositorySql;
use Exception;

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
    public function editarForum(Forum $forum):bool
    {
        try
        {
            $this->repositorySql->editar($forum);
        }
        catch(Exception $e)
        {
            print_r($e);
            die;
            return false;
        }
        return true;
    }
}