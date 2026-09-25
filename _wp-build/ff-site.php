<?php
/**
 * Plugin Name: Franciele Falconi — Site Assets
 * Description: Fontes, CSS e JS do site portado do design v2. Editar CSS/JS em wp-content/mu-plugins/ff-assets/.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'ff-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Montserrat:wght@300;400;500;600&family=Petit+Formal+Script&display=swap',
        array(), null
    );

    $dir = __DIR__ . '/ff-assets';
    $url = plugins_url('ff-assets', __FILE__);

    if (file_exists($dir . '/ff-assets.css')) {
        wp_enqueue_style('ff-site', $url . '/ff-assets.css', array('ff-fonts'), filemtime($dir . '/ff-assets.css'));
    }
    if (file_exists($dir . '/ff-assets.js')) {
        wp_enqueue_script('ff-site', $url . '/ff-assets.js', array(), filemtime($dir . '/ff-assets.js'), true);
    }
}, 20);
