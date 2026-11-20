<?php
/**
 * Performance Optimizations
 * 
 * @package Barons
 * @version 3.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove Query Strings from Static Resources
 * Melhora cache e performance
 */
function barons_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'barons_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'barons_remove_query_strings', 15, 1);

/**
 * Disable Emojis - Remove scripts desnecessários
 */
function barons_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'barons_disable_emojis');

/**
 * Remove Embeds - Desabilita oEmbeds se não usado
 */
function barons_disable_embeds() {
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'barons_disable_embeds');

/**
 * Lazy Load Images - Adiciona loading="lazy" automaticamente
 */
function barons_add_lazy_loading($content) {
    if (is_admin()) {
        return $content;
    }
    
    // Adiciona loading="lazy" a todas as imagens
    $content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'barons_add_lazy_loading', 99);
add_filter('post_thumbnail_html', 'barons_add_lazy_loading', 99);

/**
 * Preload Critical Resources
 */
function barons_preload_resources() {
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/js/meus_scripts.js') . '" as="script">';
    echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style">';
}
add_action('wp_head', 'barons_preload_resources', 1);

/**
 * Defer CSS Loading para estilos não críticos
 */
function barons_defer_css_loading($html, $handle) {
    $deferred_styles = array('font-awesome');
    
    if (in_array($handle, $deferred_styles)) {
        $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
    }
    
    return $html;
}
add_filter('style_loader_tag', 'barons_defer_css_loading', 10, 2);

/**
 * Remove REST API Links if not needed
 */
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');

/**
 * Remove Windows Live Writer manifest link
 */
remove_action('wp_head', 'wlwmanifest_link');

/**
 * Remove shortlink
 */
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Disable RSS Feeds se não usado
 */
function barons_disable_feed() {
    // Descomente se não usar RSS feeds
    // wp_die(__('No feed available, please visit our <a href="' . get_bloginfo('url') . '">homepage</a>!'));
}
// add_action('do_feed', 'barons_disable_feed', 1);
// add_action('do_feed_rdf', 'barons_disable_feed', 1);
// add_action('do_feed_rss', 'barons_disable_feed', 1);
// add_action('do_feed_rss2', 'barons_disable_feed', 1);
// add_action('do_feed_atom', 'barons_disable_feed', 1);

/**
 * Limit Post Revisions
 */
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

/**
 * Increase Autosave Interval
 */
if (!defined('AUTOSAVE_INTERVAL')) {
    define('AUTOSAVE_INTERVAL', 300); // 5 minutos
}

/**
 * Optimize Database Tables on Theme Activation
 */
function barons_optimize_database() {
    global $wpdb;
    $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);
    
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
    }
}
// Execute uma vez via admin se necessário
// add_action('after_switch_theme', 'barons_optimize_database');
