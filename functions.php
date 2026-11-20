<?php
/**
 * Barons Theme v3.0 - Functions
 * 
 * @package Barons
 * @version 3.0
 */

// Segurança: impedir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Carregar configuração de categorias
require_once get_template_directory() . '/inc/categories-config.php';

/**
 * Configuração do tema
 */
function barons_theme_setup() {
    // Suporte a thumbnails
    add_theme_support('post-thumbnails');
    
    // Suporte a título dinâmico
    add_theme_support('title-tag');
    
    // Suporte a HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ));
    
    // Suporte a feeds automáticos
    add_theme_support('automatic-feed-links');
    
    // Suporte a custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Suporte a cores customizáveis
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => 'Dourado Barons',
            'slug'  => 'barons-gold',
            'color' => '#D2B887',
        ),
        array(
            'name'  => 'Fundo Escuro',
            'slug'  => 'barons-dark',
            'color' => '#1E1E21',
        ),
    ));
    
    // Registrar menus
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'barons'),
        'footer'  => __('Menu Footer', 'barons'),
    ));
}
add_action('after_setup_theme', 'barons_theme_setup');

/**
 * Carregamento de scripts e estilos
 */
function barons_enqueue_scripts() {
    // Bootstrap 5.3.3 (última versão estável)
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);
    
    // Font Awesome 6.5.1 (última versão)
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
    
    // jQuery (WordPress já inclui, use a versão dele)
    wp_enqueue_script('jquery');
    
    // CSS e JS do tema com cache inválido automaticamente após cada alteração
    $theme_directory = get_stylesheet_directory();
    $style_version = file_exists($theme_directory . '/style.css')
        ? filemtime($theme_directory . '/style.css')
        : '3.0';
    $script_version = file_exists($theme_directory . '/js/meus_scripts.js')
        ? filemtime($theme_directory . '/js/meus_scripts.js')
        : '3.0';

    wp_enqueue_script('barons-scripts', get_template_directory_uri() . '/js/meus_scripts.js', array('jquery'), $script_version, true);
    wp_enqueue_style('barons-style', get_stylesheet_uri(), array('bootstrap-css'), $style_version);
    
    // Adiciona suporte a comentários threaded
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'barons_enqueue_scripts');

/**
 * Remove a margem superior padrão da barra administrativa
 */
function barons_remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'barons_remove_admin_login_header');

// Pega a primeira imagem do post de uma categoria específica
function img_principal($num_cat) {
	global $post;
	
	$the_query = new WP_Query(array(
		'cat'              => $num_cat,
		'posts_per_page'   => 1,
		'post_status'      => 'publish',
		'suppress_filters' => false
	));
	
	if ($the_query->have_posts()) {
		$the_query->the_post();
		$image_url = get_post_featured_image_url();
		wp_reset_postdata();
		return $image_url;
	}
	
	wp_reset_postdata();
	return get_template_directory_uri() . "/images/default.png";
}

// Pega os dados do post mais recente de uma categoria (para o carrossel)
function get_carousel_item_data($cat_id) {
	$query = new WP_Query(array(
		'cat' => $cat_id,
		'posts_per_page' => 1,
		'post_status' => 'publish'
	));

	if ($query->have_posts()) {
		$query->the_post();
		$data = array(
			'image' => get_post_featured_image_url(get_the_ID()),
			'title' => get_the_title(),
			'subtitle' => get_post_meta(get_the_ID(), 'subtitulo', true),
			'link' => get_permalink()
		);
		wp_reset_postdata();
		return $data;
	}

	wp_reset_postdata();
	return array(
		'image' => get_template_directory_uri() . "/images/default.png",
		'title' => '',
		'subtitle' => '',
		'link' => ''
	);
}

// Pega a URL da imagem destacada (ou a primeira do conteúdo como fallback)
function get_post_featured_image_url($post_id = null) {
	if (!$post_id) {
		global $post;
		$post_id = $post->ID;
	}
	
	// 1. Tentar a imagem destacada primeiro
	if (has_post_thumbnail($post_id)) {
		return wp_get_attachment_url(get_post_thumbnail_id($post_id));
	}
	
	// 2. Se não houver imagem destacada, procurar no conteúdo
	$post = get_post($post_id);
	if (isset($post->post_content) && !empty($post->post_content)) {
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		
		if ($output && isset($matches[1][0]) && !empty($matches[1][0])) {
			return $matches[1][0];
		}
	}
	
	// 3. Retorna a imagem padrão
	return barons_get_default_image_url();
}

// Pega a primeira imagem do conteúdo do post (cartaz)
function pegar_primeira_imagem_conteudo($post_id = null) {
	if (!$post_id) {
		global $post;
		$post_id = $post->ID;
	}

	$post = get_post($post_id);
	if (!isset($post->post_content) || empty($post->post_content)) {
		return barons_get_default_image_url();
	}

	$output = preg_match_all('/<img.+src=[\'\"]([^\'\"]+)[\'\"].*>/i', $post->post_content, $matches);
	if ($output && isset($matches[1][0]) && !empty($matches[1][0])) {
		// Guarda a URL usada no cartaz para remover apenas essa imagem do conteúdo
		$GLOBALS['hero_image_url'] = $matches[1][0];
		return $matches[1][0];
	}

	return barons_get_default_image_url();
}

// Pega a primeira imagem do post (prioriza a imagem destacada) e remove do conteúdo para não duplicar
function catch_that_image() {
	global $post;
	
	if (!isset($post)) {
		return barons_get_default_image_url();
	}
	
	$image_url = get_post_featured_image_url($post->ID);
	$GLOBALS['remove_first_image_from_content'] = true;
	
	return $image_url;
}

// Remove a primeira imagem do conteúdo para não aparecer duplicada
function remove_first_image_from_content($content) {
	// Em single, remove apenas a imagem usada no cartaz
	if (is_single() && !empty($GLOBALS['hero_image_url'])) {
		$hero_image_url = preg_quote($GLOBALS['hero_image_url'], '/');
		$content = preg_replace('/<img[^>]+src=[\'"]' . $hero_image_url . '[\'"][^>]*>/i', '', $content, 1);
	}
	return $content;
}
add_filter('the_content', 'remove_first_image_from_content', 1);

// Define automaticamente a primeira imagem como imagem destacada ao salvar/publicar
add_action('save_post_post', 'set_first_image_as_thumbnail', 10, 1);
function set_first_image_as_thumbnail($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (wp_is_post_revision($post_id)) {
		return;
	}
	if (get_post_type($post_id) !== 'post') {
		return;
	}
	if (has_post_thumbnail($post_id)) {
		return;
	}

	$post = get_post($post_id);
	if (!$post || empty($post->post_content)) {
		return;
	}

	preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $post->post_content, $matches);
	if (empty($matches[1][0])) {
		return;
	}

	$attachment_id = get_attachment_id_by_url($matches[1][0]);
	if ($attachment_id) {
		set_post_thumbnail($post_id, $attachment_id);
	}
}

// Função auxiliar para pegar o ID do anexo pela URL
function get_attachment_id_by_url($url) {
	$attachment_id = attachment_url_to_postid($url);
	if ($attachment_id) {
		return $attachment_id;
	}

	global $wpdb;
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid = %s LIMIT 1",
			esc_url_raw($url)
		)
	);
}

// Define o tamanho do resumo na página inicial
add_filter('excerpt_length', 'custom_excerpt_length');
function custom_excerpt_length($length) {
	return 30; // Nova quantidade de caracteres do resumo
}

// Caixa de metadados para subtítulo no editor de posts
add_action('add_meta_boxes', 'barons_add_subtitle_metabox');
function barons_add_subtitle_metabox() {
	add_meta_box(
		'barons_subtitle_metabox',
		'Subtitulo do Post',
		'barons_render_subtitle_metabox',
		'post',
		'side',
		'high'
	);
}

function barons_render_subtitle_metabox($post) {
	$subtitle = get_post_meta($post->ID, 'subtitulo', true);
	wp_nonce_field('barons_save_subtitle', 'barons_subtitle_nonce');
	
	echo '<label for="barons_subtitle_field">Subtitulo</label>';
	echo '<input type="text" id="barons_subtitle_field" name="barons_subtitle_field" value="' . esc_attr($subtitle) . '" style="width:100%; margin-top:8px;" />';
	echo '<p style="margin:6px 0 0 0; color:#666;">Digite o texto que aparece abaixo do titulo.</p>';
}

add_action('save_post', 'barons_save_subtitle_metabox');
function barons_save_subtitle_metabox($post_id) {
	if (!isset($_POST['barons_subtitle_nonce']) || !wp_verify_nonce($_POST['barons_subtitle_nonce'], 'barons_save_subtitle')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (wp_is_post_revision($post_id)) {
		return;
	}
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (isset($_POST['barons_subtitle_field'])) {
		$subtitle = sanitize_text_field($_POST['barons_subtitle_field']);
		update_post_meta($post_id, 'subtitulo', $subtitle);
	}
}

/**
 * Filtro para exibir apenas posts publicados nas páginas de categoria
 */
function barons_filter_private_posts($query) {
	if (!is_admin() && $query->is_main_query() && is_category()) {
		$query->set('post_status', 'publish');
	}
	return $query;
}
add_action('pre_get_posts', 'barons_filter_private_posts');

/**
 * Cabeçalhos de segurança
 */
function barons_add_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
add_action('send_headers', 'barons_add_security_headers');

/**
 * Otimização: remove a versão do WordPress de scripts e estilos
 */
function barons_remove_wp_version_strings($src) {
    global $wp_version;
    parse_str(parse_url($src, PHP_URL_QUERY), $query);
    if (!empty($query['ver']) && $query['ver'] === $wp_version) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'barons_remove_wp_version_strings');
add_filter('style_loader_src', 'barons_remove_wp_version_strings');

/**
 * Adiciona async e defer em scripts externos para melhor performance
 */
function barons_add_async_defer_attributes($tag, $handle) {
    if ($handle === 'bootstrap-js' || $handle === 'barons-scripts') {
        if (strpos($tag, ' defer') === false) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    return $tag;
}
add_filter('script_loader_tag', 'barons_add_async_defer_attributes', 10, 2);

/**
 * Áreas de widgets
 */
function barons_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar Principal', 'barons'),
        'id'            => 'sidebar-1',
        'description'   => __('Área de widgets da sidebar', 'barons'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'barons_widgets_init');

/**
 * Inclui arquivos adicionais
 */
// Otimizações de performance
if (file_exists(get_template_directory() . '/inc/performance.php')) {
    require_once get_template_directory() . '/inc/performance.php';
}

// Otimizações de SEO
if (file_exists(get_template_directory() . '/inc/seo.php')) {
    require_once get_template_directory() . '/inc/seo.php';
}

// Melhorias de segurança
if (file_exists(get_template_directory() . '/inc/security.php')) {
    require_once get_template_directory() . '/inc/security.php';
}

// URL da imagem padrão, respeitando uploads personalizados e HTTPS
function barons_get_default_image_url() {
	$upload_dir = wp_upload_dir();
	return trailingslashit($upload_dir['baseurl']) . 'imagens/default.png';
}