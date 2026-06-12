<?php
$titulo = "Listagem de Usuários";
$header = "Usuários";
include_once(__DIR__."/../elements/header.php");
?>
<div class="h-[90dvh] w-full grid grid-cols-12 grid-rows-12">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>    
    <table class="w-full col-span-10 row-span-12">
        <tr>
            <th class="bg-blue-500 text-center">Id</th>
            <th class="bg-red-500 text-white text-center">Nome</th>
            <th class="bg-blue-500 text-center">Email</th>
            <th class="bg-red-500 text-white text-center">Senha</th>
            <th class="bg-blue-500 text-center">Imagem</th>
            <th class="bg-red-500 text-white text-center">Regra</th>
            <th class="bg-blue-500 text-center">Criado em</th>
            <th class="bg-red-500 text-white text-center">Visualizar</th>
            <th class="bg-blue-500 text-center">Editar</th>
            <th class="bg-red-500 text-white text-center">Excluir</th>
        </tr>
        <?php if(isset($usuarios)): ?>
        <?php foreach($usuarios as $u):?>
        <tr class="hover:[&>td]:bg-black hover:[&>td]:text-white">
            <td class="bg-blue-500 text-center"><?= $u->getId() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $u->getNome() ?></td>
            <td class="bg-blue-500 text-center"><?= $u->getEmail() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $u->getSenha() ?></td>
            <td class="bg-blue-500 flex justify-center items-center"><img src="<?= $u->getImagem() ?>" alt="Imagem de <?= $u->getNome() ?>" class="h-[100px]"></td>
            <td class="bg-red-500 text-white text-center"><?= ucfirst($u->getRegra()) ?></td>
            <td class="bg-blue-500 text-center"><?= $u->getCriadoEm()?->format('d/m/Y H:i') ?></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/usuario/perfil" method="get" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="w-full h-full">Visualizar</button></form></td>
            <td class="bg-blue-500 text-center"><form action="<?= URL_BASE ?>/usuario/editar" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="w-full h-full">Editar</button></form></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/usuario/excluir" method="post" class="w-full h-full"><input  class="w-full h-full" type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="w-full h-full">Excluir</button></form></td>
        </tr>
        <?php endforeach;?>
        <?php endif; ?>
    </table>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");