<?php
$titulo = "Entrar em uma Equipe";
include_once(__DIR__ . "/../elements/header.php");
?>

<div class="rd-shell">
    <?php include_once(__DIR__ . "/../elements/sidebar.php"); ?>

    <main class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden p-4 sm:p-8 w-full max-w-xl mx-auto space-y-6">

        <div class="mb-4 text-center">
            <p class="rd-eyebrow">EQUIPES</p>
            <h1 class="rd-heading text-2xl sm:text-3xl">ENTRAR EM UMA <span>EQUIPE</span></h1>
            <p class="text-xs text-[#91B5BD] mt-2">Informe o Hash único fornecido pelo coordenador e a senha da equipe.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="p-4 bg-emerald-950/60 border border-emerald-500 text-emerald-300 text-xs rounded">
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros['geral'])): ?>
            <div class="p-4 bg-red-950/60 border border-red-500 text-red-300 text-xs rounded">
                <?= htmlspecialchars($erros['geral']) ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/equipe/entrar" autocomplete="off" method="post" novalidate class="rd-form-card space-y-5">
            <div class="rd-field">
                <label for="hash" class="rd-label">Código Hash da Equipe</label>
                <input 
                    type="text" 
                    name="hash" 
                    id="hash" 
                    value="<?= htmlspecialchars($hash ?? '') ?>" 
                    placeholder="Ex: 8BFA93C21A0D" 
                    class="rd-input uppercase font-mono tracking-widest"
                >
                <?php if (isset($erros['hash'])): ?>
                    <p class="rd-error"><?= $erros['hash'] ?></p>
                <?php endif; ?>
            </div>

            <div class="rd-field">
                <label for="senha" class="rd-label">Senha da Equipe</label>
                <input 
                    type="password" 
                    name="senha" 
                    id="senha" 
                    placeholder="Digite a senha..." 
                    class="rd-input"
                >
                <?php if (isset($erros['senha'])): ?>
                    <p class="rd-error"><?= $erros['senha'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="rd-btn rd-btn-primary w-full !py-3">
                Solicitar Associação
            </button>
        </form>

    </main>
</div>

<?php include_once(__DIR__ . "/../elements/footer.php"); ?>