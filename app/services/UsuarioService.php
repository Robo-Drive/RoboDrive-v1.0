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
    public function salvarUsuario(Usuario $usuario)
    {
        $resposta = $this->repositorySql->buscarEmail($usuario);
        if(empty($resposta))
        {
            $this->repositorySql->cadastrar($usuario);
        }
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
    public function logarUsuario(Usuario $usuario): array
    {
        $resposta = array();
        $login = $this->repositorySql->buscarEmail($usuario);
        if(empty($login))
        {
            return [
                "erros" => "Email não cadastrado",
                "liberado" => false
            ];
        }
        else
        {
            $login->getSenha();
        }
        return $resposta;
    }
}