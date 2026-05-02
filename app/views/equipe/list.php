<?php
$titulo = "Listagem de Equipes";
$header = "Equipes";
$menu = [
    [
        "rota" => URL_BASE."/home",
        "nome" => "Home"
    ],
    [
        "rota" => URL_BASE."/equipe/cadastrar",
        "nome" => "Cadastro"
    ]
];
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[80dvh] w-full ">
    <table class="w-full ">
        <tr>
            <th class="bg-blue-500 text-center">Id</th>
            <th class="bg-red-500 text-white text-center">Nome</th>
            <th class="bg-blue-500 text-center">Senha</th>
            <th class="bg-red-500 text-white text-center">Visualizar</th>
            <th class="bg-blue-500 text-center">Editar</th>
            <th class="bg-red-500 text-white text-center">Excluir</th>
        </tr>
        <?php if(isset($equipes)):?>
        <?php foreach($equipes as $e):?>
        <tr class="hover:[&>td]:bg-black hover:[&>td]:text-white">
            <td class="bg-blue-500 text-center"><?= $e->getId() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $e->getNome() ?></td>
            <td class="bg-blue-500 text-center"><?= $e->getSenha() ?></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/equipe/perfil" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="w-full h-full">Visualizar</button></form></td>
            <td class="bg-blue-500 text-center"><form action="<?= URL_BASE ?>/equipe/editar" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="w-full h-full">Editar</button></form></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/equipe/excluir" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="w-full h-full">Excluir</button></form></td>
        </tr>
        <?php endforeach;?>
        <?php endif; ?>
    </table>
</div>
<?php
$marquee = "Listagem de equipes do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");