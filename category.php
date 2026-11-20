<?php 
/**
 * Generic Category Page Template
 * 
 * Este arquivo serve para TODAS as categorias.
 * Elimina a necessidade de criar category-{slug}.php para cada categoria
 * Funciona dinamicamente baseado na categoria atual
 * 
 * @package Barons
 * @version 3.0
 */

get_header(); 

// Pegar a categoria atual
$current_category = get_queried_object();
if (! $current_category) {
    get_footer();
    exit;
}

$category_config = function_exists('barons_get_category')
    ? barons_get_category($current_category->slug)
    : null;
$category_color = $category_config ? $category_config['color'] : 'var(--color-barons-gold)';
?>

<header class="category-hero category-<?php echo esc_attr($current_category->slug); ?>" style="--category-accent: <?php echo esc_attr($category_color); ?>;">
    <div class="category-hero__inner">
        <span class="category-hero__eyebrow"><?php esc_html_e('Explore a categoria', 'barons'); ?></span>
        <h1><?php echo esc_html($current_category->name); ?></h1>
        <p>
            <?php
            printf(
                esc_html(_n('%d publicação', '%d publicações', (int) $current_category->count, 'barons')),
                (int) $current_category->count
            );
            ?>
        </p>
    </div>
</header>

<div class="main-conteudo container-fluid category-listing" style="--category-accent: <?php echo esc_attr($category_color); ?>;">
    <div class="row g-0">
        <div class="main-posts col-12 col-lg-9">
            <?php 
            if (have_posts()) : 
                while (have_posts()) : 
                    the_post(); 
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class("card-post"); ?>>
                <div class="card-thumbnail">
                    <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium_large', array(
                                'class' => 'category-card-thumb',
                                'alt'   => esc_attr(get_the_title()),
                            ));
                        } else {
                            $featured_url = get_post_featured_image_url();
                            echo '<img class="category-card-thumb" src="' . esc_url($featured_url) . '" alt="' . esc_attr(get_the_title()) . '" />';
                        }
                        ?>
                    </a>
                </div>
                <div class="card-texto">
                    <h2>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <?php if (get_the_excerpt()) : ?>
                        <p><?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 24, '…')); ?></p>
                    <?php endif; ?>
                    <a class="card-read-more" href="<?php the_permalink(); ?>">
                        <?php esc_html_e('Ler matéria', 'barons'); ?> <span aria-hidden="true">→</span>
                    </a>
                </div>
            </article>
            <?php 
                endwhile;    
            else: 
            ?>
                <p><?php esc_html_e('Nenhum post encontrado nesta categoria.', 'barons'); ?></p>
            <?php 
            endif; 
            ?>

            <nav class="paginacao d-flex justify-content-center align-items-center" aria-label="<?php esc_attr_e('Paginação da categoria', 'barons'); ?>">
                <?php 
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Anterior', 'barons'),
                    'next_text' => __('Próxima', 'barons'),
                    'screen_reader_text' => __('Mais Posts', 'barons'),
                )); 
                ?>
            </nav>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
