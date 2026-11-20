<?php get_header(); ?>

<!-- Hero/Cartaz com Background Image -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
    <div class="cartaz-hero" style="background-image: url('<?php echo esc_url(pegar_primeira_imagem_conteudo(get_the_ID())); ?>');" role="img" aria-label="<?php echo esc_attr(get_the_title()); ?>">
        <!-- Overlay escuro para melhor legibilidade do texto -->
        <div class="cartaz-overlay"></div>
        
        <!-- Conteúdo do Hero -->
        <div class="cartaz-conteudo">
            <div class="container-fluid">
                <header class="entry-header">
                    <h1 class="cartaz-titulo" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>
                    <?php 
                    $subtitulo = get_post_meta(get_the_ID(), 'subtitulo', true);
                    if ($subtitulo) : 
                    ?>
                        <h2 class="cartaz-subtitulo" itemprop="alternativeHeadline">
                            <?php echo esc_html($subtitulo); ?>
                        </h2>
                    <?php endif; ?>
                    
                    <!-- Meta informações -->
                    <div class="entry-meta">
                        <time class="posted-on" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                            <?php echo get_the_date(); ?>
                        </time>
                        <span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="name"><?php the_author(); ?></span>
                        </span>
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                    </div>
                </header>
            </div>
        </div>
    </div> <!-- Fecha hero -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-9 row texto">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="entry-content" itemprop="articleBody">
                        <?php the_content(); ?>
                        
                        <?php 
                        wp_link_pages(array(
                            'before' => '<nav class="page-links" aria-label="' . esc_attr__('Páginas do post', 'barons') . '"><span class="page-links-title">' . __('Continue lendo:', 'barons') . '</span>',
                            'after'  => '</nav>',
                            'link_before' => '<span class="page-number">',
                            'link_after'  => '</span>',
                        )); 
                        ?>
                    </div>
                    
                    <?php endwhile; ?>
                <?php else : ?>
                    <p><?php esc_html_e('Desculpe, nenhum conteúdo encontrado.', 'barons'); ?></p>
                <?php endif; ?>
                
                <?php if (has_tag()) : ?>
                    <footer class="entry-footer">
                        <p class="tags-post" itemprop="keywords">
                            <?php the_tags(__('Tags: ', 'barons'), ' &bull; ', ''); ?>
                        </p>
                    </footer>
                <?php endif; ?>
                
                <!-- Comentários -->
                <?php 
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div> <!-- fecha o container -->
</article>

<?php get_footer(); ?>