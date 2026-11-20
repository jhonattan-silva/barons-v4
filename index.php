<?php get_header(); ?> <!--Equivale ao include, vai chamar o header-->
<?php
    // Usar configuração dinâmica de categorias
    $carousel_categories = barons_get_categories_config();
    $carousel_items = array();

    $all_categories = get_categories(array('hide_empty' => false));

    foreach ($carousel_categories as $slug => $config) {
        $category = get_category_by_slug($slug);
        if (!$category) {
            $category = get_term_by('name', $config['label'], 'category');
        }
        if (!$category) {
            $category = get_term_by('slug', sanitize_title($config['label']), 'category');
        }
        if (!$category && !empty($all_categories)) {
            foreach ($all_categories as $cat) {
                if (strcasecmp($cat->name, $config['label']) === 0) {
                    $category = $cat;
                    break;
                }
            }
        }
        if (!$category) {
            continue;
        }

        $query = new WP_Query(array(
            'cat' => $category->term_id,
            'posts_per_page' => 1,
            'post_type' => 'post',
            'post_status' => array('publish'),
            'perm' => 'readable',
            'ignore_sticky_posts' => true
        ));

        if ($query->have_posts()) {
            $query->the_post();
            $carousel_items[] = array(
                'image' => pegar_primeira_imagem_conteudo(get_the_ID()),
                'title' => get_the_title(),
                'subtitle' => get_post_meta(get_the_ID(), 'subtitulo', true),
                'link' => get_permalink()
            );
        }
        wp_reset_postdata();
    }

    if (empty($carousel_items)) {
        $carousel_items[] = array(
            'image' => get_template_directory_uri() . "/images/default.png",
            'title' => '',
            'subtitle' => '',
            'link' => ''
        );
    }
?>


<!-- CARROSSEL  *#*#*#**#*#**#*#**#*#*#*#**#**#*#**#*#**#*#*#**#*#*#**#**-->
<div id="carousel" class="carousel slide container-fluid" data-bs-ride="carousel">
    <div class="carousel-indicators">
		<?php foreach ($carousel_items as $index => $item) : ?>
			<button type="button" data-bs-target="#carousel" data-bs-slide-to="<?php echo esc_attr($index); ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo esc_attr($index + 1); ?>"></button>
		<?php endforeach; ?>
	</div>
    <div class="carousel-inner">
		<?php foreach ($carousel_items as $index => $item) : ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
            <img src="<?php echo esc_url($item['image']); ?>" class="d-block w-100" alt="<?php echo esc_attr($item['title']); ?>" />
            <div class="carousel-caption">
                <?php if (!empty($item['link'])) : ?>
                    <span class="titulo"><a href="<?php echo esc_url($item['link']); "> <?php echo esc_html($item['title']); ?> </a></span>
                <?php else : ?>
                    <span class="titulo"><?php echo esc_html($item['title']); ?></span>
                <?php endif; ?>
                <span class="subtitulo"><?php echo esc_html($item['subtitle']); ?></span>
            </div>
        </div>
		<?php endforeach; ?>
        <!-- Repita para os outros itens -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

  
    
<!-- FIM CARROSSEL *#*#*#**#*#**#*#**#*#*#*#**#**#*#**#*#**#*#*#**#*#*#**#**-->



    <div class="main-conteudo container-fluid category-listing home-listing"><!-- a partir do carrossel até chegar no footer -->
    	<div class="row g-0">
            <div class="main-posts col-12 col-lg-9"> <!-- toda listagem de posts até a paginação-->
                <?php
                $home_query = new WP_Query(array(
                	'post_type' => 'post',
                	'post_status' => 'publish',
                	'paged' => max(1, get_query_var('paged')),
                	'ignore_sticky_posts' => true
                ));
                if ($home_query->have_posts()) : while ($home_query->have_posts()) : $home_query->the_post(); ?> <!-- verifica se tem posts--> 
		        <?php
		        $post_categories = get_the_category();
		        $post_accent = 'var(--color-barons-gold)';
		        if (!empty($post_categories)) {
		            $post_category = barons_get_category($post_categories[0]->slug);
		            if ($post_category && !empty($post_category['color'])) {
		                $post_accent = $post_category['color'];
		            }
		        }
		        ?>
		        <article id="post-<?php the_ID(); ?>" <?php post_class('card-post'); ?> style="--category-accent: <?php echo esc_attr($post_accent); ?>;">
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
		            	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		            	<?php if (get_the_excerpt()) : ?>
		            		<p><?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 24, '…')); ?></p>
		            	<?php endif; ?>
		            	<a class="card-read-more" href="<?php the_permalink(); ?>">
		            		<?php esc_html_e('Ler matéria', 'barons'); ?> <span aria-hidden="true">→</span>
		            	</a>
		            </div>
		        </article>
                <?php endwhile; ?>    
                <?php else: ?>
                <?php endif; ?>
		        <!-- PAGINAÇÃO -->
				<div class="paginacao d-flex justify-content-center align-items-center"> 
                    <?php 
                    the_posts_pagination( array(
						'mid_size' => 2,
						'prev_text' => __( 'Anterior', 'textdomain' ),
						'next_text' => __( 'Proxima', 'textdomain' ),
						'screen_reader_text' => __('Mais Posts', 'textdomain'),
                    ) ); ?>
                    <?php wp_reset_postdata(); ?>
				</div> <!-- Fecha paginação -->
		    </div> <!-- Fecha a div com posts-->

			<?php get_sidebar(); ?>
 	    </div> <!--fecha row-->

    </div> <!-- Fecha a div(conteudo-total) com posts e sidebar -->    





<?php get_footer(); ?>