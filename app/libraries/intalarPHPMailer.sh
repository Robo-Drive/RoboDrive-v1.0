
#!/bin/bash

set -e

# Caminho base do projeto
PROJETO_DIR="$(cd "$(dirname "$0")" && pwd)"

echo "📦 Instalando dependências para o PHPMailer em: $PROJETO_DIR"

# Verifica se o PHP está instalado
if ! command -v php &> /dev/null; then
    echo "❌ PHP não encontrado."
    echo "Instale com: sudo pacman -S php"
    exit 1
fi

# Verifica se o Composer está instalado
if ! command -v composer &> /dev/null; then
    echo "🔧 Composer não encontrado."

    echo "📥 Instalando Composer pelo pacman..."
    sudo pacman -S --needed composer

    # Verifica novamente
    if ! command -v composer &> /dev/null; then
        echo "❌ Não foi possível instalar o Composer."
        exit 1
    fi
else
    echo "✅ Composer já está instalado."
fi

# Exibe a versão do Composer
echo "🔍 Verificando Composer..."
composer --version

# Entra no diretório do projeto
cd "$PROJETO_DIR"

# Inicializa o Composer se necessário
if [ ! -f "$PROJETO_DIR/composer.json" ]; then
    echo "📄 Criando composer.json..."

    composer init \
        --name="robodrive/phpmailer" \
        --description="Dependências de email do RoboDrive" \
        --require="phpmailer/phpmailer:^6.9" \
        --no-interaction
fi

# Instala o PHPMailer
echo "📥 Instalando PHPMailer..."

composer require phpmailer/phpmailer

# Verifica se o autoload foi criado
if [ ! -f "$PROJETO_DIR/vendor/autoload.php" ]; then
    echo "❌ Erro: autoload.php não encontrado."
    exit 1
fi

# Confirmação
echo ""
echo "✅ PHPMailer instalado com sucesso!"

echo "📂 Estrutura do projeto:"
if command -v tree &> /dev/null; then
    tree -L 2 "$PROJETO_DIR"
else
    find "$PROJETO_DIR" -maxdepth 2 -type f
fi

echo ""
echo "🚀 Agora você pode usar o autoload com:"
echo "require_once __DIR__ . '/vendor/autoload.php';"
