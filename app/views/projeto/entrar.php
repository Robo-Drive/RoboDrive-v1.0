<?php
$titulo = "Entrar em um Projeto";
include_once(__DIR__ . "/../elements/header.php");
?>

<div class="rd-shell">
    <?php include_once(__DIR__ . "/../elements/sidebar.php"); ?>

    <main class="rd-content-centered !justify-start overflow-y-auto rd-scroll-hidden p-4 sm:p-8 w-full max-w-xl mx-auto space-y-6">

        <div class="mb-4 text-center">
            <p class="rd-eyebrow">PROJETOS</p>
            <h1 class="rd-heading text-2xl sm:text-3xl">ENTRAR EM UM <span>PROJETO</span></h1>
            <p class="text-xs text-[#91B5BD] mt-2">Informe o Hash fornecido pelo coordenador e a senha de acesso.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="p-4 bg-[#06141C] border border-[#00D6B5] text-[#00D6B5] text-xs rounded">
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erros['geral'])): ?>
            <div class="p-4 bg-[#06141C] border border-[#F2A058] text-[#F2A058] text-xs rounded">
                <?= htmlspecialchars($erros['geral']) ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/projeto/entrar" autocomplete="off" method="post" novalidate class="rd-form-card space-y-5">
            <div class="rd-field">
                <label for="hash" class="rd-label">Código Hash do Projeto</label>
                <input 
                    type="text" 
                    name="hash" 
                    id="hash" 
                    value="<?= htmlspecialchars($hash ?? '') ?>" 
                    placeholder="Ex: 4A8E20B9C1" 
                    class="rd-input uppercase font-mono tracking-widest"
                >
            </div>

            <div class="rd-field">
                <label for="senha" class="rd-label">Senha do Projeto</label>
                <input 
                    type="password" 
                    name="senha" 
                    id="senha" 
                    placeholder="Digite a senha..." 
                    class="rd-input"
                >
            </div>

            <button type="submit" class="rd-btn rd-btn-primary w-full">
                Solicitar Associação
            </button>
        </form>

    </main>
</div>

<?php include_once(__DIR__ . "/../elements/footer.php"); ?>