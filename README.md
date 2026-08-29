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

## Pendências

- **Depoimentos** na seção `#depoimentos` são placeholder — trocar pelos reais da Fran.
- **WhatsApp** usado nos CTAs: `5548991118771` — confirmar.
- Selo do hero diz "ACOLHER · COMPREENDER · TRANSFORMAR" (3 termos) enquanto a seção Método
  tem 5 movimentos — possível inconsistência a resolver no Claude Design.
- Domínio próprio (ex. `francielefalconi.com.br`) no lugar do subdomínio — quando definido,
  atualizar `canonical`, tags OG/Twitter, `robots.txt` e `sitemap.xml`.
