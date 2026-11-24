<?php

namespace app\controllers;

use app\core\Controller;
use app\repositories\CodigoRepositorySql;
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

        $data["trituradorCards"] = $this->cardsCodigoPublico();

        $this->view("home/home", $data);
    }

    // Projetos públicos com código, para a faixa "do projeto ao código". Reaproveita os repositórios existentes.
    // ponytail: varre os públicos um a um; paginar/limitar na consulta se a base crescer muito.
    private function cardsCodigoPublico(): array
    {
        $projetos = new ProjetoRepositorySql();
        $codigos = new CodigoRepositorySql();
        $cards = [];

        foreach ($projetos->listarPublico() as $projeto)
        {
            $versao = $projetos->buscarUltimaVersao($projeto->getId());
            if (!$versao) continue;

            foreach ($codigos->buscarPorVersao((int) $versao["id"]) as $codigo)
            {
                $caminho = (string) $codigo->getCaminho();
                $fisico = is_file($caminho) ? $caminho : realpath(STORE_PATH . "/" . preg_replace("#^store/#", "", $caminho));
                $texto = ($fisico && is_file($fisico) && is_readable($fisico)) ? file_get_contents($fisico, false, null, 0, 2000) : false;
                if ($texto === false || trim($texto) === "" || str_contains($texto, "\0")) continue;

                $linhas = array_slice(preg_split("/\r\n|\n|\r/", str_replace("\t", "    ", $texto)), 0, 8);
                $cards[] = [
                    "titulo" => (string) $projeto->getNome(),
                    "tag" => (string) ($projeto->getCategoria()?->getNome() ?? "Projeto"),
                    "meta" => (string) ($projeto->getDescricao() ?: "Projeto público"),
                    "codigo" => implode("\n", array_map(fn($l) => mb_substr(rtrim($l), 0, 30), $linhas)),
                ];
                break;
            }

            if (count($cards) >= 8) break;
        }

        return $cards;
    }
}
