<?php

namespace app\services;

use app\models\Usuario;
use app\repositories\UsuarioRepositorySql;

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