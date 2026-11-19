<?php
$titulo = "Página Não Encontrada - RoboDrive";
include_once(__DIR__ . "/header.php");
?>
<div class="rd-shell">
    <?php include_once(__DIR__ . "/sidebar.php") ?>
    
    <main class="rd-content-centered !justify-center p-6 text-center">
        <div class="max-w-lg space-y-6">
            <span class="font-['Orbitron'] text-8xl font-black tracking-widest text-[#13F3F7] drop-shadow-[0_0_20px_rgba(19,243,247,0.4)]">
                404
            </span>
            <div class="space-y-2">
                <h1 class="font-['Orbitron'] text-2xl font-black text-[#F2FEFE] uppercase tracking-wider">
                    Sinal Não Encontrado
                </h1>
                <p class="text-sm text-[#91B5BD] leading-relaxed">
                    O módulo, circuito ou rota que você tentou acessar não existe, foi desativado ou está inacessível no momento.
                </p>
            </div>
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <button 
                    type="button" 
                    onclick="window.history.length > 1 ? window.history.back() : window.location.href='<?= URL_BASE ?>'" 
                    class="rd-btn rd-btn-secondary w-full sm:w-auto"
                >
                    ‹ Voltar
                </button>
                <a href="<?= URL_BASE ?>" class="rd-btn rd-btn-primary w-full sm:w-auto">
                    Página Inicial
                </a>
            </div>
        </div>
    </main>
</div>
<?php
include_once(__DIR__ . "/footer.php");
