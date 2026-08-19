# RoboDrive — Guia de Identidade Visual e Paleta de Cores

> Documento de referência para todos os desenvolvedores e designers do projeto RoboDrive.
>
> **Regra principal:** a interface deve transmitir tecnologia, robótica, futurismo e energia neon, mantendo o ciano como cor de assinatura e o amarelo como cor de destaque especial.

---

## 1. Direção visual

A identidade visual do RoboDrive combina:

- Tecnologia e robótica
- Estética futurista / cyberpunk
- Interface escura
- Neon ciano
- Transição cromática de azul → ciano → verde-água
- Amarelo vibrante utilizado como segunda linguagem visual
- Alto contraste
- Elementos luminosos usados com moderação

A referência estética geral é uma combinação da identidade da logo do RoboDrive com uma estética cyberpunk semelhante às referências visuais utilizadas no desenvolvimento do projeto.

**Importante:** a identidade do RoboDrive não deve simplesmente copiar a estética de Cyberpunk: Edgerunners. As referências servem apenas para orientar a linguagem visual.

---

# 2. Paleta oficial

## Cores principais

| Nome               | Hex       | Função                                     |
|--------------------|-----------|--------------------------------------------|
| Preto Tecnológico  | `#000505` | Background absoluto                        |
| Azul-Preto         | `#06141C` | Background principal / seções              |
| Azul Profundo      | `#082B3A` | Cards e áreas elevadas                     |
| Azul Cyber         | `#07556A` | Bordas e elementos secundários             |
| Azul-Ciano         | `#0795A5` | Elementos intermediários                   |
| Ciano RoboDrive    | `#13F3F7` | Cor principal da identidade                |
| Neon Cyan          | `#54FBFE` | Hover, highlights e brilho                 |
| Aqua               | `#00D6B5` | Status, indicadores e elementos funcionais |
| Aqua Neon          | `#39F5D0` | Highlights e animações                     |
| Cyber Yellow       | `#F0ED06` | Seções e destaques especiais               |
| Yellow Dark        | `#D8D400` | Variações e hover do amarelo               |
| Amber              | `#F2A058` | Detalhes quentes secundários               |
| Branco Frio        | `#F2FEFE` | Texto principal                            |
| Cinza Azul         | `#91B5BD` | Texto secundário                           |
| Cinza Desabilitado | `#4E6B72` | Texto/elementos desabilitados              |

---

# 3. Cores essenciais

Quando for necessário reduzir a paleta para poucas cores, utilizar prioritariamente:

```text
#000505  → Background
#082B3A  → Azul profundo
#07556A  → Azul cyber
#13F3F7  → Ciano principal
#00D6B5  → Verde-água
#F0ED06  → Amarelo especial
```

Essas seis cores representam o DNA visual principal do RoboDrive.

---

# 4. Hierarquia de cores

A distribuição aproximada da interface deve seguir:

| Categoria | Proporção aproximada |
|---|---:|
| Tons escuros | 60% |
| Azul / ciano escuro | 20% |
| Ciano neon | 12% |
| Verde-água | 5% |
| Amarelo | 3% |

O objetivo é impedir que as cores neon ou o amarelo dominem excessivamente a interface.

**O ciano é a cor de assinatura. O amarelo é uma cor de destaque.**

---

# 5. Gradiente principal

A transição cromática recomendada é:

```text
#082B3A
   ↓
#07556A
   ↓
#0795A5
   ↓
#13F3F7
   ↓
#00D6B5
   ↓
#39F5D0
```

Gradiente recomendado para elementos que realmente necessitem de transição:

```css
background: linear-gradient(
  135deg,
  #082B3A,
  #07556A,
  #13F3F7
);
```

Não utilizar gradientes indiscriminadamente. Eles devem servir para criar destaque, profundidade ou sensação de energia.

---

# 6. Backgrounds

## Background absoluto

```css
--color-bg-black: #000505;
```

Usar em:

- Fundo geral
- Navbar
- Footer
- Áreas extremamente escuras
- Elementos que precisem de máximo contraste

## Background principal

```css
--color-bg-primary: #06141C;
```

Usar em:

- Seções
- Containers
- Áreas principais de conteúdo

## Background elevado

```css
--color-bg-elevated: #082B3A;
```

Usar em:

- Cards
- Painéis
- Menus
- Componentes elevados

---

# 7. Ciano — cor de assinatura

## Ciano RoboDrive

```css
--color-primary: #13F3F7;
```

Esta é a principal cor da identidade.

Priorizar em:

- Logo
- Títulos importantes
- Ícones
- Botões principais
- Bordas de destaque
- Indicadores
- Elementos interativos
- Linhas e detalhes
- Efeitos neon
- Gráficos

## Ciano luminoso

```css
--color-primary-light: #54FBFE;
```

Usar principalmente em:

- Hover
- Highlights
- Brilhos
- Estados ativos
- Pequenos detalhes luminosos

Regra geral:

```text
Normal → #13F3F7
Hover  → #54FBFE
```

---

# 8. Azul

## Azul profundo

```css
--color-blue-dark: #082B3A;
```

Usar em:

- Cards
- Painéis
- Seções
- Backgrounds secundários

## Azul cyber

```css
--color-blue: #07556A;
```

Usar em:

- Bordas
- Divisores
- Elementos secundários
- Gráficos
- Backgrounds

## Azul-ciano

```css
--color-blue-cyan: #0795A5;
```

Usar como intermediário entre azul e ciano.

---

# 9. Verde-água

O verde-água representa a evolução natural do azul/ciano e deve ser utilizado principalmente em elementos funcionais.

## Aqua

```css
--color-aqua: #00D6B5;
```

Usar em:

- Status positivo
- Sensores
- Indicadores
- Elementos de funcionamento
- Dados de desempenho
- Componentes relacionados à robótica

## Aqua neon

```css
--color-aqua-light: #39F5D0;
```

Usar em:

- Highlights
- Hover
- Animações
- Pontos de energia
- Pequenos efeitos

---

# 10. Amarelo

O amarelo possui uma função diferente do ciano.

**Não deve ser tratado como uma cor concorrente da identidade principal.**

Ele deve representar uma seção, estado ou experiência visual especial.

## Cyber Yellow

```css
--color-yellow: #F0ED06;
```

Usar em:

- Seções especiais
- CTAs específicos
- Banners
- Títulos especiais
- Elementos que precisam de forte destaque

## Yellow Dark

```css
--color-yellow-dark: #D8D400;
```

Usar em:

- Hover
- Sombras
- Variações do amarelo
- Detalhes

## Amber

```css
--color-amber: #F2A058;
```

Uso extremamente limitado:

- Ilustrações
- Pequenos detalhes
- Gráficos
- Elementos decorativos

---

# 11. Tipografia e texto

## Texto principal

```css
--color-text-primary: #F2FEFE;
```

Usar em:

- Títulos
- Texto principal
- Informações importantes

Evitar `#FFFFFF` como padrão quando `#F2FEFE` oferecer resultado visual melhor.

## Texto secundário

```css
--color-text-secondary: #91B5BD;
```

Usar em:

- Descrições
- Legendas
- Metadata
- Informações auxiliares

## Texto desabilitado

```css
--color-text-disabled: #4E6B72;
```

Usar em:

- Componentes desabilitados
- Informações indisponíveis
- Estados inativos

---

# 12. Componentes — padrões recomendados

## Navbar

```css
background: #000505;
color: #91B5BD;
```

Estados:

```text
Normal → #91B5BD
Hover  → #13F3F7
Active → #54FBFE
```

---

## Botão primário

```css
background: #13F3F7;
color: #000505;
```

Hover:

```css
background: #54FBFE;
```

O botão primário deve ter alto contraste e ser facilmente identificável.

---

## Botão secundário

```css
background: transparent;
border: 1px solid #13F3F7;
color: #13F3F7;
```

Hover:

```css
background: #13F3F7;
color: #000505;
```

---

## Card

```css
background: #06141C;
border: 1px solid #07556A;
```

Conteúdo:

```text
Título     → #F2FEFE
Destaque   → #13F3F7
Descrição  → #91B5BD
```

---

## Status positivo

```css
color: #00D6B5;
```

---

## Seção amarela

Quando uma seção utilizar a identidade amarela:

```css
background: #F0ED06;
color: #000505;
```

Detalhes:

```css
border-color: #082B3A;
```

---

# 13. Neon e glow

Efeitos neon devem ser usados com moderação.

Exemplo:

```css
box-shadow:
  0 0 8px rgba(19, 243, 247, 0.45),
  0 0 24px rgba(19, 243, 247, 0.20);
```

O glow deve reforçar a estética tecnológica, não prejudicar a legibilidade.

Evitar:

- Glow em todos os elementos
- Sombras excessivamente fortes
- Texto inteiro com neon
- Brilho constante em elementos que não são interativos

---

# 14. Regras de uso

## Fazer

- Priorizar fundos escuros.
- Utilizar `#13F3F7` como assinatura visual.
- Criar profundidade utilizando diferentes tons de azul.
- Utilizar verde-água para informações funcionais.
- Utilizar amarelo para áreas especiais.
- Manter alto contraste.
- Utilizar neon de forma estratégica.
- Manter consistência entre páginas e componentes.

## Evitar

- Utilizar amarelo e ciano na mesma proporção.
- Utilizar todas as cores simultaneamente.
- Fazer todo o site brilhar.
- Usar branco puro como padrão em todos os textos.
- Utilizar gradientes em excesso.
- Transformar cada componente em um elemento cyberpunk neon.
- Utilizar cores fora da paleta sem necessidade.

---

# 15. CSS Variables — referência oficial

A implementação pode utilizar:

```css
:root {
  /* Backgrounds */
  --color-bg-black: #000505;
  --color-bg-primary: #06141C;
  --color-bg-elevated: #082B3A;

  /* Blues */
  --color-blue: #07556A;
  --color-blue-cyan: #0795A5;

  /* RoboDrive Cyan */
  --color-primary: #13F3F7;
  --color-primary-light: #54FBFE;

  /* Aqua */
  --color-aqua: #00D6B5;
  --color-aqua-light: #39F5D0;

  /* Yellow */
  --color-yellow: #F0ED06;
  --color-yellow-dark: #D8D400;
  --color-amber: #F2A058;

  /* Text */
  --color-text-primary: #F2FEFE;
  --color-text-secondary: #91B5BD;
  --color-text-disabled: #4E6B72;
}
```

---

# 16. Resumo para decisões de design

Quando houver dúvida sobre qual cor utilizar:

```text
Precisa de fundo?
→ #000505 / #06141C

Precisa de profundidade?
→ #082B3A / #07556A

Precisa representar o RoboDrive?
→ #13F3F7

Precisa de destaque neon?
→ #54FBFE

Precisa representar funcionamento/status?
→ #00D6B5

Precisa de uma seção visualmente diferente?
→ #F0ED06

Precisa de texto?
→ #F2FEFE

Precisa de texto secundário?
→ #91B5BD
```

---

## Identidade visual em uma frase

**RoboDrive deve parecer uma interface de robótica futurista: escura, tecnológica e precisa, iluminada principalmente por ciano neon, evoluindo para verde-água, com o amarelo reservado para momentos de destaque.**
