> ⚠️ OBSOLETO (2026-08-29). Esta abordagem (widgets nativos) foi tentada e a Madu achou que
> afastou do design v2. Direção final: **port FIEL = 1 bloco HTML por seção**, feito pelo
> `build-home.FROZEN-2026-08-29.php` e depois **congelado**. Ver README do repo.

---

# Plano — Tornar a Home 100% editável no Elementor (e congelar o script)

**Decisão da Madu (2026-08-29):** o Elementor passa a ser a fonte da verdade. Rodar o
`build-home.php` uma última vez gerando a versão "máximo nativo", depois **congelar o script**.
Aplicar em **todas as 8 seções**. **Onda e selo ficam visualmente como estão hoje.** Continuamos
em **Elementor Free**.

## Limite do Free (o que NÃO muda)

O Free não tem CSS por elemento nem posição fixa/sticky nativa. Então:
- O `mu-plugin ff-assets` (CSS + JS) **continua existindo** — formas orgânicas (blobs), selo, ondas,
  glow, nav fixa, layout multi-coluna, reveal-on-scroll: tudo isso segue vindo do CSS/JS, amarrado
  por `id`/classe.
- O que a Madu passa a editar pela UI: **todo texto, todas as imagens, cores dos textos, tipografia,
  espaçamento, fundos de seção, rótulos e links de botão**.
- O que continua só no código: geometria das formas, animações, o desenho das ondas e do selo.

## Conversões por tipo (todas as seções)

| Hoje (HTML cru) | Vira | Editável pela UI |
|---|---|---|
| Textos "script" (`.ff-script`, `.ff-script-right`) | Widget **Título** + classe | texto, cor, fonte |
| Olho-de-sobrancelha (`eyebrow()` com filetes) | **Container** (Divisor + Título + Divisor) | texto do rótulo |
| Filete dourado do hero (`.ff-gold-rule`) | Widget **Divisor** | largura, cor, espessura |
| Links sublinhados (`.ff-underline-link` ×3) | Widget **Botão** (estilo link) + ícone seta | texto, link |
| Depoimentos: inicial / nome / cargo / kicker | Widgets **Título** + classe | texto |
| Método: orbes "01"–"05" (`.ff-step-orb`) | Widget **Título** + classe (círculo fica no CSS) | o número |
| Tags "sobre" (`.ff-chips span` ×4) | Container linha + 4 Títulos (classe `.ff-chip` nova) | texto de cada tag |
| Grade de contatos (conversar) | Widget **Lista de Ícones** | texto, link, ícone de cada item |
| Rodapé: marca (logo+nome+tagline) | Container: Imagem + 2 Títulos | texto, logo |
| Rodapé: 3 links | Widget **Lista de Ícones** (sem ícone) | texto, link |
| Nav: logo / 4 âncoras / CTA | Container `#ffnav` (fica fixo via CSS) → Imagem + Lista de Ícones + Botão | logo, rótulos, link do CTA |
| Foto de fundo do hero (`<picture>`) | **Fundo do container** `#hero` (imagem responsiva) | trocar a foto |
| Fotos "caminhos"/"sobre"/"conversar" (blobs) | **já são** widget Imagem (classe faz a máscara) | já editável — trocar imagem |
| Bolinhas decorativas (`.ff-dot`) | continuam HTML (decoração, sem texto) | — (cor no CSS) |

## Fica como está hoje (decisão da Madu)

- **Onda do hero** e **onda do "conversar"** — SVG no widget HTML, intactas.
- **Selo girando** — widget HTML único; o texto (ACOLHER/COMPREENDER/TRANSFORMAR e o anel) se
  edita pelo conteúdo do widget HTML; visual 100% preservado.
- **Glow do hero** — decoração, sem conteúdo.

## Refatoração de CSS

Ao virar widget nativo, o HTML de saída muda (`.elementor-widget-heading .elementor-heading-title`
etc.). O `ff-assets.css` será reescrito para mirar as novas estruturas pelas classes/ids que
anexamos. Arquivo é pequeno (~200 linhas); mudança é ampla mas contida. O `ff-assets.js`
(reveal/parallax/nav) é revisado para manter os seletores válidos.

## Execução — fase por fase (deploy + conferência a cada seção)

Ordem: 2 Hero → 3 Caminhos → 4 Depoimentos → 5 Serviço+Método → 6 Sobre → 7 Conversar → 8 Rodapé → 1 Nav.
Para cada seção:
1. Reescrever o bloco no `build-home.php` (widgets nativos) + ajustar `ff-assets.css`.
2. `scp` → `wp eval-file` → `wp elementor flush-css` → `wp litespeed-purge all`.
3. Conferir no navegador (~390 / ~768 / ~1440 px) contra a referência estática
   `darkviolet-whale-772897.hostingersite.com` — tem que ficar visualmente idêntico.
4. Registrar no relatório e seguir.

## Fase final — congelar

- `build-home.php` → renomeado para `build-home.SNAPSHOT-2026-08-29.php` (histórico), com aviso no
  topo: "NÃO rodar — Elementor é a fonte da verdade desde 2026-08-29".
- `README.md` do repo: seção "editar o site" (é no Elementor; o mu-plugin `ff-assets` guarda o
  CSS/JS estrutural; a página não é mais gerada por script).
- Backup do `_elementor_data` final em `~/ff-backups/` no servidor + cópia no repo
  (`_wp-build/elementor-data-30.SNAPSHOT.json`).
- Atualizar memória `project_franciele_falconi_site.md`.

## Riscos / pré-requisitos

- **Conferência visual precisa do navegador** — a extensão do Chrome não está conectada nesta
  sessão. Sem ela, confiro por HTML renderizado + curl (menos confiável para "ficou idêntico").
- `_css_classes` em Título/Lista-de-Ícones pode não "colar" no Free (funciona em imagem/botão/HTML).
  Plano B: `_element_id` único por widget ou wrapper com id. Testo isso já na 1ª seção.
- Enquanto a migração roda, cada seção fica alguns minutos "meio migrada" no ar — o site está
  `blog_public=0` (não indexável), então sem impacto de SEO.
- `blog_public` continua `0` ao fim (não religar indexação).
