<?php
/*
 * Espera $adminAlvo = ["tipo" => ..., "id" => ..., "nome" => ..., "visibilidade" => ?, "responsavel" => ?, "compacto" => ?]
 * "tipo" segue as rotas do sistema (usuario, projeto, forum, comentario, componente, equipe).
 */
if(isset($adminAlvo) && $_SESSION["usuario_logado"]->getRegra() == "admin"):
$visibilidadesAdmin = [
    "privado" => ["rotulo" => "Restrito ao time", "classe" => "rd-badge-private"],
    "equipe"  => ["rotulo" => "Visível à equipe", "classe" => "rd-badge-team"],
    "publico" => ["rotulo" => "Global",           "classe" => "rd-badge-public"]
];
$visibilidadeAlvo = $visibilidadesAdmin[$adminAlvo["visibilidade"] ?? ""] ?? null;
$barraCompacta = $adminAlvo["compacto"] ?? false;
?>
<div class="<?= $barraCompacta ? 'mt-4 border-t-2 pt-3' : 'mb-6 border-[3px] bg-[#06141C] p-4' ?> flex flex-col gap-3 border-[#07556A] text-left sm:flex-row sm:items-center sm:justify-between">
    <div class="flex flex-wrap items-center gap-3">
        <p class="rd-eyebrow mb-0">Visão de administrador</p>
        <?php if($visibilidadeAlvo): ?>
            <span class="rd-badge <?= $visibilidadeAlvo["classe"] ?>"><?= $visibilidadeAlvo["rotulo"] ?></span>
        <?php endif; ?>
        <?php if(!$barraCompacta): ?>
            <p class="w-full text-xs text-[#91B5BD]">Como administrador, você tem acesso a este conteúdo independente da visibilidade definida.</p>
        <?php endif; ?>
    </div>
    <button
        type="button"
        class="rd-btn rd-btn-danger rd-btn-sm w-fit shrink-0"
        data-desativar
        data-tipo="<?= $adminAlvo["tipo"] ?>"
        data-id="<?= $adminAlvo["id"] ?>"
        data-nome="<?= $adminAlvo["nome"] ?>"
        data-responsavel="<?= $adminAlvo["responsavel"] ?? "" ?>"
    >Desativar</button>
</div>
<?php endif; ?>
