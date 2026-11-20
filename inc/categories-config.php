<?php
/**
 * Barons Theme - Categories Configuration
 * 
 * Define todas as categorias do tema com suas cores
 * Não é necessário modificar CSS ou criar arquivos de categoria manualmente
 * 
 * @package Barons
 * @version 3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Obter configuração de categorias
 * 
 * @return array Array de categorias com slug, label e cor
 */
function barons_get_categories_config() {
    return apply_filters('barons_categories_config', array(
        'games' => array(
            'label' => __('GAMES', 'barons'),
            'color' => '#2B855E',
            'slug'  => 'games',
        ),
        'filmes' => array(
            'label' => __('FILMES', 'barons'),
            'color' => '#0081C7',
            'slug'  => 'filmes',
        ),
        'series' => array(
            'label' => __('SÉRIES', 'barons'),
            'color' => '#E27A3F',
            'slug'  => 'series',
        ),
        'hqs' => array(
            'label' => __('HQS', 'barons'),
            'color' => '#D61F15',
            'slug'  => 'hqs',
        ),
        'livros' => array(
            'label' => __('LIVROS', 'barons'),
            'color' => '#7062D9',
            'slug'  => 'livros',
        ),
        'animes' => array(
            'label' => __('ANIMES', 'barons'),
            'color' => '#E91E63',
            'slug'  => 'animes',
        ),
    ));
}

/**
 * Obter uma categoria específica
 * 
 * @param string $slug Slug da categoria
 * @return array|null Array com dados da categoria ou null
 */
function barons_get_category($slug) {
    $categories = barons_get_categories_config();
    return isset($categories[$slug]) ? $categories[$slug] : null;
}

/**
 * Gerar CSS dinâmico para variáveis de cor
 * Injetar como inline CSS para manter performances
 */
function barons_generate_categories_css() {
    $categories = barons_get_categories_config();
    $css = ":root {\n";
    
    foreach ($categories as $slug => $config) {
        $css .= sprintf("    --color-%s: %s;\n", $slug, $config['color']);
    }
    
    $css .= "}\n\n";
    
    // Gerar classes de animação
    $css .= "/* Categoria - Sublinhado animado */\n";
    foreach ($categories as $slug => $config) {
        $css .= sprintf(".cat-%s::after {\n", $slug);
        $css .= "\tcontent: '';\n";
        $css .= "\tdisplay: block;\n";
        $css .= "\twidth: 0;\n";
        $css .= "\theight: 2px;\n";
        $css .= sprintf("\tbackground: var(--color-%s);\n", $slug);
        $css .= "\ttransition: width var(--transition-base);\n";
        $css .= "}\n\n";
    }
    
    // Gerar classes de hover
    $css .= "/* Categoria - Hover states */\n";
    foreach ($categories as $slug => $config) {
        $css .= sprintf(".cat-%s:hover,\n", $slug);
        $css .= sprintf(".cat-%s:focus {\n", $slug);
        $css .= sprintf("\tcolor: var(--color-%s) !important;\n", $slug);
        $css .= "}\n\n";
    }
    
    // Gerar classes de mobile
    $css .= "@media only screen and (max-width: 990px) {\n";
    foreach ($categories as $slug => $config) {
        $css .= sprintf("\t.cat-%s {\n", $slug);
        $css .= sprintf("\t\tcolor: var(--color-%s) !important;\n", $slug);
        $css .= "\t}\n\n";
    }
    $css .= "}\n\n";
    
    // Gerar classes de categoria page (h1)
    $css .= "/* Categoria Page - Títulos */\n";
    foreach ($categories as $slug => $config) {
        $css .= sprintf(".category-%s h1 {\n", $slug);
        $css .= "\tfont-weight: 800;\n";
        $css .= sprintf("\tcolor: %s;\n", $config['color']);
        $css .= "\tfont-size: 80px;\n";
        $css .= "}\n\n";
    }
    
    // Gerar classes de fundo de categoria
    $css .= "/* Categoria - Background */\n";
    foreach ($categories as $slug => $config) {
        $css .= sprintf("div.category-%s {\n", $slug);
        $css .= sprintf("\tbackground-color: %s;\n", $config['color']);
        $css .= "}\n\n";
    }
    
    return $css;
}

/**
 * Output categories CSS inline
 */
function barons_output_categories_css() {
    echo '<style id="barons-categories-css">' . "\n";
    echo barons_generate_categories_css();
    echo "\n</style>\n";
}
add_action('wp_head', 'barons_output_categories_css', 5);
