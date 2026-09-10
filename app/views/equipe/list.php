<?php
$titulo = "Listagem de Equipes";
include_once(__DIR__."/../elements/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="rd-eyebrow">COLABORAÇÃO</p>
                <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">EQUIPES <span>CADASTRADAS</span></h1>
            </div>
            <a href="<?= URL_BASE ?>/equipe/cadastro" class="rd-btn rd-btn-primary w-fit">Nova equipe</a>
        </section>

        <section class="rd-section">
            <div class="rd-table-wrap">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($equipes)):?>
                        <?php foreach($equipes as $e):?>
                        <tr>
                            <td>#<?= $e->getId() ?></td>
                            <td><?= $e->getNome() ?></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <form action="<?= URL_BASE ?>/equipe/perfil" method="get"><input type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="rd-btn rd-btn-ghost rd-btn-sm">Ver</button></form>
                                    <form action="<?= URL_BASE ?>/equipe/editar" method="post"><input type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Editar</button></form>
                                    <form action="<?= URL_BASE ?>/equipe/excluir" method="post"><input type="hidden" name="id" value="<?= $e->getId() ?>"><button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">Excluir</button></form>
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
