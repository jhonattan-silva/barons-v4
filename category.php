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
?>

<div class="container-fluid">
    <div class="row col-12">
        <h1 class="text-center category-<?php echo esc_attr($current_category->slug); ?>">
            <?php 
            echo esc_html($current_category->name);
            ?>
        </h1>
    </div>
</div>

<div class="main-conteudo container-fluid">
    <div class="row">
        <div class="main-posts col-12 col-sm-9 row h-100">
            <?php 
            if (have_posts()) : 
                while (have_posts()) : 
                    the_post(); 
            ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class("card-post col-md-5 col-12 row border"); ?>>
                <div class="card-thumbnail col">
                    <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(array(200, 350)); ?>
                    </a>
                </div>
                <div class="card-texto col">
                    <h3>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <p><?php the_excerpt(); ?></p>
                </div>
            </div>
            <?php 
                endwhile;    
            else: 
            ?>
                <p><?php esc_html_e('Nenhum post encontrado nesta categoria.', 'barons'); ?></p>
            <?php 
            endif; 
            ?>

            <!-- PAGINAÇÃO -->
            <div class="paginacao d-flex justify-content-center align-items-center">
                <?php 
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Anterior', 'barons'),
                    'next_text' => __('Próxima', 'barons'),
                    'screen_reader_text' => __('Mais Posts', 'barons'),
                )); 
                ?>
            </div>
        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
