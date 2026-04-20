<?php

namespace app\core;

class Controller
{
    public function view(string $view, ?array $data = null)
    {
        if ($data) {
            extract($data);
        }

        $path = __DIR__ . "/../views/$view.php";

        if (file_exists($path)) {
            require_once $path;
        } else {
            echo 'A view solicitada não foi encontrada: ' . $view;
        }
    }

    public function redirect(string $url)
    {
        header('location: ' . $url);
        exit();
    }

    public function authRequired(): bool
    {
        if (!isset($_SESSION['user_logado'])) {
            $this->redirect(URL_BASE . '/login');
            return false;
        }
        return true;
    }

    public function professorRequired(): bool
    {
        if (!isset($_SESSION['user_logado']) || $_SESSION['user_logado']->getPerfil() !== 'professor') {
            $this->redirect(URL_BASE . '/');
            return false;
        }
        return true;
    }

    public function getLoggedUser()
    {
        return $_SESSION['user_logado'] ?? null;
    }
}
