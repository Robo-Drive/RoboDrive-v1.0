<?php
$list = [
    [
        "route" => URL_BASE."/usuario/perfil",
        "nome" => "Início",
        "icon" => "home-icon.png"
    ],
    [
        "route" => URL_BASE."/forum/listar",
        "nome" => "Fórum",
        "icon" => "forum-icon.png"
    ],
    [
        "route" => URL_BASE."/projeto/listar",
        "nome" => "Projetos públicos",
        "icon" => "projeto-icon.png"
    ],
    [
        "route" => URL_BASE."/componente/listar",
        "nome" => "Componentes",
        "icon" => "componente-icon.png"
    ]
];

$listAdmin = [
    [
        "route" => URL_BASE."/admin/denuncia/listar",
        "nome" => "Denúncias",
        "icon" => "denuncia-icon.svg"
    ],
    [
        "route" => URL_BASE."/admin/usuario/listar",
        "nome" => "Usuários",
        "icon" => "usuarios-icon.svg"
    ],
    [
        "route" => URL_BASE."/admin/equipe/listar",
        "nome" => "Equipes",
        "icon" => "usuarios-icon.svg"
    ],
    [
        "route" => URL_BASE."/admin/projeto/listar",
        "nome" => "Projetos",
        "icon" => "projeto-icon.png"
    ],
    [
        "route" => URL_BASE."/admin/forum/listar",
        "nome" => "Fórum",
        "icon" => "forum-icon.png"
    ],
    [
        "route" => URL_BASE."/admin/componente/listar",
        "nome" => "Componentes",
        "icon" => "componente-icon.png"
    ]
];

$secoes = [["titulo" => null, "links" => $list]];
if(isset($_SESSION["usuario_logado"]) && $_SESSION["usuario_logado"]->getRegra() == "admin")
{
    $secoes[] = ["titulo" => "Administração", "links" => $listAdmin];
}
?>
<div id="fundoSidebar" class="fixed inset-x-0 bottom-0 top-[10dvh] z-40 hidden bg-[#000505]/80 lg:!hidden"></div>

<aside id="sidebar" class="rd-sidebar" aria-label="Menu principal">

    <?php foreach($secoes as $secao): ?>
        <?php if($secao["titulo"]): ?>
            <p class="rd-eyebrow mb-0 mt-4 truncate border-t-[3px] border-[#07556A] px-1 pt-4"><?= $secao["titulo"] ?></p>
        <?php endif; ?>
        <nav class="flex flex-col gap-2">
            <?php foreach($secao["links"] as $l): ?>
                <?php $ativo = $_SERVER['REQUEST_URI'] === parse_url($l["route"], PHP_URL_PATH); ?>
                <a href="<?= $l["route"] ?>" class="rd-nav-link <?= $ativo ? 'is-active' : '' ?>" <?= $ativo ? 'aria-current="page"' : '' ?>>
                    <img src="<?= IMG_URL_BASE."/".$l["icon"] ?>" alt="">
                    <span class="truncate"><?= texto($l["nome"]) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    <?php endforeach; ?>

    <?php if(isset($_SESSION["usuario_logado"])): ?>
        <div class="mt-auto flex w-full flex-col gap-2">
            <form action="<?= URL_BASE ?>/usuario/editar" method="post" class="w-full">
                <input name="id" type="hidden" value="<?= $_SESSION["usuario_logado"]->getId() ?>">
                <button type="submit" class="rd-nav-link w-full">
                    <img src="<?= IMG_URL_BASE."/settings-icon.png"?>" alt="">
                    <span class="truncate">Configurações</span>
                </button>
            </form>
            <form action="<?= URL_BASE ?>/logout" method="post" class="w-full">
                <button type="submit" class="rd-nav-link rd-nav-danger w-full">
                    <img src="<?= IMG_URL_BASE."/logout-icon.png"?>" alt="">
                    <span class="truncate">Sair</span>
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="mt-auto flex w-full flex-col gap-2">
            <a href="<?= URL_BASE ?>/login" class="rd-nav-link rd-btn-primary w-full text-center">
                <span class="truncate">Entrar</span>
            </a>
        </div>
    <?php endif; ?>

</aside>
<script src="<?= js_url("sidebar.js") ?>"></script>
