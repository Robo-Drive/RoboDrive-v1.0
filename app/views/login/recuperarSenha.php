<?php
$titulo = "Recuperar Senha";
include_once(__DIR__ . "/../elements/header.php");
?>

<main class="relative z-[2] flex min-h-[90dvh] flex-1 items-center justify-center overflow-hidden px-4 py-6 sm:py-10 text-[#F2FEFE]">
    <section class="relative my-auto w-full max-w-md border-[3px] border-[#07556A] bg-[#06141C] p-5 sm:p-8 md:p-10 shadow-[8px_8px_0_#07556A]">
        <div class="absolute left-0 top-0 h-[3px] w-full bg-[#13F3F7]"></div>
        
        <div class="mb-6">
            <p class="mb-2 text-[.65rem] font-bold uppercase tracking-[.25em] text-[#91B5BD]">SEGURANÇA</p>
            <h1 class="font-['Orbitron'] text-2xl sm:text-3xl font-black text-white">
                RECUPERAR <span class="text-[#13F3F7]">SENHA</span>
            </h1>
            <p class="mt-2 text-xs text-[#91B5BD]">Digite o e-mail da sua conta para receber o link de redefinição.</p>
        </div>

        <?php if (!empty($sucesso)): ?>
            <div class="p-3 mb-4 bg-emerald-950/60 border border-emerald-500 text-emerald-300 text-xs">
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($erro_geral)): ?>
            <div class="p-3 mb-4 bg-red-950/60 border border-red-500 text-red-300 text-xs">
                <?= htmlspecialchars($erro_geral) ?>
            </div>
        <?php endif; ?>

        <form action="<?= URL_BASE ?>/recuperar-senha" autocomplete="off" method="post" class="space-y-5">
            <div class="relative">
                <label for="email" class="rd-label">E-mail cadastrado</label>
                <input type="email" name="email" id="email" class="rd-input" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="seuemail@exemplo.com">
                <?php if (isset($erros['email'])): ?>
                    <p class="rd-error"><?= $erros['email'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="rd-btn rd-btn-primary w-full !py-3">
                Enviar Link
            </button>
        </form>

        <p class="mt-6 border-t border-[#07556A] pt-4 text-center text-xs text-[#91B5BD]">
            Lembrou da senha?
            <a href="<?= URL_BASE ?>/login" class="font-bold text-[#13F3F7] hover:underline">Voltar ao Login</a>
        </p>
    </section>
</main>

<?php include_once(__DIR__ . "/../elements/footer.php"); ?>