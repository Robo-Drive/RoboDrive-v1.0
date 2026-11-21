<?php if(isset($forum)):
$autorForum = $forum->getUsuario();
$cardAlvo = [
    "rotulo"    => "Postagem",
    "tipo"      => "forum",
    "id"        => (int) $forum->getId(),
    "link"      => URL_BASE . "/forum/perfil?id=" . (int) $forum->getId(),
    "titulo"    => "@" . ($autorForum?->getNomeUsuario() ?? "usuario"),
    "descricao" => html_entity_decode($forum->getConteudo() ?? ""),
    "meta"      => trim(($forum->getCriadoEm()?->format("d/m/Y H:i") ?? "") . " · " . ucfirst($forum->getVisibilidade() ?? "publico"), " ·"),
    "imagem"    => null,
    "ativo"     => $forum->isStatus(),
    "denuncia"  => [
        "tipo"    => "forum",
        "id"      => (int) $forum->getId(),
        "nome"    => mb_strimwidth($forum->getConteudo() ?? "", 0, 50, "..."),
        "autores" => $autorForum ? [$autorForum->getId()] : []
    ]
];
include(__DIR__ . "/../../elements/cardPadrao.php");
endif; ?>
