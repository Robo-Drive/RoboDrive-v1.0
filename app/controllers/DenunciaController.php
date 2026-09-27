<?php

namespace app\controllers;

use app\core\Controller;

class DenunciaController extends Controller
{
    public function listar()
    {
        $this->loginRequired();
        $this->view("denuncia/list");
    }
    public function salvar()
    {
        $this->loginRequired();
        $this->redirect($_SERVER["HTTP_REFERER"] ?? URL_BASE . '/usuario/perfil');
    }
    public function excluir()
    {
        $this->loginRequired();
        $this->redirect(URL_BASE . '/denuncia');
    }
}
