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
            if ($this->requisicaoAjax())
            {
                $this->responderJson([
                    "sucesso" => false,
                    "mensagem" => "Sua sessão expirou. Faça login novamente.",
                    "redirect" => URL_BASE . "/login"
                ], 401);
            }

            $this->redirect(URL_BASE);
        }
    }
    // Admin logado? (não bloqueia, só informa)
    protected function ehAdmin(): bool
    {
        return isset($_SESSION["usuario_logado"]) && $_SESSION["usuario_logado"]->getRegra() === "admin";
    }

    public function adminRequired()
    {
        if($_SESSION["usuario_logado"]->getRegra() == "admin")
        {
            return true;
        }
        return false;
    }

    /*
     * Justificativa de desativação: só existe quando um ADMIN desativa conteúdo/conta de OUTRO usuário.
     * Autodesativação (ou admin desativando algo próprio) não tem justificativa nem e-mail.
     */
    protected function exigeJustificativa(?int $donoId): bool
    {
        $logado = $_SESSION["usuario_logado"] ?? null;

        return $logado
            && $logado->getRegra() === "admin"
            && (int)$donoId !== (int)$logado->getId();
    }

    // Retorna a mensagem de erro, ou null se a justificativa enviada for válida.
    protected function erroJustificativa(): ?string
    {
        $validador = new \app\helpers\ValidadorHelper();
        $validador->justificativa($_POST["justificativa"] ?? "");

        return $validador->getErros()["justificativa"] ?? null;
    }

    protected function justificativaEnviada(): string
    {
        return trim(filter_input(INPUT_POST, "justificativa", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "");
    }

    // Resposta padrão das ações enviadas por AJAX: sempre JSON.
    // O redirect só é enviado quando a ação deu certo.
    protected function responderAcao(bool $sucesso, string $mensagem, int $status = 200, ?string $redirect = null, array $erros = []): void
    {
        $dados = ["sucesso" => $sucesso, "mensagem" => $mensagem];

        if ($sucesso && $redirect !== null)
        {
            $dados["redirect"] = $redirect;
        }

        if (!empty($erros))
        {
            $dados["erros"] = $erros;
        }

        $this->responderJson($dados, $status);
    }

    // Erro de validação da justificativa (aparece embaixo do campo)
    protected function responderErroJustificativa(string $erro): void
    {
        $this->responderAcao(false, $erro, 422, null, ["justificativa" => $erro]);
    }
}
