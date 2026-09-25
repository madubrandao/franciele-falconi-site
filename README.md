# Franciele Falconi — site

Página única de vendas/portfólio da Franciele Falconi (Terapeuta Psicanalista Sistêmica, Método Falconi).

Existem **duas versões**:

1. **WordPress + Elementor — v4 "liquid glass"** → produção-alvo desde 2026-09-24 (seção abaixo; código em `_wp-build/`).
2. **Site estático (v2)** → este repo na raiz (`index.html`); hoje é só fallback/referência.

---

## Versão WordPress + Elementor — v4 "liquid glass"

Design novo do Claude Design (tema escuro verde-esmeralda + dourado, fundo animado "jardim que cresce",
cards-livro, carrossel do Método, acordeões), implementado na Home do **WordPress + Elementor Free**.

- **URL:** https://gold-spider-281232.hostingersite.com/ — `blog_public=0` **de propósito**: a Hostinger bloqueia o
  Googlebot nesse endereço provisório; só indexar quando houver domínio final.
- **Página:** ID 30 (`inicio`), template `elementor_canvas` (tela total), front page. 12 seções, cada uma = 1 widget
  HTML fiel ao design; exceção: as 4 imagens de foto (3 depoimentos + Sobre mim) são widgets **Imagem** nativos.
- **Handoff de origem:** `design/Site Franciele Falconi Mobile.dc.html` (já é o site responsivo; o "Preview Mobile e
  Tablet" é só moldura de aparelho). Todos os CTAs → WhatsApp `https://wa.me/5548991118771`.
- **Responsivo:** menu ☰ < 1000px; botões centralizados < 700px; no toque (`hover:none`) a capa dos cards "Um
  trabalho, dois caminhos" abre pelo **scroll**; `prefers-reduced-motion` respeitado.

### Onde está o código (`_wp-build/`)

```
_wp-build/
  v4/ff-site.php        mu-plugin loader: fontes, CSS/JS só na front page, body class ff4, title/description SEO
  v4/ff4-assets.css     tokens, liquid glass, botão, card, livro, marquee, acordeões, menu, media queries
  v4/ff4-garden.js      fundo animado (3 canvas + hero, crescimento ligado ao scroll) — port vanilla do design
  v4/ff4-ui.js          menu, botão (pulso), livro (scroll no toque), marquee, acordeões/FAQ
  v4/set-photos.php     troca as 4 imagens do Elementor (wp eval-file) sem recriar a página
  v4/img/               imagens otimizadas (logo, banner, capas, jardim, fotos). Os 3 prints de depoimentos
                        (ff4-depoimento-1/2/3.jpg) NÃO são versionados (dados de clientes, repo público): estão na
                        Biblioteca de Mídia do WordPress e no computador da Madu.
  build-home-v4.FROZEN-2026-09-24.php   gerou a Home v4 — CONGELADO (exit(1))
  elementor-data-30.v4.SNAPSHOT.json    restore point da Home v4 (com as fotos)
  build-home*.php, elementor-data-30.SNAPSHOT.json, ff-assets.*, ff-site.php   histórico v2/v3 (fallback)
  HERO-SUPASTE-BRIEF.md, PLANO-elementor-nativo.md, _hero-supaste-ref/         notas e referências antigas
```

> **Segurança do deploy:** este repo é **público** e o push em `main` publica tudo no `public_html` do site
> estático. `_wp-build/.htaccess` nega acesso web à pasta; mesmo assim **não coloque segredos aqui**.

### Editar a versão WordPress

- **O build está CONGELADO:** o **Elementor é a fonte da verdade**; rodar o `build-home` de novo apagaria edições
  feitas no painel. Para descongelar, remova o guard no topo do arquivo `.FROZEN`.
- **Texto:** Elementor → widget HTML da seção → editar. **Foto:** clicar na imagem → Biblioteca de Mídia.
- **CSS/JS:** editar `_wp-build/v4/ff4-*`, `scp` para `/tmp/ff4/`, copiar para
  `wp-content/mu-plugins/ff-assets/`, depois `wp elementor flush-css` e `wp litespeed-purge all`.
- **SSH:** `ssh -i ~/.ssh/hostinger_franciele -p 65002 u431895820@147.93.38.91` →
  `cd ~/domains/gold-spider-281232.hostingersite.com/public_html` (backups em `~/ff-backups/`).
- **Rollback:** reimportar `elementor-data-30.v4.SNAPSHOT.json` (ou `~/ff-backups/pre-v4-*` para a v3).

### Pendências

- **Logo:** falta o PNG transparente original (o atual foi recortado por chave de cor de um JPEG).
- **Conteúdo a validar com a Fran:** certificações/tempo de atuação, texto expandido dos cards de empresa,
  aviso ético do rodapé, serviço em destaque para pessoa física.
- **Domínio final:** conectar, trocar `home`/`siteurl`, canonical, sitemap e só então `blog_public=1`.

---

## Site estático (v2) — fallback

**Site estático** (HTML + CSS + JS vanilla), sem build, sem framework.

- **Produção:** https://darkviolet-whale-772897.hostingersite.com/ (Hostinger)
- **Design (fonte de verdade):** Claude Design — projeto `802abfec-5342-49b7-9329-877a4b2f6d27`,
  arquivo `Franciele Falconi v2.dc.html`. Este repo é um **port manual** desse arquivo;
  não há sincronização automática.

### Estrutura

```
index.html            página inteira (CSS e JS inline, portados 1:1 do .dc.html)
assets/               imagens finais servidas na web
assets/_raw/          originais das imagens otimizadas (NÃO versionado)
scripts/optimize.mjs  gera .jpg otimizado + favicons + og-image a partir de assets/_raw/
robots.txt sitemap.xml
```

### Editar

1. Ajuste o `index.html` (ou reporte a mudança no Claude Design e refaça o port).
2. Se trocar imagens pesadas: ponha o original em `assets/_raw/` e rode
   `npm i && node scripts/optimize.mjs`.
3. `git commit` + `git push origin main`.

### Deploy (Hostinger hPanel · Git)

O push na branch `main` dispara o redeploy automático (webhook configurado no hPanel).

Configuração inicial (uma vez): hPanel → Sites → (site) → **Avançado › GIT** →
criar repositório apontando para a URL deste repo, branch `main`, diretório `public_html`;
depois copiar a *Webhook URL* e adicionar em GitHub → Settings → Webhooks.

### Pendências

- **WhatsApp** usado nos CTAs: `5548991118771` — confirmar.
- Selo do hero diz "ACOLHER · COMPREENDER · TRANSFORMAR" (3 termos) enquanto a seção Método
  tem 5 movimentos — possível inconsistência a resolver no Claude Design.
- Domínio próprio (ex. `francielefalconi.com.br`) no lugar do subdomínio — quando definido,
  atualizar `canonical`, tags OG/Twitter, `robots.txt` e `sitemap.xml`.
