# Brief — Hero no modelo supaste.com (adaptado à marca)

> **Origem:** este trabalho foi pedido pela Madu e prototipado por engano no repo do **CRM**
> (`projeto_fran`, Next.js) na sessão de 2026-09-01. O código React não porta para cá, mas as
> **decisões de design, cores e medidas** valem — estão consolidadas abaixo e nos arquivos de
> `_hero-supaste-ref/`. Aplicar aqui no build nativo (`build-home.php` + `ff-assets.css` + `ff-assets.js`).
>
> O protótipo React está rodável em `projeto_fran` (branch `feat/hero-supaste-style`), caso queira ver o
> comportamento ao vivo: `npm run dev` lá.

---

## 1. Objetivo

Reproduzir **fielmente o formato visual** da supaste.com em 3 blocos:

1. **Cabeçalho** — nav em "pill" flutuante.
2. **Hero (1ª seção)** — bloco centralizado: eyebrow → H1 em 2 linhas → parágrafo → botão → selos.
3. **2ª seção (sobe na rolagem)** — moldura "glass" com uma **janela de app** (sidebar, busca, abas,
   fileira de cards) que sobe e escala conforme o scroll, exatamente como na imagem do Awwwards.

**Escopo do texto de referência** (delimitado pela Madu — copiar só até aqui):

```
Supaste  |  Features  FAQ  Updates  Cooldock  Screen Movie  Pricing  |  Download
Clipboard history
Copy once.
Reuse anytime.
Supaste saves your clipboard and screenshots in a beautiful visual history, automatically
grouped by type, app, and custom categories, so you can search, find, and paste anything
back in seconds.
```

## 2. Regra da Madu (decisões já confirmadas)

| Pergunta | Resposta da Madu |
|---|---|
| Onde implementar | **Substituir o hero atual** (não é página nova) |
| Verde no lugar do preto | **`#3c4a34`** (= `--ff-green`, já existe no `ff-assets.css`) |
| Fundo (céu azul + foto de colinas da Supaste) | **Adaptar aos tons da marca** — gradiente verde→bege, sem foto |
| Até onde vai a 2ª seção | **Só o mockup da janela do app** (para aí, como na imagem de referência) |
| Textos / ícones / logo | **Placeholder** — a Madu edita depois manualmente |

Referências visuais: `_hero-supaste-ref/supaste-referencia-awwwards.png` (alvo) e
`_hero-supaste-ref/crm-hero-final.jpg` (protótipo já adaptado à marca).

---

## 3. Spec por bloco

### 3.1 Nav "pill" (`#ffnav`)

- Wrapper fixo, **transparente**, `display:flex; justify-content:center`, `padding: clamp(14px,2vw,24px) clamp(16px,3vw,32px)`, `pointer-events:none`.
- **Pill interno** (é o que aparece): `pointer-events:auto`, `max-width:920px`, largura 100%,
  `padding:10px 10px 10px clamp(16px,2vw,22px)`, `border-radius:999px`,
  `border:1px solid rgba(246,241,228,.2)`, `background:var(--ff-green)`,
  `box-shadow:0 16px 40px rgba(12,16,8,.4)`.
- Layout do pill: `[marca] [links (margin-right:auto)] [CTA]`, `gap: clamp(14px,2.4vw,34px)`, `align-items:center`.
- **Marca:** `logo-icon` (28–34px, circular) + wordmark em `Playfair Display`, `clamp(13px,1.1vw,15px)`, cor `--ff-light`.
- **Links:** `Cinzel`, `clamp(10.5px,.86vw,12px)`, `letter-spacing:.14em`, `text-transform:uppercase`,
  cor `rgba(246,241,228,.72)`, hover `--ff-gold-light`.
- **CTA:** pill claro — `background:var(--ff-light)`, texto `--ff-green`, `min-height:40px`,
  `padding:0 clamp(18px,2vw,24px)`, `font-weight:600`, `letter-spacing:.12em`, uppercase.
- **Scroll (`ff-assets.js`, já existe `onScroll`):** ao passar de 60px, trocar o **fundo do pill** para
  `var(--ff-green-dark)` e sombra mais forte. (Hoje o JS mexe no `#ffnav` inteiro — mudar para mexer no pill.)
- **Mobile (`≤760px`):** esconder os links (`display:none`), pill com `justify-content:space-between`.

### 3.2 Hero (`#inicio` + `#hero-inner`)

**Estrutura (a chave é o scroll pinado):**

```
#inicio  { position:relative; min-height:165vh; background:<gradiente da marca> }
  .ff-hero-sticky { position:sticky; top:0; height:100svh; display:flex;
                    align-items:center; justify-content:center; overflow:hidden;
                    padding: clamp(96px,14vh,140px) clamp(20px,4vw,40px) }
    .ff-hero-backdrop  (glow radial suave no topo, z0)
    .ff-hero-hills     (SVG de colinas nos tons da marca, z1, no rodapé)
    #hero-inner        (conteúdo do hero, z3, centralizado, fade no scroll)
    .ff-hero-mockup    (a janela do app, z2, absolute, sobe no scroll)
```

**Gradiente do fundo (no lugar do céu azul da Supaste):**
`linear-gradient(180deg, #1b2217 0%, var(--ff-green) 30%, #6f7d5a 58%, var(--ff-bg) 100%)`

**Colinas (`.ff-hero-hills`)** — SVG `viewBox="0 0 1440 320"`, `preserveAspectRatio="none"`,
`position:absolute; bottom:0; width:100%; height:clamp(160px,28vh,300px)`, dois paths:
```
<path d="M0,214 C240,132 430,150 660,204 C900,260 1130,250 1440,176 L1440,320 L0,320 Z" fill="var(--ff-green)" opacity=".85"/>
<path d="M0,268 C280,206 520,232 780,262 C1040,292 1240,284 1440,244 L1440,320 L0,320 Z" fill="var(--ff-green-dark)"/>
```
(substitui a `.ff-hero-wave` atual)

**Conteúdo (`#hero-inner`) — centralizado, `max-width:820px`, `text-align:center`:**
- **Eyebrow:** ícone folha + texto em `Cinzel`, `clamp(10.5px,1vw,13px)`, `letter-spacing:.28em`, uppercase, cor `--ff-light`.
- **H1 em 2 linhas** (`.ff-hero-h1`), `line-height:1`, `letter-spacing:-.02em`:
  - Linha 1: `Montserrat` 600, cor `--ff-light`, `font-size: clamp(42px, min(8.6vw,12vh), 120px)`.
  - Linha 2: `Playfair Display` **itálico** 400, cor `--ff-gold-light`, mesmo `font-size`.
- **Parágrafo:** `max-width:40em`, `margin-top: clamp(20px,3vh,30px)`, cor `rgba(246,241,228,.82)`,
  `font-size: clamp(14px,1.3vw,18px)`, `line-height:1.6`.
- **Botão primário** (`.ff-btn-primary` variante hero): pill **escuro** `#12180e`,
  `border:1px solid rgba(246,241,228,.14)`, texto `--ff-light`, `min-height:54px`,
  `padding:0 clamp(26px,3vw,36px)`, `box-shadow:0 18px 40px rgba(10,14,8,.45)`, uppercase,
  `letter-spacing:.1em`. Ícone à esquerda. **(Contraste: NÃO usar `--ff-green` aqui — some no fundo verde.)**
- **Selos:** 3 textos curtos, `Cinzel`? não — sans, `clamp(10.5px,.9vw,12px)`, cor `rgba(246,241,228,.6)`,
  `flex; gap: clamp(14px,2vw,26px); justify-content:center`.

**Fade no scroll (via var `--hero-progress`, ver §3.4):**
```
#hero-inner      { opacity: clamp(0, calc(1 - var(--hero-progress,0) * 2), 1);
                   transform: translateY(calc(var(--hero-progress,0) * -12vh)) }
```

### 3.3 Mockup da janela do app (`.ff-hero-mockup`) — bloco novo

```
.ff-hero-mockup { position:absolute; left:50%; top:50%; z-index:2; width:min(1100px,94vw);
  transform: translate(-50%, calc(-50% + (1 - var(--hero-progress,0)) * 72vh))
             scale(calc(.9 + var(--hero-progress,0) * .1));
  opacity: clamp(0, calc(.12 + var(--hero-progress,0) * 1.4), 1); }
```
→ no scroll 0 fica ~72vh abaixo do centro (invisível); no fim do hero fica centralizado, opaco, escala 1.

**Moldura "glass" (`.ff-mockup-frame`):** `padding: clamp(10px,1.4vw,18px)`,
`border:1px solid rgba(246,241,228,.45)`, `border-radius: clamp(20px,2.4vw,34px)`,
`background: rgba(246,241,228,.22)`, `backdrop-filter: blur(10px)`,
`box-shadow: 0 40px 90px rgba(20,26,15,.45), inset 0 1px 0 rgba(246,241,228,.4)`.

**Janela (`.ff-mockup-window`):** `display:grid; grid-template-columns: clamp(112px,14vw,168px) 1fr`,
`height: clamp(270px,42vh,400px)`, `border-radius: clamp(14px,1.8vw,22px)`,
`background:#171d13`, `overflow:hidden`.

Conteúdo (tudo placeholder):
- **Sidebar:** `padding: clamp(14px,1.6vw,20px)`, borda direita `1px solid rgba(246,241,228,.08)`;
  marca = quadradinho gradiente dourado 16px + "Franciele" em Playfair.
- **Topbar:** `display:flex; gap:12px` — campo de busca (flex:1, `border-radius:999px`,
  `background: rgba(246,241,228,.08)`, ícone lupa + "Buscar…") + 3 botões redondos 28px.
- **Abas:** `display:flex; gap:6px` — pills `padding:6px 12px; border-radius:999px`,
  1ª ativa (`background:var(--ff-light)`, `color:var(--ff-green)`), demais
  `background: rgba(246,241,228,.06)`, `color: rgba(246,241,228,.55)`, + uma pill "+".
- **Cards:** `display:grid; grid-template-columns: repeat(5,1fr); gap: clamp(8px,1vw,14px)`.
  5 variantes de fundo (imagem-gradiente, nota, captura clara, swatch `#3C4A34`, ícone claro),
  cada card com rodapé mini (quadradinho favicon + "há X min").
  Mobile `≤760px`: `repeat(3,1fr)` e esconder os cards 4–5.

### 3.4 Scroll driver (`ff-assets.js`)

Dentro do `onScroll()` já existente, adicionar:
```js
var hero = root.getElementById("inicio");
if (hero && !reduce) {
  var range = hero.offsetHeight - window.innerHeight;
  var start = window.scrollY + hero.getBoundingClientRect().top;
  var p = range > 0 ? Math.min(1, Math.max(0, (window.scrollY - start) / range)) : 0;
  hero.style.setProperty("--hero-progress", p.toFixed(4));
}
```
`prefers-reduced-motion` e `≤900px`: hero vira `min-height:auto`, `.ff-hero-sticky` `position:static`
+ `flex-direction:column`, mockup `position:relative` e empilhado abaixo do conteúdo; `#hero-inner` e
`.ff-hero-mockup` com `opacity:1; transform:none`.

---

## 4. Passos sugeridos

1. `build-home.php` — reescrever a seção **1. NAV** (pill) e **2. HERO** (`$heroDeco` → backdrop + colinas;
   `$heroInner` → layout centralizado 2 linhas; **novo** bloco `$heroMockup` via `htmlw()`).
   Remover `$heroSeal` e `.ff-hero-wave` do hero. Manter `#ffprogress`.
2. `ff-assets.css` — bloco `.ff-nav*` (pill), bloco `/* HERO */` (sticky 165vh + gradiente + colinas +
   `#hero-inner` centralizado + `--hero-progress`), bloco novo `.ff-hero-mockup / .ff-mockup-*`,
   media queries `≤900` / `≤760` / `reduced-motion`.
3. `ff-assets.js` — adicionar o cálculo de `--hero-progress` no `onScroll()`; trocar o alvo do efeito de
   nav-scroll de `#ffnav` para o pill interno.
4. Rebuild da página 30 (`build-home.php` via WP-CLI) → revisar no navegador vs
   `supaste-referencia-awwwards.png` em ~1440px e mobile → refino → **visto da Madu** → recongelar
   (`build-home.FROZEN-<data>.php` + atualizar `SNAPSHOT.json`).

## 5. Cuidado

- Elementor Free não tem scroll-effects nativo — o efeito de subida do mockup depende **100%** do
  `--hero-progress` setado pelo `ff-assets.js` + `position:sticky` no CSS. Testar em Safari/iOS.
- `--ff-green`, `--ff-green-dark`, `--ff-gold-light`, `--ff-light`, `--ff-bg` já existem no `ff-assets.css`.
- Textos/ícones = placeholder de propósito; **não inventar copy** — a Madu preenche.
- Não commitar no repo `franciele-falconi-site` sem alinhar (ele é **público**).
