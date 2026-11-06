<?php
$titulo = "Nova Senha";
include_once(__DIR__ . "/../elements/header.php");
?>

<main class="relative z-[2] flex min-h-[90dvh] flex-1 items-center justify-center overflow-hidden px-4 py-6 sm:py-10 text-[#F2FEFE]">
    <section class="relative my-auto w-full max-w-md border-[3px] border-[#07556A] bg-[#06141C] p-5 sm:p-8 md:p-10 shadow-[8px_8px_0_#07556A]">
        <div class="absolute left-0 top-0 h-[3px] w-full bg-[#13F3F7]"></div>
        
        <div class="mb-6">
            <p class="mb-2 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">NOVA SENHA</p>
            <h1 class="font-['Orbitron'] text-2xl sm:text-3xl font-black text-white">
                CADASTRAR <span class="text-[#13F3F7]">SENHA</span>
            </h1>
            <p class="mt-2 text-xs text-[#91B5BD]">Informe sua nova senha de acesso.</p>
        </div>

        <form action="<?= URL_BASE ?>/redefinir-senha" autocomplete="off" method="post" class="space-y-5">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

            <div class="relative">
                <label for="senha" class="rd-label">Nova Senha</label>
                <input type="password" name="senha" id="senha" minlength="8" class="rd-input" placeholder="Mínimo de 8 caracteres">
                <?php if (isset($erros['senha'])): ?>
                    <p class="rd-error"><?= $erros['senha'] ?></p>
                <?php endif; ?>
            </div>

            <div class="relative">
                <label for="confirmar_senha" class="rd-label">Confirmar Nova Senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="8" class="rd-input" placeholder="Repita a nova senha">
                <?php if (isset($erros['confirmar_senha'])): ?>
                    <p class="rd-error"><?= $erros['confirmar_senha'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="rd-btn rd-btn-primary w-full !py-3">
                Salvar Nova Senha
            </button>
        </form>
    </section>
</main>

<?php include_once(__DIR__ . "/../elements/footer.php"); ?>