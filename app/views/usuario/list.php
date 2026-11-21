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

        <!-- BUSCA POR NOME OU NOME DE USUÁRIO -->
        <form data-busca method="get" action="<?= URL_BASE ?>/admin/usuario/listar" class="flex gap-3 px-5 pb-3">
            <input
                type="text"
                name="busca"
                value="<?= htmlspecialchars($busca_atual ?? '') ?>"
                placeholder="Pesquisar usuários pelo nome ou nome de usuário..."
                class="rd-input w-full flex-1"
                autocomplete="off"
            >
            <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">Buscar</button>
        </form>

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
                    <tbody id="resultadosUsuarios" data-atualizar data-resultados>
                        <?php if(isset($usuarios) && count($usuarios) > 0): ?>
                        <?php foreach($usuarios as $u):?>
                        <?php $proprioUsuario = $u->getId() == $_SESSION["usuario_logado"]->getId(); ?>
                        <tr>
                            <td>#<?= $u->getId() ?></td>
                            <td><img src="<?= $u->getImagem() ? URL_BASE."/arquivo?arquivo=".$u->getImagem() : IMG_URL_BASE."/perfil.png" ?>" alt="Foto de <?= texto($u->getNome()) ?>" class="rd-table-thumb"></td>
                            <td><?= texto($u->getNome()) ?></td>
                            <td>@<?= texto($u->getNomeUsuario()) ?></td>
                            <td>
                                <?php if($adminLogado && !$proprioUsuario): ?>
                                    <button type="button" title="Enviar comunicado por e-mail" class="text-left underline decoration-[#07556A] decoration-dashed decoration-2 underline-offset-4 transition-colors duration-200 hover:text-[#13F3F7] hover:decoration-[#13F3F7]" data-comunicado data-id="<?= $u->getId() ?>" data-nome="<?= texto($u->getNome()) ?>" data-email="<?= texto($u->getEmail()) ?>"><?= texto($u->getEmail()) ?></button>
                                <?php else: ?>
                                    <?= texto($u->getEmail()) ?>
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
                                        <?php if($u->isStatus() === false): ?>
                                            <form action="<?= URL_BASE ?>/usuario/ativar" method="post" data-confirmar="Deseja realmente reativar a conta deste usuário?" >
                                                <input type="hidden" name="id" value="<?= $u->getId() ?>">
                                                <button type="submit" class="rd-btn rd-btn-primary rd-btn-sm !bg-emerald-600 hover:!bg-emerald-500 !text-black font-bold">Ativar</button>
                                            </form>
                                        <?php else: ?>
                                            <button type="button" class="rd-btn rd-btn-danger rd-btn-sm" data-desativar data-tipo="usuario" data-id="<?= $u->getId() ?>" data-nome="<?= texto($u->getNome()) ?> (@<?= texto($u->getNomeUsuario()) ?>)" data-responsavel="<?= texto($u->getNome()) ?>">Desativar</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <?php else: ?>
                        <tr><td colspan="9" class="text-center">Nenhum usuário encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>
<?php
include_once(__DIR__."/../elements/adminModais.php");
?>
<script src="<?= js_url("busca.js") ?>"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
