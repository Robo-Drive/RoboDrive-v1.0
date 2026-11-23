<?php if(isset($equipe)):
$cardAlvo = [
    "rotulo"    => "Equipe",
    "tipo"      => "equipe",
    "id"        => (int) $equipe->getId(),
    "link"      => URL_BASE . "/equipe/perfil?id=" . (int) $equipe->getId(),
    "titulo"    => html_entity_decode($equipe->getNome() ?? "Equipe"),
    "descricao" => html_entity_decode($equipe->getDescricao() ?? ""),
    "meta"      => "Equipe",
    "imagem"    => IMG_URL_BASE . "/team-icon.png",
    "ativo"     => $equipe->isStatus(),
    "denuncia"  => null
];
include(__DIR__ . "/../../elements/cardPadrao.php");
endif; ?>
