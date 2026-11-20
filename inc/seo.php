<?php
/**
 * SEO Optimizations
 * 
 * @package Barons
 * @version 3.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adiciona Open Graph Meta Tags
 */
function barons_add_og_tags() {
    if (is_singular()) {
        global $post;
        
        $og_title = get_the_title();
        $og_description = get_the_excerpt();
        $og_url = get_permalink();
        $og_image = get_post_featured_image_url($post->ID);
        $og_type = 'article';
        
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        
        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
        
        // Article Meta
        if (is_single()) {
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
            
            $categories = get_the_category();
            if ($categories) {
                foreach ($categories as $category) {
                    echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                }
            }
            
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                }
            }
        }
    } else {
        // Página inicial ou arquivo
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
    }
}
add_action('wp_head', 'barons_add_og_tags', 5);

/**
 * Adiciona Schema.org JSON-LD
 */
function barons_add_schema_markup() {
    if (is_singular('post')) {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => get_post_featured_image_url($post->ID),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_url() . '/wp-content/uploads/logo.png'
                )
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink()
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
    
    // Breadcrumbs Schema
    if (is_singular() || is_category() || is_tag()) {
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array()
        );
        
        $breadcrumb_schema['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => home_url('/')
        );
        
        if (is_category()) {
            $category = get_queried_object();
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $category->name,
                'item' => get_category_link($category->term_id)
            );
        } elseif (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $breadcrumb_schema['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $categories[0]->name,
                    'item' => get_category_link($categories[0]->term_id)
                );
            }
            
            $breadcrumb_schema['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'barons_add_schema_markup', 10);

/**
 * Canonical URLs
 */
function barons_add_canonical() {
    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    }
}
add_action('wp_head', 'barons_add_canonical', 1);

/**
 * Add Meta Description
 */
function barons_add_meta_description() {
    if (is_singular()) {
        $description = get_the_excerpt();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n";
        }
    } elseif (is_category()) {
        $description = category_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'barons_add_meta_description', 1);

/**
 * Robots Meta Tag
 */
function barons_add_robots_meta() {
    if (is_search() || is_404()) {
        echo '<meta name="robots" content="noindex,nofollow">' . "\n";
    }
}
add_action('wp_head', 'barons_add_robots_meta', 1);
