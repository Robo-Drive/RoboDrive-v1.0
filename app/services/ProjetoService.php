<?php

namespace app\services;

use app\helpers\ValidadorHelper;
use app\models\Componente;
use app\models\Projeto;
use app\repositories\CategoriaRepositorySql;
use app\repositories\CodigoRepositorySql;
use app\repositories\ComponenteRepositorySql;
use app\repositories\EquipeRepositorySql;
use app\repositories\ExtraRepositorySql;
use app\repositories\ProjetoRepositorySql;
use Exception;

class ProjetoService
{
    private ProjetoRepositorySql $repositorySql;

    public function __construct()
    {
        $this->repositorySql = new ProjetoRepositorySql();
    }

    public function salvarProjeto(array $posts): array
    {
        if ($this->repositorySql->existeNome(trim($posts["nome"] ?? ""))) 
        {
            throw new Exception("Já existe um projeto cadastrado com esse nome.");
        }

        if (($posts["visibilidade"] ?? "") === "equipe" && empty($posts["equipe_id"])) 
        {
            throw new Exception("A visibilidade para equipe requer vínculo com uma equipe.");
        }

        $projeto = Projeto::map([$posts])[0];
        return $this->repositorySql->cadastrar($projeto);
    }

    public function editarProjeto(Projeto $projeto): bool
    {
        if ($this->repositorySql->existeNome($projeto->getNome(), $projeto->getId())) 
        {
            throw new Exception("Já existe um projeto cadastrado com esse nome.");
        }

        $this->repositorySql->editar($projeto);
        return true;
    }
    public function validarVinculos(?int $equipeId, ?int $categoriaId, int $proprietarioId, string $visibilidade, ?int $equipeAtualId = null): ?string
    {
        if ($categoriaId !== null && !(new CategoriaRepositorySql())->pertenceAoUsuario($categoriaId, $proprietarioId))
        {
            return "A categoria selecionada não pertence ao proprietário do projeto.";
        }

        if ($visibilidade === "equipe" && !$equipeId)
        {
            return "A visibilidade para equipe requer vínculo com uma equipe.";
        }

        if ($equipeId && $equipeId !== $equipeAtualId && !$this->usuarioParticipaDaEquipe($equipeId))
        {
            return "Você não participa da equipe selecionada.";
        }

        return null;
    }

    private function usuarioParticipaDaEquipe(int $equipeId): bool
    {
        $logado = $_SESSION["usuario_logado"] ?? null;

        if (!$logado)
        {
            return false;
        }

        return $logado->getRegra() === "admin"
            || (new EquipeRepositorySql())->usuarioPertenceAEquipe($equipeId, (int)$logado->getId());
    }

    public function podeVisualizar(Projeto $projeto): bool
    {
        $visibilidade = $projeto->getVisibilidade();

        if ($visibilidade === "publico")
        {
            return true;
        }

        if (!isset($_SESSION["usuario_logado"]))
        {
            return false;
        }

        if ($this->usuarioTemPermissao((int)$projeto->getId()))
        {
            return true;
        }

        $equipeId = (int)($projeto->getEquipe()?->getId() ?? 0);

        return $visibilidade === "equipe" && $equipeId > 0 && $this->usuarioParticipaDaEquipe($equipeId);
    }

    public function coordenaEquipeDoProjeto(Projeto $projeto): bool
    {
        $logado = $_SESSION["usuario_logado"] ?? null;
        $equipeId = (int)($projeto->getEquipe()?->getId() ?? 0);

        return $logado && $equipeId > 0
            && (new EquipeRepositorySql())->usuarioECoordenador($equipeId, (int)$logado->getId());
    }

    public function podeDesativar(Projeto $projeto): bool
    {
        return $this->usuarioECoordenador((int)$projeto->getId()) || $this->coordenaEquipeDoProjeto($projeto);
    }

    public function usuarioECoordenador(int $projetoId): bool
    {
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        if (!$usuarioLogado || $projetoId <= 0)
        {
            return false;
        }

        if ($usuarioLogado->getRegra() === "admin")
        {
            return true;
        }

        return $this->repositorySql->usuarioECoordenador($projetoId, (int)$usuarioLogado->getId());
    }
    public function usuarioTemPermissao(int $projetoId): bool
    {
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        if (!$usuarioLogado || $projetoId <= 0)
        {
            return false;
        }

        if ($usuarioLogado->getRegra() === "admin")
        {
            return true;
        }

        return $this->repositorySql->usuarioPertenceAoProjeto($projetoId, (int)$usuarioLogado->getId());
    }

    public function validarSaidaCoordenador($projetoId, $usuarioId): bool
    {
        $membros = $this->repositorySql->listarMembros($projetoId);
        $coordenadores = array_filter($membros, fn($m) => $m["papel"] === "coordenador");

        foreach ($membros as $m) 
        {
            if ($m["id"] == $usuarioId && $m["papel"] === "coordenador" && count($coordenadores) <= 1) 
            {
                return false;
            }
        }

        return true;
    }

    public function alterarPapel(int $projetoId, int $usuarioId, string $papel): array
    {
        $usuarioLogado = $_SESSION["usuario_logado"] ?? null;

        if ($usuarioLogado && (int)$usuarioLogado->getId() === $usuarioId && $usuarioLogado->getRegra() !== "admin")
        {
            return ["resposta" => false, "erro" => "Você não pode alterar o seu próprio papel.", "status" => 422];
        }

        $this->repositorySql->atualizarPapelMembro($projetoId, $usuarioId, $papel);

        return ["resposta" => true];
    }

    public function removerMembro(int $projetoId, int $usuarioId, ?int $novoCoordenadorId): array
    {
        $membros = $this->repositorySql->listarMembros($projetoId);

        if (count($membros) <= 1)
        {
            return ["resposta" => false, "erro" => "O projeto deve ter pelo menos um integrante.", "status" => 422];
        }

        $coordenadores = array_filter($membros, fn($m) => $m["papel"] === "coordenador");
        $membroAlvo = null;

        foreach ($membros as $m)
        {
            if ((int)$m["id"] === $usuarioId)
            {
                $membroAlvo = $m;
                break;
            }
        }

        if (!$membroAlvo)
        {
            return ["resposta" => false, "erro" => "Integrante não encontrado no projeto.", "status" => 404];
        }

        $projetoBusca = new Projeto();
        $projetoBusca->setId($projetoId);
        $projeto = $this->repositorySql->buscarId($projetoBusca);

        $ehDonoDoProjeto = ($projeto && (int)$projeto->getUsuario()?->getId() === $usuarioId);
        $ehUnicoCoordenador = ($membroAlvo["papel"] === "coordenador" && count($coordenadores) <= 1);

        if ($ehUnicoCoordenador || $ehDonoDoProjeto)
        {
            if (!$novoCoordenadorId || $novoCoordenadorId === $usuarioId)
            {
                return ["resposta" => false, "erro" => "Selecione outro membro para assumir a coordenação antes de sair.", "status" => 422];
            }

            if ($ehUnicoCoordenador)
            {
                $this->repositorySql->atualizarPapelMembro($projetoId, $novoCoordenadorId, "coordenador");
            }

            if ($ehDonoDoProjeto)
            {
                $this->repositorySql->transferirProprietario($projetoId, $novoCoordenadorId);
            }
        }

        $this->repositorySql->removerMembro($projetoId, $usuarioId);

        return ["resposta" => true];
    }

    public function atualizarVersao(int $projetoId, int $usuarioId, array $componentes, array $codigosManter, array $extrasManter, array $novosCodigos, array $novosCodigosDesc, array $novosExtras, array $novosExtrasDesc, string $nota): bool
    {
        $codigoRepositorio = new CodigoRepositorySql();
        $extraRepositorio = new ExtraRepositorySql();
        $componenteRepositorio = new ComponenteRepositorySql();
        $validador = new ValidadorHelper();

        $versaoAtual = $this->repositorySql->buscarUltimaVersao($projetoId);
        $versaoAtualId = (int)($versaoAtual["id"] ?? 0);

        $idsCodigosBanco = $versaoAtualId > 0
            ? array_values(array_unique(array_map(fn($c) => (int)$c->getId(), $codigoRepositorio->buscarPorVersao($versaoAtualId))))
            : [];
        $idsExtrasBanco = $versaoAtualId > 0
            ? array_values(array_unique(array_map(fn($e) => (int)$e->getId(), $extraRepositorio->buscarPorVersao($versaoAtualId))))
            : [];

        $codigosManter = array_values(array_unique(array_map("intval", $codigosManter)));
        $extrasManter = array_values(array_unique(array_map("intval", $extrasManter)));

        sort($idsCodigosBanco, SORT_NUMERIC);
        sort($codigosManter, SORT_NUMERIC);
        sort($idsExtrasBanco, SORT_NUMERIC);
        sort($extrasManter, SORT_NUMERIC);

        $temNovosCodigos = $validador->possuiArquivos($novosCodigos);
        $temNovosExtras = $validador->possuiArquivos($novosExtras);

        $mapBanco = [];

        if ($versaoAtualId > 0)
        {
            foreach ($componenteRepositorio->buscarPorVersao($versaoAtualId) as $comp)
            {
                $mapBanco[(int)$comp->getId()] = (int)$comp->getQuantidade();
            }
        }

        ksort($mapBanco, SORT_NUMERIC);
        ksort($componentes, SORT_NUMERIC);

        $mudou = $temNovosCodigos || $idsCodigosBanco !== $codigosManter
            || $temNovosExtras || $idsExtrasBanco !== $extrasManter
            || $mapBanco !== $componentes;

        if (!$mudou)
        {
            return false;
        }

        $novaVersaoNum = (int)($versaoAtual["versao"] ?? 0) + 1;
        $nota = $nota !== "" ? $nota : "Atualização para versão v{$novaVersaoNum}";

        $novaVersaoId = (int)$this->repositorySql->criarVersao($projetoId, $usuarioId, $novaVersaoNum, $nota);

        $versaoObj = new Projeto();
        $versaoObj->setId($novaVersaoId);

        foreach ($codigosManter as $codigoId)
        {
            $codigoRepositorio->associarVersao($codigoId, $novaVersaoId);
        }

        if ($temNovosCodigos)
        {
            (new CodigoService())->salvarCodigo($novosCodigos, $novosCodigosDesc, $versaoObj);
        }

        foreach ($extrasManter as $extraId)
        {
            $extraRepositorio->associarVersao($extraId, $novaVersaoId);
        }

        if ($temNovosExtras)
        {
            (new ExtraService())->salvarExtras($novosExtras, $novosExtrasDesc, $versaoObj);
        }

        $componentesObj = [];

        foreach ($componentes as $componenteId => $quantidade)
        {
            $comp = new Componente();
            $comp->setId($componenteId);
            $comp->setQuantidade($quantidade);

            $componentesObj[] = $comp;
        }

        if (!empty($componentesObj))
        {
            (new ComponenteService())->associar(["componentes" => $componentesObj, "projeto" => $versaoObj]);
        }

        return true;
    }
}