<?php if(isset($equipe)):
$cardAlvo = [
    "rotulo"    => "Equipe",
    "tipo"      => "equipe",
    "id"        => (int) $equipe->getId(),
    "link"      => URL_BASE . "/equipe/perfil?id=" . (int) $equipe->getId(),
    "titulo"    => html_entity_decode($equipe->getNome() ?? "Equipe"),
    "descricao" => html_entity_decode($equipe->getDescricao() ?? ""),
    "meta"      => "Equipe",
    "imagem"    => null,
    "ativo"     => $equipe->isStatus(),
    "denuncia"  => [
        "tipo"    => "equipe",
        "id"      => (int) $equipe->getId(),
        "nome"    => $equipe->getNome(),
        "autores" => [$usuario->getId()]
    ]
];
include(__DIR__ . "/../../elements/cardPadrao.php");
endif; ?>
