# RoboDrive — Pacote de Redesign do Front-end

## O que é este pacote

Este zip contém **apenas os arquivos de front-end** (views PHP, CSS e os dois
scripts JS de upload) que foram redesenhados para seguir a identidade visual
já estabelecida na Home, no Login e no Cadastro, além do guia de paleta de
cores do projeto.

**Nada de back-end foi tocado.** Nenhum Model, Controller, Repository,
Service, Helper, arquivo de rota, configuração ou banco de dados está aqui —
seu projeto continua funcionando exatamente como já funciona por trás dessas
telas.

## Como instalar

1. Extraia o zip.
2. Copie as pastas `app/` e `public/` para dentro da raiz do seu projeto
   `RoboDrive-v1.0`, **sobrescrevendo** os arquivos de mesmo caminho.
3. Não é necessário rodar nada, não há build/composer/npm envolvido — é só
   PHP, CSS e JS puro, exatamente como o restante do projeto já funciona.
4. Suba o servidor normalmente (`php -S 127.0.0.1:8080 router.php` ou o que
   você já usa) e acesse.

## O que foi redesenhado

- `app/views/elements/header.php` — cor de assinatura corrigida para o
  ciano oficial (`#13F3F7`), removida uma checagem de arquivo morta, e
  adicionado um chip com foto/nome do usuário logado no canto direito
  (só aparece se houver sessão ativa — Login/Cadastro não são afetados).
- `app/views/elements/sidebar.php` — navegação lateral usada em todas as
  páginas internas.
- Todas as views e partials de **Usuário, Equipe, Fórum, Componente e
  Projeto** (listagens, formulários de criar/editar, páginas de perfil e
  cards).
- `public/assets/css/style.css` — agora contém um pequeno "design system"
  (`.rd-card`, `.rd-input`, `.rd-btn`, `.rd-table`, etc.) usado por todas as
  telas acima, para manter tudo consistente.
- `public/assets/scripts/files.js` e `images.js` — só as classes CSS dos
  elementos criados dinamicamente (lista de arquivos/imagens anexados)
  foram trocadas para bater com o novo visual. A lógica (eventos, upload,
  remoção de itens) é exatamente a mesma.

**Não foram alterados:** `home.php`, `login.php`, `cadastro.php` e os
`elements/form.php` de login/cadastro — ficaram como você já tinha feito.

## Pequenos ajustes de front-end feitos no caminho

Ao restilizar, encontrei alguns problemas que eram só de HTML/atributos
(nenhum PHP de lógica/back-end) e aproveitei para corrigir:

- Link "Voltar" de Equipe e Projeto apontava para `/equipe/listar` e
  `/projeto/listar`, rotas que não existem no `router.php` (só existe
  `/equipe` e `/projeto`) — corrigido para a rota real.
- O botão "Ver" na listagem de Equipes usava `method="post"` num formulário
  cujo controller lê `$_GET['id']` — corrigido para `method="get"`
  (mesmo padrão já usado em Usuário/Componente).
- A tela "Criar postagem" do Fórum enviava o formulário para
  `/projeto/salvar` (e tinha uma aspas sobrando na tag `<form>`) — corrigido
  para `/forum/salvar`.
- O campo de senha da Equipe estava com `type="text"` (senha visível) —
  trocado para `type="password"`.
- Um `<img>` dentro de `projeto/elements/cardComponente.php` usava o caminho
  de arquivo bruto do componente em vez de passar pela rota `/arquivo`,
  igual já é feito em `componente/elements/card.php`.
- Removi a exibição do **hash da senha** nas tabelas de listagem de
  Usuários e Equipes — mostrar isso na tela não tem utilidade e não é uma
  boa prática de segurança, mesmo sendo só a tela de administração.
- Componentes visuais que usavam foto de fundo (`robodrive-fundo.png`)
  foram trocados por fundo sólido + cards com borda, para bater com a
  estética não-fotográfica da Home.

## Um limite honesto deste pacote

Não recriei o projeto inteiro em zip porque nem tudo estava disponível para
mim nesta conversa: os assets de imagem (ícones, logo, `robodrive-fundo.png`,
`perfil.png`, etc.), a pasta `vendor`/PHPMailer e alguns arquivos de view que
não foram compartilhados comigo (ex.: `usuario/create.php`) não foram
reproduzidos. Como o pacote só sobrescreve os arquivos de front-end mantendo
os mesmos nomes de imagem/rota já usados no seu projeto, essas imagens
continuam funcionando normalmente — só não vieram dentro deste zip porque eu
nunca recebi o conteúdo delas.

Se quiser, no próximo passo eu reviso visualmente cada tela junto com você
(ou ajusto algo específico) depois que você testar no seu ambiente.
