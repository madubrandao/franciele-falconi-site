/**
 * Otimização de imagens do site Franciele Falconi.
 * Fonte: assets/_raw/  ->  saída: assets/
 * Idempotente e tolerante a arquivos ausentes (roda parcial enquanto o hero não chega).
 *
 *   node scripts/optimize.mjs
 */
import { existsSync, mkdirSync } from "node:fs";
import { readFile } from "node:fs/promises";
import path from "node:path";
import sharp from "sharp";

const ROOT = path.resolve(import.meta.dirname, "..");
const RAW = path.join(ROOT, "assets", "_raw");
const OUT = path.join(ROOT, "assets");
mkdirSync(OUT, { recursive: true });

sharp.cache(false);

/** Converte um PNG/JPEG pesado em JPEG otimizado (mantém o visual). */
async function jpg(src, dst, { width, quality = 82 } = {}) {
  const from = path.join(RAW, src);
  if (!existsSync(from)) { console.log("  (pula, ausente) " + src); return; }
  let img = sharp(from).rotate();
  if (width) img = img.resize({ width, withoutEnlargement: true });
  const buf = await img.jpeg({ quality, mozjpeg: true, chromaSubsampling: "4:2:0" }).toBuffer();
  await sharp(buf).toFile(path.join(OUT, dst));
  console.log(`  ${src}  ->  ${dst}  (${(buf.length / 1024).toFixed(0)} KB)`);
}

async function favicons() {
  const src = path.join(RAW, "logo-icon.png");
  if (!existsSync(src)) { console.log("  (pula favicons) logo-icon.png ausente"); return; }
  await sharp(src).resize(48, 48).png().toFile(path.join(OUT, "favicon.png"));
  await sharp(src).resize(180, 180).png().toFile(path.join(OUT, "apple-touch-icon.png"));
  console.log("  favicon.png (48) + apple-touch-icon.png (180)");
}

async function ogImage() {
  const lockup = path.join(RAW, "logo-lockup.png");
  if (!existsSync(lockup)) { console.log("  (pula og-image) logo-lockup.png ausente"); return; }
  const W = 1200, H = 630;
  const logo = await sharp(await readFile(lockup)).resize({ width: 560 }).toBuffer();
  const bg = await sharp({
    create: { width: W, height: H, channels: 3, background: "#29311e" },
  }).png().toBuffer();
  await sharp(bg)
    .composite([{ input: logo, gravity: "center" }])
    .jpeg({ quality: 86, mozjpeg: true })
    .toFile(path.join(OUT, "og-image.jpg"));
  console.log("  og-image.jpg (1200x630)");
}

console.log("Fotos:");
await jpg("caminho-individual.png", "caminho-individual.jpg", { width: 1300 });
await jpg("hero-desktop.png", "hero-desktop.jpg", { width: 1920 });
await jpg("hero-tablet.png", "hero-tablet.jpg", { width: 1400 });
await jpg("hero-mobile.png", "hero-mobile.jpg", { width: 1100 });
console.log("Favicons:");
await favicons();
console.log("OG:");
await ogImage();
console.log("Feito.");
