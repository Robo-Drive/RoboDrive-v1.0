<?php 
namespace app\core;

class Controller
{
    public function view(string $view, ?array $data = null)
    {
        if($data)
        {
            extract($data);
        }
        $path = __DIR__ . "/../views/$view.php";
        if(file_exists($path))
        {
            require_once $path;
        } 
        else 
        {
            print 'A view solicitada não foi encontrada: ' . $view;
        }
    }
    public function redirect(string $url)
    {
        header('location: ' . $url);
        exit();
    }
    public function loginRequired()
    {
        if(!isset($_SESSION["usuario_logado"]))
        {
            $this->redirect(URL_BASE);
        }
    }
    public function adminRequired()
    {
        if($_SESSION["usuario_logado"]->getRegra() == "admin")
        {
            return true;
        }
        return false;
    }
}
