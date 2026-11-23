<?php
$titulo = "Perfil do Componente";
if (isset($componente)):
    include_once(__DIR__ . "/../elements/header.php");

    $usuarioLogado = $_SESSION["usuario_logado"] ?? null;
    $eAdmin = ($usuarioLogado && $usuarioLogado->getRegra() === "admin");
    $autorId = $componente->getUsuario() ? $componente->getUsuario()->getId() : null;
    $eAutor = ($usuarioLogado && $autorId && $usuarioLogado->getId() === $autorId);

    // Formatações contra script injection mantendo acentuação e quebras de linha
    $nomeFormatado = htmlspecialchars(html_entity_decode($componente->getNome() ?? 'Componente', ENT_QUOTES, 'UTF-8'));
    $descricaoFormatada = nl2br(htmlspecialchars(html_entity_decode($componente->getDescricao() ?? 'Sem descrição cadastrada.', ENT_QUOTES, 'UTF-8')));
    $nomeUsuarioFormatado = htmlspecialchars(html_entity_decode($componente->getUsuario()?->getNomeUsuario() ?? 'Desconhecido', ENT_QUOTES, 'UTF-8'));
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

            <div class="flex items-center gap-3">
                <?php if ($eAdmin || $eAutor): ?>
                    <form action="<?= URL_BASE ?>/componente/editar" method="post">
                        <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                        <button type="submit" class="rd-btn rd-btn-primary !py-2 !px-4 text-xs">
                            Editar
                        </button>
                    </form>

                    <form action="<?= URL_BASE ?>/componente/desativar" method="post" data-confirmar="Deseja realmente desativar ou desativar este componente?">
                        <input type="hidden" name="id" value="<?= $componente->getId() ?>">
                        <button type="submit" class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition">
                            Desativar
                        </button>
                    </form>
                <?php endif; ?>

                <a href="<?= URL_BASE ?>/componente" class="border-2 border-[#07556A] px-4 py-2 text-xs font-bold text-[#F2FEFE] hover:border-[#13F3F7] transition">
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
                include(__DIR__ . "/../elements/denunciaBotao.php");
                ?>
            </div>
        </div>

        <!-- CONTEÚDO PRINCIPAL DO PERFIL -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- FOTO EM DESTAQUE -->
            <div class="lg:col-span-5 border-2 border-[#07556A] bg-[#06141C] p-4 flex flex-col items-center justify-center">
                <div class="w-full aspect-square bg-[#000505] border border-[#07556A] overflow-hidden flex items-center justify-center">
                    <img 
                        src="<?= $imagemUrl ?>" 
                        alt="Foto do componente <?= $nomeFormatado ?>" 
                        class="w-full h-full object-contain p-2 hover:scale-105 transition-transform duration-300"
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
                <div class="border-2 border-[#07556A] bg-[#000505] p-5">
                    <h2 class="text-xs font-black uppercase tracking-[.15em] text-[#13F3F7] font-['Orbitron'] mb-3">
                        SOBRE O COMPONENTE
                    </h2>
                    <div class="text-[#F2FEFE] text-sm leading-relaxed whitespace-pre-line">
                        <?= $descricaoFormatada ?>
                    </div>
                </div>

                <!-- PAINEL ADMINISTRATIVO (SE FOR ADMIN) -->
                <?php if ($eAdmin): ?>
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