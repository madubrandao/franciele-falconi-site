<?php
/**
 * Plugin Name: Franciele Falconi — Site Assets (v4 liquid glass)
 * Description: Fontes, CSS e JS da Home v4 (design Claude Design, 2026-09-24). Arquivos em wp-content/mu-plugins/ff-assets/.
 *              A v3 (ff-assets.css/js) continua no disco, sem ser carregada, para rollback.
 * Version: 4.0.0
 */
if (!defined('ABSPATH')) exit;

/* so a Home (front page) usa a v4 */
function ff4_is_home() { return is_front_page(); }

add_filter('body_class', function ($c) { if (ff4_is_home()) $c[] = 'ff4'; return $c; });

add_action('wp_enqueue_scripts', function () {
    if (!ff4_is_home()) return;
    wp_enqueue_style(
        'ff4-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600;700&family=Pinyon+Script&display=swap',
        array(), null
    );
    $dir = __DIR__ . '/ff-assets';
    $url = plugins_url('ff-assets', __FILE__);
    if (file_exists($dir . '/ff4-assets.css')) {
        wp_enqueue_style('ff4-site', $url . '/ff4-assets.css', array('ff4-fonts'), filemtime($dir . '/ff4-assets.css'));
    }
    foreach (array('ff4-garden', 'ff4-ui') as $h) {
        if (file_exists($dir . '/' . $h . '.js')) {
            wp_enqueue_script($h, $url . '/' . $h . '.js', array(), filemtime($dir . '/' . $h . '.js'), true);
        }
    }
}, 20);

/* SEO (README do handoff) — so na Home */
add_filter('pre_get_document_title', function ($t) {
    return ff4_is_home() ? 'Franciele Falconi | Terapeuta Psicanalista Sistêmica em Florianópolis' : $t;
}, 20);
add_action('wp_head', function () {
    if (!ff4_is_home()) return;
    echo '<meta name="description" content="Desenvolvimento humano e saúde emocional para pessoas e empresas. Método Falconi: um caminho estruturado para transformação emocional real. Atendimento em Florianópolis, SC, e online.">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1);
