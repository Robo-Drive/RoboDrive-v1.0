<?php
$titulo = "Listagem de Usuários";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section">
            <p class="rd-eyebrow">ADMINISTRAÇÃO</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">USUÁRIOS <span>CADASTRADOS</span></h1>
        </section>

        <section class="rd-section">
            <div class="rd-table-wrap">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Regra</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($usuarios)): ?>
                        <?php foreach($usuarios as $u):?>
                        <tr>
                            <td>#<?= $u->getId() ?></td>
                            <td><img src="<?= $u->getImagem() ? URL_BASE."/arquivo?arquivo=".$u->getImagem() : IMG_URL_BASE."/perfil.png" ?>" alt="Foto de <?= $u->getNome() ?>" class="rd-table-thumb"></td>
                            <td><?= $u->getNome() ?></td>
                            <td>@<?= $u->getNomeUsuario() ?></td>
                            <td><?= $u->getEmail() ?></td>
                            <td><span class="rd-badge <?= $u->getRegra() == 'admin' ? 'rd-badge-public' : 'rd-badge-team' ?>"><?= ucfirst($u->getRegra()) ?></span></td>
                            <td><?= $u->getCriadoEm()?->format('d/m/Y H:i') ?></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <form action="<?= URL_BASE ?>/usuario/perfil" method="get"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="rd-btn rd-btn-ghost rd-btn-sm">Ver</button></form>
                                    <form action="<?= URL_BASE ?>/usuario/editar" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Editar</button></form>
                                    <form action="<?= URL_BASE ?>/usuario/excluir" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">Excluir</button></form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/footer.php");
