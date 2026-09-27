<?php
/*
 * Espera $denuncias: um item por conteúdo denunciado (denúncias agrupadas), no formato:
 * [
 *   "tipo"                => "usuario" | "projeto" | "forum" | "comentario" | "componente" | "equipe",
 *   "alvo_id"             => int,
 *   "alvo_nome"           => string (nome do item ou trecho do texto, no caso de postagem/comentário),
 *   "quantidade"          => int (total de denúncias do item),
 *   "primeira_denuncia"   => "Y-m-d H:i:s",
 *   "motivos"             => ["Spam" => 4, "Conteúdo ofensivo" => 2],
 *   "responsavel_id"      => int,
 *   "responsavel_nome"    => string,
 *   "responsavel_usuario" => string (nome de usuário, sem @),
 *   "responsavel_email"   => string
 * ]
 * A ordem (mais denúncias primeiro; empate = mais antiga primeiro) também é garantida por denuncia.js.
 */
$titulo = "Denúncias";
include_once(__DIR__."/../elements/header.php");

$denuncias = $denuncias ?? [];
$totalDenuncias = array_sum(array_column($denuncias, "quantidade"));
$datasDenuncias = array_map(fn($d) => strtotime($d["primeira_denuncia"]), $denuncias);
$maisAntiga = $datasDenuncias ? min($datasDenuncias) : null;
$quantidadePorTipo = array_count_values(array_column($denuncias, "tipo"));

$tiposDenuncia = [
    "usuario"    => ["rotulo" => "Usuário",    "plural" => "Usuários",    "rota" => "/usuario/perfil",    "metodo" => "get"],
    "projeto"    => ["rotulo" => "Projeto",    "plural" => "Projetos",    "rota" => "/projeto/perfil",    "metodo" => "get"],
    "forum"      => ["rotulo" => "Postagem",   "plural" => "Postagens",   "rota" => "/forum/perfil",      "metodo" => "post"],
    "comentario" => ["rotulo" => "Comentário", "plural" => "Comentários", "rota" => null,                  "metodo" => null],
    "componente" => ["rotulo" => "Componente", "plural" => "Componentes", "rota" => "/componente/perfil", "metodo" => "get"],
    "equipe"     => ["rotulo" => "Equipe",     "plural" => "Equipes",     "rota" => "/equipe/perfil",     "metodo" => "get"]
];
?>
<div class="rd-shell">
    <?php include_once(__DIR__."/../elements/sidebar.php") ?>
    <div class="rd-content rd-scroll-hidden">

        <section class="rd-section">
            <p class="rd-eyebrow">ADMINISTRAÇÃO</p>
            <h1 class="rd-heading text-[clamp(1.8rem,4vw,2.8rem)]">DENÚNCIAS <span>RECEBIDAS</span></h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-[#91B5BD]">
                Os itens mais denunciados aparecem primeiro; em caso de empate, os mais antigos têm prioridade.
                Ao desativar um item, o responsável recebe um e-mail com a justificativa e pode recorrer da decisão.
            </p>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rd-card !p-5">
                    <p class="rd-eyebrow">Itens denunciados</p>
                    <p class="font-['Orbitron'] text-3xl font-black text-[#F2FEFE]"><?= count($denuncias) ?></p>
                </div>
                <div class="rd-card !p-5">
                    <p class="rd-eyebrow">Denúncias recebidas</p>
                    <p class="font-['Orbitron'] text-3xl font-black text-[#13F3F7]"><?= $totalDenuncias ?></p>
                </div>
                <div class="rd-card !p-5">
                    <p class="rd-eyebrow">Pendente desde</p>
                    <p class="font-['Orbitron'] text-3xl font-black text-[#F2FEFE]"><?= $maisAntiga ? date("d/m/Y", $maisAntiga) : "—" ?></p>
                </div>
            </div>
        </section>

        <section class="rd-section">
            <?php if(count($denuncias) > 0): ?>
                <div class="mb-6 flex flex-wrap gap-2" role="group" aria-label="Filtrar denúncias por tipo">
                    <button type="button" data-filtro="todos" aria-pressed="true" class="rd-chip aria-pressed:border-[#13F3F7] aria-pressed:bg-[#13F3F7]/10 aria-pressed:text-[#13F3F7]">
                        Todos <span class="text-[#91B5BD]"><?= count($denuncias) ?></span>
                    </button>
                    <?php foreach($quantidadePorTipo as $tipo => $quantidade): ?>
                        <button type="button" data-filtro="<?= $tipo ?>" aria-pressed="false" class="rd-chip aria-pressed:border-[#13F3F7] aria-pressed:bg-[#13F3F7]/10 aria-pressed:text-[#13F3F7]">
                            <?= $tiposDenuncia[$tipo]["plural"] ?> <span class="text-[#91B5BD]"><?= $quantidade ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div id="listaDenuncias" class="flex flex-col gap-4">
                    <?php foreach($denuncias as $denuncia): ?>
                        <?php include(__DIR__."/elements/card.php") ?>
                    <?php endforeach; ?>
                </div>
                <p id="denunciasSemResultado" class="rd-empty hidden">Nenhuma denúncia deste tipo</p>
            <?php else: ?>
                <p class="rd-empty">Nenhuma denúncia pendente — tudo em ordem por aqui</p>
            <?php endif; ?>
        </section>

    </div>
</div>

<?php include_once(__DIR__."/../elements/adminModais.php") ?>
<script src="<?= JS_URL_BASE ?>/denuncia.js"></script>
<?php
include_once(__DIR__."/../elements/footer.php");
