<?php

namespace app\controllers;

use app\core\Controller;

class ArquivoController extends Controller
{
    public function visualizar()
    {
        if (!isset($_GET["arquivo"]) || empty($_GET["arquivo"]))
        {
            http_response_code(400);
            echo "Arquivo não especificado.";
            return;
        }

        $arquivo = trim($_GET["arquivo"]);
        $storageRoot = realpath(STORE_PATH);

        if (str_starts_with($arquivo, "store/"))
        {
            $caminhoRelativo = substr($arquivo, 6);
            $caminhoCompleto = realpath(STORE_PATH . "/" . $caminhoRelativo);
        }
        elseif (str_starts_with($arquivo, "store\\"))
        {
            $caminhoRelativo = substr($arquivo, 6);
            $caminhoCompleto = realpath(STORE_PATH . "/" . $caminhoRelativo);
        }
        else
        {
            $caminhoCompleto = realpath($arquivo); 
            if (!$caminhoCompleto && ($pos = strpos($arquivo, "/store/")) !== false)
            {
                $subPath = substr($arquivo, $pos + 7);
                $caminhoCompleto = realpath(STORE_PATH . "/" . $subPath);
            }
        }

        if ($caminhoCompleto && is_file($caminhoCompleto) && str_starts_with($caminhoCompleto, $storageRoot))
        {
            $mimeType = mime_content_type($caminhoCompleto) ?: "application/octet-stream";

            header("Content-Type: " . $mimeType);
            header("Content-Length: " . filesize($caminhoCompleto));

            readfile($caminhoCompleto);
            exit;
        } 
        else
        {
            http_response_code(404);
            echo "Arquivo não encontrado ou acesso negado.";
        }
    }
}
