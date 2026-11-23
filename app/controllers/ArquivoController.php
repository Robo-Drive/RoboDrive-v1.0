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

        if (!$storageRoot)
        {
            http_response_code(500);
            echo "Diretório de armazenamento não encontrado.";
            return;
        }

        $arquivo = str_replace("\\", "/", $arquivo);

        if (str_starts_with($arquivo, "store/"))
        {
            $caminhoRelativo = substr($arquivo, 6);
            $caminhoCompleto = realpath($storageRoot . "/" . $caminhoRelativo);
        }
        else
        {
            $caminhoCompleto = realpath($arquivo);
        }

        if (!$caminhoCompleto || !is_file($caminhoCompleto) || !is_readable($caminhoCompleto) ||!str_starts_with($caminhoCompleto, $storageRoot . DIRECTORY_SEPARATOR))
        {
            http_response_code(404);
            echo "Arquivo não encontrado ou acesso negado.";
            return;
        }

        $mimeType = mime_content_type($caminhoCompleto) ?: "application/octet-stream";

        header("Content-Type: " . $mimeType);
        header("Content-Length: " . filesize($caminhoCompleto));

        readfile($caminhoCompleto);
        exit;
    }
}
