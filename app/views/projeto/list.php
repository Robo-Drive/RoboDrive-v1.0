<?php
$titulo = "Listagem de Projetos";
$header = "Projetos";

include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <table class="w-full col-span-10">
        <tr>
            <th class="bg-blue-500 text-center">Id</th>
            <th class="bg-red-500 text-white text-center">Nome</th>
            <th class="bg-blue-500 text-center">Descrição</th>
            <th class="bg-red-500 text-white text-center">Visibilidade</th>
            <th class="bg-blue-500 text-center">Criado em</th>
            <th class="bg-red-500 text-white text-center">Visualizar</th>
            <th class="bg-blue-500 text-center">Editar</th>
            <th class="bg-red-500 text-white text-center">Excluir</th>
        </tr>
        <?php if(isset($projetos)):?>
        <?php foreach($projetos as $p):?>
        <tr class="hover:[&>td]:bg-black hover:[&>td]:text-white">
            <td class="bg-blue-500 text-center"><?= $p->getId() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $p->getNome() ?></td>
            <td class="bg-blue-500 text-center"><?= $p->getDescricao() ?></td>
            <td class="bg-red-500 text-center text-white"><?= $p->getVisibilidade() ?></td>
            <td class="bg-blue-500 text-center"><?= $p->getCriadoEm()?->format('d/m/Y H:i') ?></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/projeto/perfil" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $p->getId() ?>"><button type="submit" class="w-full h-full">Visualizar</button></form></td>
            <td class="bg-blue-500 text-center"><form action="<?= URL_BASE ?>/projeto/editar" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $p->getId() ?>"><button type="submit" class="w-full h-full">Editar</button></form></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/projeto/excluir" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $p->getId() ?>"><button type="submit" class="w-full h-full">Excluir</button></form></td>
        </tr>
        <?php endforeach;?>
        <?php endif; ?>
    </table>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");