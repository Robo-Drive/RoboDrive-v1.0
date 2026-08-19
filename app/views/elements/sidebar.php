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
<div class="bg-black col-span-2 row-span-12 flex flex-col py-3 items-center gap-3 border border-[#00F5F5]">
    <?php foreach($list as $l): ?>
        <a href="<?= $l["route"] ?>" class="h-[50px] w-[90%] border border-2 p-3 text-white  hover:text-[#00F5F5] <?= $_SERVER['REQUEST_URI'] === parse_url($l["route"], PHP_URL_PATH) ? 'border-[#00F5F5]' : 'border-white'?> font-bold flex gap-3"><img src="<?= IMG_URL_BASE."/".$l["icon"] ."-icon.png"?>" alt="<?= $l["nome"]. "icone" ?>"><?= $l["nome"] ?></a>
    <?php endforeach; ?>
    <div class="mt-auto w-full px-4 flex flex-col justify-center gap-3">
        <form action="<?= URL_BASE ?>/usuario/editar" method="post" class="h-[50px] w-full"><input name="id" type="hidden" value="<?= $_SESSION["usuario_logado"]->getId() ?>"><button type="submit" class="h-[50px] w-full border border-2 p-3 text-white  hover:text-[#00F5F5] border-white font-bold flex gap-3"><img src="<?= IMG_URL_BASE."/settings-icon.png"?>" alt="logout">Configurações</button></form>
        <form action="<?= URL_BASE ?>/logout" method="post" class="h-[50px] w-full"><button type="submit" class="h-full w-full border border-2 p-3 text-white  hover:bg-[#00F5F5] font-bold flex gap-3"><img src="<?= IMG_URL_BASE."/logout-icon.png"?>" alt="logout">Sair</button></form>
    </div>
</div>