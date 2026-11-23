<?php
$titulo = "Perfil do Componente";
if (isset($componente)):
    include_once(__DIR__ . "/../elements/header.php");

    $usuarioLogado = $_SESSION["usuario_logado"] ?? null;
    $eAdmin = ($usuarioLogado && $usuarioLogado->getRegra() === "admin");
    $autorId = $componente->getUsuario() ? $componente->getUsuario()->getId() : null;
    $eAutor = ($usuarioLogado && $autorId && $usuarioLogado->getId() === $autorId);
    $componenteAtivo = ($componente->isStatus() !== false);

    $nomeFormatado = texto($componente->getNome() ?? 'Componente');
    $descricaoFormatada = nl2br(texto($componente->getDescricao() ?? 'Sem descrição cadastrada.'));
    $nomeUsuarioFormatado = texto($componente->getUsuario()?->getNomeUsuario() ?? 'Desconhecido');
    $imagemUrl = !empty($componente->getImagem())
        ? URL_BASE . "/arquivo?arquivo=" . urlencode($componente->getImagem())
        : IMG_URL_BASE . "/projeto-icon.png";
?>
<div class="rd-shell">
    <?php include_once(__DIR__ . "/../elements/sidebar.php") ?>

    <main class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden p-4 sm:p-8 w-full max-w-5xl mx-auto space-y-6">

        <!-- CABEÇALHO COM BOTÕES DE AÇÃO -->
        <div class="border-b-2 border-[#07556A] pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="rd-eyebrow">BIBLIOTECA DE HARDWARE</p>
                <h1 class="font-['Orbitron'] text-xl sm:text-3xl font-black text-[#F2FEFE]">
                    <?= $nomeFormatado ?>
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <?php if (!$componenteAtivo): ?>
                    <span class="rd-badge rd-badge-private">Desativado</span>
                    <?php
                    $ativarAlvo = ["tipo" => "componente", "id" => (int) $componente->getId(), "ativo" => false];
                    include(__DIR__ . "/../elements/ativarBotao.php");
                    ?>
                <?php endif; ?>

                <?php if (($eAdmin || $eAutor) && $componenteAtivo): ?>
                    <form action="<?= URL_BASE ?>/componente/editar" method="post">
                        <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                        <button type="submit" class="rd-btn rd-btn-primary rd-btn-sm">
                            Editar
                        </button>
                    </form>

                    <?php if ($eAutor): ?>
                        <form action="<?= URL_BASE ?>/componente/desativar" method="post" data-confirmar="Deseja realmente desativar este componente?" >
                            <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                            <button type="submit" class="rd-btn rd-btn-danger rd-btn-sm">
                                Desativar
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= URL_BASE ?>/componente/listar" class="rd-btn rd-btn-ghost">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" aria-hidden="true"><polyline points="15 5 8 12 15 19"/></svg>
                Voltar
                </a>

                <?php
                $denunciaAlvo = [
                    "tipo"    => "componente",
                    "id"      => $componente->getId(),
                    "nome"    => $componente->getNome(),
                    "autores" => $autorId ? [$autorId] : [],
                    "classe"  => "shrink-0"
                ];
                if ($componenteAtivo) {
                    include(__DIR__ . "/../elements/denunciaBotao.php");
                }
                ?>
            </div>
        </div>

        <!-- CONTEÚDO PRINCIPAL DO PERFIL -->
        <div id="conteudoComponente" data-atualizar class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- FOTO EM DESTAQUE -->
            <div class="lg:col-span-5 border-2 border-[#07556A] bg-[#06141C] p-4 flex flex-col items-center justify-center">
                <!-- Card como aparece na listagem -->
                <div class="relative aspect-video w-full overflow-hidden bg-[#082B3A]">
                    <div class="absolute inset-0 z-0">
                        <img src="<?= $imagemUrl ?>" alt="" aria-hidden="true" class="h-full w-full border-[3px] border-[#07556A] object-cover opacity-20">
                    </div>
                    <div class="relative z-10 flex h-full w-full flex-col justify-between p-4">
                        <h3 class="truncate text-left font-['Orbitron'] text-lg font-black text-[#F2FEFE]"><?= $nomeFormatado ?></h3>
                        <p class="line-clamp-3 text-sm text-[#91B5BD]"><?= texto($componente->getDescricao()) ?></p>
                        <p class="text-right text-[.65rem] font-bold uppercase tracking-[.15em] text-[#91B5BD]">Cadastrado por @<?= $nomeUsuarioFormatado ?></p>
                    </div>
                </div>

                <!-- Imagem limpa, no mesmo formato do card (16:9) -->
                <div class="mt-4 aspect-video w-full overflow-hidden border border-[#07556A] bg-[#000505]">
                    <img
                        src="<?= $imagemUrl ?>"
                        alt="Foto do componente <?= $nomeFormatado ?>"
                        class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                        onerror="this.onerror=null;this.src='<?= IMG_URL_BASE ?>/projeto-icon.png';"
                    >
                </div>

                <div class="w-full mt-4 pt-3 border-t border-[#07556A]/60 flex items-center justify-between text-xs text-[#91B5BD]">
                    <span>Cadastrado por:</span>
                    <?php if ($autorId): ?>
                        <a href="<?= URL_BASE ?>/usuario/perfil?id=<?= $autorId ?>" class="font-bold text-[#13F3F7] hover:underline">
                            @<?= $nomeUsuarioFormatado ?>
                        </a>
                    <?php else: ?>
                        <span class="font-bold text-[#13F3F7]">@<?= $nomeUsuarioFormatado ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- DETALHES E ESPECIFICAÇÃO DO COMPONENTE -->
            <div class="lg:col-span-7 space-y-6">

                <!-- DESCRIÇÃO COMPLETA -->
                <div class="border-2 border-[#07556A] bg-[#06141C] p-5">
                    <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron'] mb-3">
                        SOBRE O COMPONENTE
                    </h2>
                    <div class="text-[#F2FEFE] text-sm leading-relaxed whitespace-pre-line">
                        <?= $descricaoFormatada ?>
                    </div>
                </div>

                <!-- PAINEL ADMINISTRATIVO (SE FOR ADMIN) -->
                <?php if ($eAdmin && !$eAutor && $componenteAtivo): ?>
                    <div class="w-full">
                        <?php
                        $adminAlvo = [
                            "tipo"        => "componente",
                            "id"          => $componente->getId(),
                            "nome"        => $componente->getNome(),
                            "responsavel" => "@" . $nomeUsuarioFormatado
                        ];
                        include(__DIR__ . "/../elements/adminBarra.php");
                        ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </main>
</div>

<?php
include_once(__DIR__ . "/../elements/adminModais.php");
include_once(__DIR__ . "/../elements/denunciaModal.php");
include_once(__DIR__ . "/../elements/footer.php");
endif;
?>