<?php

namespace app\controllers;

use app\core\Controller;
use app\repositories\ComponenteRepositorySql;
use app\repositories\EquipeRepositorySql;
use app\repositories\ProjetoRepositorySql;
use app\repositories\UsuarioRepositorySql;

class HomeController extends Controller
{
    public function home()
    {
        $data["totalProjetos"] = (new ProjetoRepositorySql())->contarAtivos();
        $data["totalComponentes"] = (new ComponenteRepositorySql())->contarAtivos();
        $data["totalEquipes"] = (new EquipeRepositorySql())->contarAtivos();
        $data["totalUsuarios"] = (new UsuarioRepositorySql())->contarAtivos();

        $this->view("home/home", $data);
    }
}
