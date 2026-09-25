# Franciele Falconi — site (v2)

Página única de vendas/portfólio da Franciele Falconi (Terapeuta Psicanalista Sistêmica,
Método Falconi). **Site estático** (HTML + CSS + JS vanilla), sem build, sem framework.

- **Produção:** https://darkviolet-whale-772897.hostingersite.com/ (Hostinger)
- **Design (fonte de verdade):** Claude Design — projeto `802abfec-5342-49b7-9329-877a4b2f6d27`,
  arquivo `Franciele Falconi v2.dc.html`. Este repo é um **port manual** desse arquivo;
  não há sincronização automática.

## Estrutura

```
index.html            página inteira (CSS e JS inline, portados 1:1 do .dc.html)
assets/               imagens finais servidas na web
assets/_raw/          originais das imagens otimizadas (NÃO versionado)
scripts/optimize.mjs  gera .jpg otimizado + favicons + og-image a partir de assets/_raw/
robots.txt sitemap.xml
```

## Editar

1. Ajuste o `index.html` (ou reporte a mudança no Claude Design e refaça o port).
2. Se trocar imagens pesadas: ponha o original em `assets/_raw/` e rode
   `npm i && node scripts/optimize.mjs`.
3. `git commit` + `git push origin main`.

## Deploy (Hostinger hPanel · Git)

O push na branch `main` dispara o redeploy automático (webhook configurado no hPanel).

Configuração inicial (uma vez): hPanel → Sites → (site) → **Avançado › GIT** →
criar repositório apontando para a URL deste repo, branch `main`, diretório `public_html`;
depois copiar a *Webhook URL* e adicionar em GitHub → Settings → Webhooks.

## Versão WordPress + Elementor (produção-alvo desde 2026-08-29)

Além do site estático acima, existe uma versão em **WordPress + Elementor Free** que passou a ser
o alvo de produção:

- **URL:** https://gold-spider-281232.hostingersite.com/ (`blog_public=0` — ainda não indexável)
- **Como foi montada:** `_wp-build/build-home.FROZEN-2026-08-29.php` gerou a Home (página ID 30) como
  **8 blocos** — 1 container Elementor + 1 widget HTML por seção, com o markup **exato deste `index.html`**.
  CSS/JS do design vivem no mu-plugin `wp-content/mu-plugins/ff-assets/` (`ff-assets.css` = o `<style>`
  do design; `ff-assets.js` = o `<script>`).
- **O `build-home` está CONGELADO.** Desde 2026-08-29 o **Elementor é a fonte da verdade** — rodá-lo
  de novo apagaria as edições feitas no painel. Snapshot em `_wp-build/elementor-data-30.SNAPSHOT.json`.

### Editar a versão WordPress

- **Texto:** Elementor → abrir o widget HTML da seção → editar o HTML.
- **Imagem:** trocar pela Biblioteca de Mídia do WordPress.
- **Reordenar / esconder seção:** mover ou ocultar o container da seção no Elementor.
- **CSS/JS estrutural:** editar `_wp-build/ff-assets.css` / `ff-assets.js`, mandar pro servidor
  (`scp` → `wp-content/mu-plugins/ff-assets/`) e `wp litespeed-purge all`.
- **SSH:** `ssh -i ~/.ssh/hostinger_franciele -p 65002 u431895820@147.93.38.91` →
  `cd ~/domains/gold-spider-281232.hostingersite.com/public_html`.

## Pendências

- **Depoimentos** na seção `#depoimentos` são placeholder — trocar pelos reais da Fran.
- **WhatsApp** usado nos CTAs: `5548991118771` — confirmar.
- Selo do hero diz "ACOLHER · COMPREENDER · TRANSFORMAR" (3 termos) enquanto a seção Método
  tem 5 movimentos — possível inconsistência a resolver no Claude Design.
- Domínio próprio (ex. `francielefalconi.com.br`) no lugar do subdomínio — quando definido,
  atualizar `canonical`, tags OG/Twitter, `robots.txt` e `sitemap.xml`.
