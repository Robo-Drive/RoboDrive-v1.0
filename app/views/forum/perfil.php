<?php
$titulo = "Postagem do Fórum";
$header = "Fórum";
if (isset($forum)):
include_once(__DIR__ . "/../elements/header.php");

$usuarioLogado = $_SESSION["usuario_logado"];
$eAdmin = ($usuarioLogado->getRegra() === "admin");
$eAutor = ($forum->getUsuario()->getId() === $usuarioLogado->getId());
?>

<div class="rd-shell">
    <?php include_once(__DIR__ . "/../elements/sidebar.php"); ?>

    <main class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden p-4 sm:p-8 w-full max-w-4xl mx-auto space-y-6">

        <!-- CABEÇALHO -->
        <div class="flex items-center justify-between border-b-2 border-[#07556A] pb-4">
            <div>
                <p class="rd-eyebrow">FÓRUM</p>
                <h1 class="font-['Orbitron'] text-xl sm:text-2xl font-black text-[#F2FEFE]">
                    POSTAGEM DA COMUNIDADE
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <?php if ($eAdmin || $eAutor): ?>
                    <form action="<?= URL_BASE ?>/forum/editar" method="post">
                        <input type="hidden" name="id" value="<?= $forum->getId() ?>">
                        <button type="submit" class="rd-btn rd-btn-secondary !py-2 !px-4 text-xs">
                            Editar
                        </button>
                    </form>

                    <form action="<?= URL_BASE ?>/forum/excluir" method="post" onsubmit="return confirm('Deseja realmente excluir esta postagem?');">
                        <input type="hidden" name="id" value="<?= $forum->getId() ?>">
                        <button type="submit" class="border-2 border-[#F2A058] text-[#F2A058] hover:bg-[#F2A058]/10 px-4 py-2 text-xs font-bold transition">
                            Excluir
                        </button>
                    </form>
                <?php endif; ?>

                <a href="<?= URL_BASE ?>/forum" class="border-2 border-[#07556A] px-4 py-2 text-xs font-bold text-[#F2FEFE] hover:border-[#13F3F7] transition">
                    Voltar
                </a>
            </div>
        </div>

        <!-- CARD PRINCIPAL DA POSTAGEM -->
        <article class="border-2 border-[#07556A] bg-[#06141C] p-6 space-y-6 relative">
            
            <!-- AUTOR E DATA -->
            <div class="flex items-center justify-between border-b border-[#07556A]/60 pb-4">
                <div class="flex items-center gap-3">
                    <img 
                        src="<?= IMG_URL_BASE ?>/perfil.png" 
                        alt="Foto do autor" 
                        class="w-10 h-10 object-cover border border-[#07556A]"
                    >
                    <div>
                        <p class="text-sm font-bold text-[#F2FEFE]">
                            @<?= htmlspecialchars($forum->getUsuario()->getNomeUsuario() ?? 'Usuário') ?>
                        </p>
                        <span class="text-[.65rem] text-[#91B5BD] font-mono">
                            Publicado em <?= $forum->getCriadoEm() ? $forum->getCriadoEm()->format('d/m/Y H:i') : 'Data não informada' ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-[.65rem] font-bold uppercase tracking-wider px-2 py-0.5 border border-[#07556A] text-[#13F3F7] bg-[#000505]">
                        <?= htmlspecialchars(ucfirst($forum->getVisibilidade() ?? 'Público')) ?>
                    </span>

                    <?php
                    // Botão de denúncia (não aparece para o próprio autor)
                    $denunciaAlvo = [
                        "tipo"    => "forum",
                        "id"      => $forum->getId(),
                        "nome"    => mb_strimwidth($forum->getConteudo(), 0, 50, '...'),
                        "autores" => [$forum->getUsuario()->getId()],
                        "classe"  => "shrink-0"
                    ];
                    include(__DIR__ . "/../elements/denunciaBotao.php");
                    ?>
                </div>
            </div>

            <!-- CONTEÚDO DA POSTAGEM -->
            <div class="text-[#F2FEFE] text-base leading-relaxed whitespace-pre-line bg-[#000505] p-5 border border-[#07556A]/60">
                <?= htmlspecialchars(html_entity_decode($forum->getConteudo())) ?>
            </div>

        </article>

    </main>
</div>

<?php
include_once(__DIR__ . "/../elements/adminModais.php");
include_once(__DIR__ . "/../elements/denunciaModal.php");
include_once(__DIR__ . "/../elements/footer.php");
endif;
?>