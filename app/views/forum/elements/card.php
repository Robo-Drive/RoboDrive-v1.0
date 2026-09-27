<?php if(isset($forum)):?>
<div class="relative bg-black/80 p-4 pr-14 w-[90%] border">
    <p><?= $forum->getUsuario()->getNomeUsuario()??"Gasparzinho" ?></p>
    <p><?= $forum->getConteudo() ?></p>
    <?php
    $adminAlvo = [
        "tipo" => "forum",
        "id" => $forum->getId(),
        "nome" => $forum->getConteudo(),
        "visibilidade" => $forum->getVisibilidade(),
        "responsavel" => $forum->getUsuario()->getNomeUsuario() ? "@".$forum->getUsuario()->getNomeUsuario() : null,
        "compacto" => true
    ];
    include(__DIR__."/../../elements/adminBarra.php");

    $denunciaAlvo = [
        "tipo" => "forum",
        "id" => $forum->getId(),
        "nome" => $forum->getConteudo(),
        "autores" => [$forum->getUsuario()->getId()],
        "classe" => "absolute right-1.5 top-1.5"
    ];
    include(__DIR__."/../../elements/denunciaBotao.php");
    ?>
</div>
<?php endif;?>
