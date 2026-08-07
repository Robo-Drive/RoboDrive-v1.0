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
    public function salvarUsuario(Usuario $usuario): bool | array
    {
        $mensages = array();
        $emailResposta = $this->repositorySql->buscarEmail($usuario);
        $nomeUsuarioResposta = $this->repositorySql->buscarNomeUsuario($usuario);
        if(empty($emailResposta) && empty($nomeUsuarioResposta))
        {
            $this->repositorySql->cadastrar($usuario);
            return true;
        }
        
        if(!empty($emailResposta))
        {
            $mensages["email"] = "Erro: Este e-mail já está cadastrado!";
        }
        if(!empty($nomeUsuarioResposta))
        {
            $mensages["nome_usuario"] = "Erro: Este nome de usuário já está cadastrado!";
        }
        return $mensages;
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