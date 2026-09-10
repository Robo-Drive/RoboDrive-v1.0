<?php
$list = [
    [
        "route" => URL_BASE."/usuario/perfil",
        "nome" => "Início",
        "icon" => "home"
    ],
    [
        "route" => URL_BASE."/forum",
        "nome" => "Fórum",
        "icon" => "forum"
    ],
    [
        "route" => URL_BASE."/projeto",
        "nome" => "Projetos públicos",
        "icon" => "projeto"
    ],
    [
        "route" => URL_BASE."/componente",
        "nome" => "Componentes",
        "icon" => "componente"
    ]
];
?>
<div class="rd-sidebar">

    <nav class="flex flex-col gap-2">
        <?php foreach($list as $l): ?>
            <?php $ativo = $_SERVER['REQUEST_URI'] === parse_url($l["route"], PHP_URL_PATH); ?>
            <a href="<?= $l["route"] ?>" class="rd-nav-link <?= $ativo ? 'is-active' : '' ?>">
                <img src="<?= IMG_URL_BASE."/".$l["icon"]."-icon.png"?>" alt="<?= $l["nome"]."icone" ?>">
                <span class="truncate"><?= $l["nome"] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="mt-auto flex w-full flex-col gap-2">
        <form action="<?= URL_BASE ?>/usuario/editar" method="post" class="w-full">
            <input name="id" type="hidden" value="<?= $_SESSION["usuario_logado"]->getId() ?>">
            <button type="submit" class="rd-nav-link w-full">
                <img src="<?= IMG_URL_BASE."/settings-icon.png"?>" alt="configurações">
                Configurações
            </button>
        </form>
        <form action="<?= URL_BASE ?>/logout" method="post" class="w-full">
            <button type="submit" class="rd-nav-link rd-nav-danger w-full">
                <img src="<?= IMG_URL_BASE."/logout-icon.png"?>" alt="sair">
                Sair
            </button>
        </form>
    </div>

</div>
