<?php

namespace app\services;

use app\models\Projeto;

class ComentarioService
{
    // RN20: só comenta quem pode ver o conteúdo. Retorna ["erro" => ..., "status" => ...] ou null se puder.
    public function validarContexto(string $tipo, int $origemId): ?array
    {
        if ($tipo === "forum")
        {
            $forumService = new ForumService();
            $postagem = $forumService->buscarPostagem($origemId);

            if (!$postagem || $postagem->getConteudo() === null)
            {
                return ["erro" => "Postagem não encontrada.", "status" => 404];
            }

            if (!$forumService->podeVisualizar($postagem))
            {
                return ["erro" => "Você não tem permissão para comentar nesta postagem.", "status" => 403];
            }

            return null;
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($origemId);
        $projeto = (new \app\repositories\ProjetoRepositorySql())->buscarId($projetoBusca);

        if (!$projeto || $projeto->getNome() === null)
        {
            return ["erro" => "Projeto não encontrado.", "status" => 404];
        }

        if (!(new ProjetoService())->podeVisualizar($projeto))
        {
            return ["erro" => "Você não tem permissão para comentar neste projeto.", "status" => 403];
        }

        return null;
    }
}
