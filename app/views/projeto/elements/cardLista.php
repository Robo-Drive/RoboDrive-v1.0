<?php if(isset($projeto)):
$cardAlvo = [
    "rotulo"    => "Projeto",
    "tipo"      => "projeto",
    "id"        => (int) $projeto->getId(),
    "link"      => URL_BASE . "/projeto/perfil?id=" . (int) $projeto->getId(),
    "titulo"    => html_entity_decode($projeto->getNome() ?? "Projeto"),
    "descricao" => html_entity_decode($projeto->getDescricao() ?? ""),
    "meta"      => $projeto->getCriadoEm()?->format("d/m/Y") ?? "",
    "imagem"    => null,
    "ativo"     => $projeto->isStatus(),
    "denuncia"  => [
        "tipo"    => "projeto",
        "id"      => (int) $projeto->getId(),
        "nome"    => $projeto->getNome(),
        "autores" => $projeto->getUsuario()?->getId() ? [$projeto->getUsuario()->getId()] : []
    ]
];
include(__DIR__ . "/../../elements/cardPadrao.php");
endif; ?>
