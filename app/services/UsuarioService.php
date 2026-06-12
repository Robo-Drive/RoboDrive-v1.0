<?php

namespace app\services;

use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;
use Exception;

class UsuarioService
{
    private UsuarioRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new UsuarioRepositorySql;
    }
    public function salvarUsuario(Usuario $usuario): bool
    {
        $resposta = $this->repositorySql->buscarEmail($usuario);
        if(empty($resposta))
        {
            $this->repositorySql->cadastrar($usuario);
            return true;
        }
        return false;
    }
    public function editarUsuario(Usuario $usuario)
    {
        try
        {
            $this->repositorySql->editar($usuario);
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }
}