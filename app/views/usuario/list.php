<?php
$titulo = "Listagem de Usuários";
$adminLogado = $_SESSION["usuario_logado"]->getRegra() == "admin";
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
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($usuarios)): ?>
                        <?php foreach($usuarios as $u):?>
                        <?php $proprioUsuario = $u->getId() == $_SESSION["usuario_logado"]->getId(); ?>
                        <tr>
                            <td>#<?= $u->getId() ?></td>
                            <td><img src="<?= $u->getImagem() ? URL_BASE."/arquivo?arquivo=".$u->getImagem() : IMG_URL_BASE."/perfil.png" ?>" alt="Foto de <?= $u->getNome() ?>" class="rd-table-thumb"></td>
                            <td><?= $u->getNome() ?></td>
                            <td>@<?= $u->getNomeUsuario() ?></td>
                            <td>
                                <?php if($adminLogado && !$proprioUsuario): ?>
                                    <button type="button" title="Enviar comunicado por e-mail" class="text-left underline decoration-[#07556A] decoration-dashed decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#13F3F7] hover:decoration-[#13F3F7]" data-comunicado data-id="<?= $u->getId() ?>" data-nome="<?= $u->getNome() ?>" data-email="<?= $u->getEmail() ?>"><?= $u->getEmail() ?></button>
                                <?php else: ?>
                                    <?= $u->getEmail() ?>
                                <?php endif; ?>
                            </td>
                            <td><span class="rd-badge <?= $u->getRegra() == 'admin' ? 'rd-badge-public' : 'rd-badge-team' ?>"><?= ucfirst($u->getRegra()) ?></span></td>
                            <td><span class="rd-badge <?= $u->isStatus() === false ? 'rd-badge-private' : 'rd-badge-team' ?>"><?= $u->isStatus() === false ? "Desativado" : "Ativo" ?></span></td>
                            <td><?= $u->getCriadoEm()?->format('d/m/Y H:i') ?></td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <form action="<?= URL_BASE ?>/usuario/perfil" method="get"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="rd-btn rd-btn-ghost rd-btn-sm">Ver</button></form>
                                    <?php if($adminLogado): ?>
                                        <form action="<?= URL_BASE ?>/usuario/editar" method="post"><input type="hidden" name="id" value="<?= $u->getId() ?>"><button type="submit" class="rd-btn rd-btn-secondary rd-btn-sm">Editar</button></form>
                                        <?php if(!$proprioUsuario): ?>
                                            <button type="button" class="rd-btn rd-btn-danger rd-btn-sm" data-desativar data-tipo="usuario" data-id="<?= $u->getId() ?>" data-nome="<?= $u->getNome() ?> (@<?= $u->getNomeUsuario() ?>)" data-responsavel="<?= $u->getNome() ?>">Desativar</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
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
include_once(__DIR__."/../elements/adminModais.php");
include_once(__DIR__."/../elements/footer.php");
