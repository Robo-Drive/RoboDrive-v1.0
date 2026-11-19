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
    public function requisicaoAjax(): bool
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
            strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";
    }

    public function responderJson(array $dados, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
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
