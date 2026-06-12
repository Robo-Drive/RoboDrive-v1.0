<?php

namespace app\controllers;

use app\core\Controller;

class ArquivoController extends Controller
{
    public function visualizar()
    {
        $this->loginRequired();

        if (!isset($_GET['arquivo']) || empty($_GET['arquivo']))
        {
            http_response_code(400);
            echo "Arquivo não especificado.";
            return;
        }

        $arquivo = $_GET['arquivo'];
        $storagePath = realpath(STORE_PATH);
        
        // Constrói o caminho completo e resolve o caminho real do arquivo
        $caminhoCompleto = realpath($storagePath . '/' . $arquivo);

        // Verifica se o arquivo existe e se está dentro do diretório de storage permitido (evita Directory Traversal)
        if ($caminhoCompleto && is_file($caminhoCompleto) && strpos($caminhoCompleto, $storagePath) === 0)
        {
            // Obtém o tipo do arquivo (MIME type)
            $mimeType = mime_content_type($caminhoCompleto);
            
            // Define os cabeçalhos para exibir a imagem corretamente
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($caminhoCompleto));
            
            // Lê e envia o conteúdo do arquivo para o navegador
            readfile($caminhoCompleto);
            exit;
        } else {
            http_response_code(404);
            echo "Arquivo não encontrado ou acesso negado.";
        }
    }
}