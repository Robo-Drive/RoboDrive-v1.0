<?php

if(isset($ativarAlvo) && isset($_SESSION["usuario_logado"]) && $_SESSION["usuario_logado"]->getRegra() == "admin" && $ativarAlvo["ativo"] === false):
?>
<form action="<?= URL_BASE ?>/<?= $ativarAlvo["tipo"] ?>/ativar" method="post" onsubmit="return confirm('Deseja realmente reativar este item?');" class="<?= $ativarAlvo["classe"] ?? "" ?>">
    <input type="hidden" name="id" value="<?= $ativarAlvo["id"] ?>">
    <button type="submit" class="rd-btn rd-btn-primary rd-btn-sm !bg-emerald-600 hover:!bg-emerald-500 !text-black font-bold">Ativar</button>
</form>
<?php endif; ?>
