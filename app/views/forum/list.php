<?php
$titulo = "Listagem de forums";
$header = "Forums";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <table class="w-full col-span-10">
        <tr>
            <th class="bg-red-500 text-white text-center">Id</th>
            <th class="bg-blue-500 text-center">Conteudo</th>
            <th class="bg-red-500 text-white text-center">Visibilidade</th>
            <th class="bg-blue-500 text-center">Usuario Id</th>
            <th class="bg-red-500 text-white text-center">Visualizar</th>
            <th class="bg-blue-500 text-center">Editar</th>
            <th class="bg-red-500 text-white text-center">Excluir</th>
        </tr>
        <?php if(isset($foruns)):?>
        <?php foreach($foruns as $f):?>
        <tr class="hover:[&>td]:bg-black hover:[&>td]:text-white">
            <td class="bg-red-500 text-white text-center"><?= $f->getId() ?></td>
            <td class="bg-blue-500 text-center"><?= $f->getConteudo() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $f->getVisibilidade() ?></td>
            <td class="bg-blue-500 text-center"><?= $f->getUsuarioId() ?></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/forum/perfil" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $f->getId() ?>"><button type="submit" class="w-full h-full">Visualizar</button></form></td>
            <td class="bg-blue-500 text-center"><form action="<?= URL_BASE ?>/forum/editar" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $f->getId() ?>"><button type="submit" class="w-full h-full">Editar</button></form></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/forum/excluir" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $f->getId() ?>"><button type="submit" class="w-full h-full">Excluir</button></form></td>
        </tr>
        <?php endforeach;?>
        <?php endif; ?>
    </table>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");