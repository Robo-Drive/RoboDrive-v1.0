<?php
$titulo = "Listagem de Usuários";
$header = "Usuários";
$menu = [
    [
        "rota" => URL_BASE."/usuario/cadastrar",
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
            <th class="bg-blue-500 text-center">Email</th>
            <th class="bg-red-500 text-white text-center">Senha</th>
            <th class="bg-blue-500 text-center">Imagem</th>
            <th class="bg-red-500 text-white text-center">Regra</th>
            <th class="bg-blue-500 text-center">Criado em</th>
            <th class="bg-red-500 text-white text-center">Visualizar</th>
            <th class="bg-blue-500 text-center">Editar</th>
            <th class="bg-red-500 text-white text-center">Excluir</th>
        </tr>
        <?php foreach($usuarios as $u):?>
        <tr class="hover:[&>td]:bg-black hover:[&>td]:text-white">
            <td class="bg-blue-500 text-center"><?= $u->getId() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $u->getNome() ?></td>
            <td class="bg-blue-500 text-center"><?= $u->getEmail() ?></td>
            <td class="bg-red-500 text-white text-center"><?= $u->getSenha() ?></td>
            <td class="bg-blue-500 flex justify-center items-center"><img src="<?= $u->getImagem() ?>" alt="Imagem de <?= $u->getNome() ?>" class="h-[100px]"></td>
            <td class="bg-red-500 text-white text-center"><?= ucfirst($u->getRegra()) ?></td>
            <td class="bg-blue-500 text-center"><?= $u->getCriadoEm()?->format('d/m/Y H:i') ?></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/id" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit">Visualizar</button></form></td>
            <td class="bg-blue-500 text-center"><form action="<?= URL_BASE ?>/editar" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit">Editar</button></form></td>
            <td class="bg-red-500 text-white text-center"><form action="<?= URL_BASE ?>/excluir" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit">Excluir</button></form></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
<?php
$marquee = "Listagem de usuários do projeto Robo Drive";
include_once(__DIR__."/../elements/footer.php");