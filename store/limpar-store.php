
<?php

use app\database\ConnectionFactory;

// ========================================
// CAMINHOS DO PROJETO
// ========================================

$ROOT = realpath(__DIR__ . '/..');
$STORE = realpath(__DIR__);

if (!$ROOT || !$STORE || basename($STORE) !== 'store') {
    die("Erro: estrutura de diretórios inválida.\n");
}

// ========================================
// CARREGAMENTO
// ========================================

require_once $ROOT . '/app/core/Autoload.php';
require_once $ROOT . '/app/config/config.php';

// ========================================
// CONFIGURAÇÕES
// ========================================

$EXCLUIR = in_array('--delete', $argv ?? [], true);

// ========================================
// CONEXÃO COM O BANCO
// ========================================

try {
    $pdo = ConnectionFactory::getConnection();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Throwable $e) {
    die("Erro na conexão: " . $e->getMessage() . "\n");
}

// ========================================
// ARQUIVOS REGISTRADOS NO BANCO
// ========================================

$tabelas = [
    'usuario'        => 'imagem',
    'componente'     => 'imagem',
    'imagem_projeto' => 'caminho',
    'codigo'         => 'caminho',
    'extra'          => 'caminho'
];

$arquivosBanco = [];

try {

    foreach ($tabelas as $tabela => $coluna) {

        $stmt = $pdo->query("SELECT `$coluna` FROM `$tabela`");

        while ($caminho = $stmt->fetchColumn()) {

            if (empty($caminho)) {
                continue;
            }

            $caminho = str_replace('\\', '/', trim($caminho));
            $caminho = ltrim($caminho, '/');

            if (!str_starts_with($caminho, 'store/')) {
                continue;
            }

            if (in_array('..', explode('/', $caminho), true)) {
                continue;
            }

            $arquivosBanco[$caminho] = true;
        }
    }

} catch (Throwable $e) {

    die("Erro ao consultar banco: " . $e->getMessage() . "\n");
}

// ========================================
// VERIFICAR ARQUIVOS
// ========================================

$mantidos = 0;
$removidos = 0;
$espaco = 0;

echo "\n========================================\n";
echo "          LIMPEZA DO STORE\n";
echo "========================================\n";

echo "Modo: " . ($EXCLUIR ? "EXCLUSÃO" : "SIMULAÇÃO") . "\n\n";

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $STORE,
        FilesystemIterator::SKIP_DOTS
    ),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $arquivo) {

    if ($arquivo->isLink() || !$arquivo->isFile()) {
        continue;
    }

    $absoluto = $arquivo->getPathname();

    // Protege o próprio script.
    if ($absoluto === __FILE__) {
        continue;
    }

    $relativo = 'store/' . str_replace(
        '\\',
        '/',
        substr($absoluto, strlen($STORE) + 1)
    );

    if (isset($arquivosBanco[$relativo])) {

        $mantidos++;

        echo "[MANTER] $relativo\n";

        continue;
    }

    $tamanho = $arquivo->getSize();

    if ($EXCLUIR) {

        if (!unlink($absoluto)) {
            echo "[ERRO] $relativo\n";
            continue;
        }

        echo "[EXCLUÍDO] $relativo\n";

    } else {

        echo "[REMOVERIA] $relativo\n";
    }

    $removidos++;
    $espaco += $tamanho;
}

// ========================================
// REMOVER DIRETÓRIOS VAZIOS
// ========================================

if ($EXCLUIR) {

    $diretorios = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $STORE,
            FilesystemIterator::SKIP_DOTS
        ),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {

        if ($item->isDir() && !$item->isLink()) {
            $diretorios[] = $item->getPathname();
        }
    }

    usort(
        $diretorios,
        fn($a, $b) => strlen($b) - strlen($a)
    );

    foreach ($diretorios as $diretorio) {

        if (count(scandir($diretorio)) === 2) {
            rmdir($diretorio);
        }
    }
}

// ========================================
// RESULTADO
// ========================================

echo "\n========================================\n";
echo "              RESULTADO\n";
echo "========================================\n";

echo "Referências no banco: " . count($arquivosBanco) . "\n";
echo "Arquivos mantidos: $mantidos\n";
echo "Arquivos " . ($EXCLUIR ? "excluídos" : "a excluir") . ": $removidos\n";

echo "Espaço " . ($EXCLUIR ? "liberado" : "a liberar") . ": ";
echo number_format($espaco / 1024 / 1024, 2) . " MB\n";

echo "\nFinalizado!\n";
