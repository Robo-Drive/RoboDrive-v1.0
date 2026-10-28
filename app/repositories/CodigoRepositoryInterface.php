<?php

namespace app\repositories;

interface CodigoRepositoryInterface
{
    public function cadastrar(array $posts): bool;
}