<?php 

namespace app\services;

use app\models\Projeto;
use app\repositories\ProjetoRepository;

class ProjetoService {

    private ProjetoRepository $repository;

    public function __construct(){

        $this->repository = new ProjetoRepository();

    }

    public function getProjetos(){
        return $this->repository->getProjetos();
    
    }

    public function getProjeto(int $id){
        return $this->repository->getProjeto($id);
    }

    public function saveProjeto(Projeto $projeto){
        return $this->repository->saveProjeto($projeto);
    }

    public function nomeJaExiste(string $nome, ?int $excludeId = null): bool{
        return $this->repository->nomeJaExiste($nome, $excludeId);
    }

    public function updateProjeto(int $id, Projeto $projeto){
        return $this->repository->updateProjeto($id, $projeto);
    }

    public function deleteProjeto(int $id){
        return $this->repository->deleteProjeto($id);
    }

}
