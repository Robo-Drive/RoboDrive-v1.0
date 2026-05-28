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
<div class="bg-black col-span-2 row-span-12 flex flex-col py-3 items-center gap-3 border border-[#FF1A1A]">
    <?php foreach($list as $l): ?>
        <a href="<?= $l["route"] ?>" class="h-[50px] w-[90%] border border-2 p-3 text-white  hover:text-[#FF1A1A] <?= $_SERVER['REQUEST_URI'] === parse_url($l["route"], PHP_URL_PATH) ? 'border-[#FF1A1A]' : 'border-white'?> font-bold flex gap-3"><img src="<?= IMG_URL_BASE."/".$l["icon"] ."-icon.png"?>" alt="<?= $l["nome"]. "icone" ?>"><?= $l["nome"] ?></a>
    <?php endforeach; ?>
</div>