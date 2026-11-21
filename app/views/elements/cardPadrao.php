<?php
/*
 * Card padrão das listagens (projeto, equipe, fórum, componente). O card inteiro é um link para o perfil.
 * Espera $cardAlvo = [
 *   "rotulo"    => "Projeto",
 *   "tipo"      => "projeto",                 // rota: projeto | equipe | forum | componente
 *   "id"        => 1,
 *   "link"      => URL do perfil,
 *   "titulo"    => texto (sem HTML),
 *   "descricao" => texto (sem HTML),
 *   "meta"      => texto do rodapé,
 *   "imagem"    => URL ou null,
 *   "ativo"     => bool,
 *   "denuncia"  => ["tipo" => ..., "id" => ..., "nome" => ..., "autores" => [ids]] ou null
 * ]
 * O link cobre o card todo (z-10); denunciar e ativar ficam acima dele (z-20), dentro do card.
 */
if(isset($cardAlvo)):
$cardAtivo = $cardAlvo["ativo"] !== false;
?>
<article class="relative flex h-full flex-col gap-3 border-[3px] border-[#07556A] bg-[#06141C] p-4 transition-colors duration-200 hover:border-[#13F3F7] <?= $cardAtivo ? "" : "opacity-80" ?>">
    <a href="<?= $cardAlvo["link"] ?>" class="absolute -inset-[3px] z-10" aria-label="Abrir <?= htmlspecialchars(html_entity_decode($cardAlvo["rotulo"] . ": " . $cardAlvo["titulo"])) ?>"></a>

    <div class="flex flex-wrap items-center gap-2 pr-11">
        <span class="rd-badge rd-badge-public"><?= htmlspecialchars(html_entity_decode($cardAlvo["rotulo"])) ?></span>
        <?php if(!$cardAtivo): ?>
            <span class="rd-badge rd-badge-private">Desativado</span>
        <?php endif; ?>
    </div>

    <?php if(!empty($cardAlvo["imagem"])): ?>
        <img src="<?= $cardAlvo["imagem"] ?>" alt="" class="h-28 w-full border-2 border-[#07556A] object-cover" loading="lazy" onerror="this.onerror=null;this.src='<?= IMG_URL_BASE ?>/projeto-icon.png';">
    <?php endif; ?>

    <h3 class="truncate font-['Orbitron'] text-base font-black text-[#F2FEFE]"><?= htmlspecialchars(html_entity_decode(html_entity_decode($cardAlvo["titulo"]))) ?></h3>
    <p class="line-clamp-3 text-sm leading-relaxed text-[#91B5BD]"><?= htmlspecialchars(html_entity_decode($cardAlvo["descricao"])) ?></p>

    <p class="mt-auto pt-2 text-[.65rem] font-bold uppercase tracking-[.15em] text-[#4E6B72]"><?= htmlspecialchars(html_entity_decode($cardAlvo["meta"])) ?></p>

    <?php
    if(!empty($cardAlvo["denuncia"]))
    {
        $denunciaAlvo = $cardAlvo["denuncia"] + ["classe" => "absolute right-1.5 top-1.5 z-20"];
        include(__DIR__ . "/denunciaBotao.php");
    }

    $ativarAlvo = ["tipo" => $cardAlvo["tipo"], "id" => $cardAlvo["id"], "ativo" => $cardAtivo, "classe" => "relative z-20"];
    include(__DIR__ . "/ativarBotao.php");
    ?>
</article>
<?php endif; ?>
