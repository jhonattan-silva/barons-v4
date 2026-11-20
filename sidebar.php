<div class="sidebares col-12 col-md-3"> <!-- Abre Div para o Sidebar -->

    <!-- Formulário de pesquisa -->
    <form role="form" class="pesquisa" action="<?php bloginfo('siteurl'); ?>/" id="searchform" method="get">
        <div class="form-group">
            <div class="input-group"> 
            <input type="search" class="form-control" id="s" name="s" placeholder="Pesquise aqui..." />
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-block" >
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </span>
            </div>
        </div>
    </form> <!-- Fim formulário de pesquisa -->

    <hr>
    <div class="social-sidebar">
        <h3>SIGAM-ME OS BONS!</h3>
        <!-- Facebook -->
        <a class="btn btn-outline-light btn-floating m-3" href="https://facebook.com/baronsnews" role="button" target="_blank">
            <i class="fab fa-facebook-f"></i>
        </a>
        <!-- Twitter -->
        <a class="btn btn-outline-light btn-floating m-3" href="https://twitter.com/baronsnews" role="button" target="_blank">
            <i class="fab fa-twitter"></i>
        </a>
        <!-- Instagram -->
        <a class="btn btn-outline-light btn-floating m-3" href="https://instagram.com/barons.br/" role="button" target="_blank">
            <i class="fab fa-instagram"></i>
        </a>
    </div>
    <hr>
    <div class="tags-sidebar"> 
        <?php if ( function_exists( 'wp_tag_cloud' ) ) : ?>
            <ul>
                <h3>TAGS POPULARES</h3>
                <li><?php wp_tag_cloud( 'smallest=8&largest=22' ); ?></li>
            </ul>
        <?php endif; ?>
    </div>
    <hr>
    <!-- Facebook Plug-in -->
    <div>
        <div class="fb-page" data-href="https://www.facebook.com/baronsnews" data-tabs="timeline" data-width="" data-height="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/baronsnews" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/baronsnews">Barons</a></blockquote></div>
    </div>
    <hr>
    <div class="random-sidebar">
       
            <h3>DESCUBRA O QUE NÃO PROCURAVA</h3>
            <?php $posts = get_posts('orderby=rand&numberposts=5'); foreach($posts as $post) { ?>
                
                    <div class="post-random"> 
                        <div class="miniatura_random"><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(200,350)); ?></a> </div><!--img do post-->
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                        </br>
                        <span class="d-none d-md-block"><?php $sub = get_post_custom_values('subtitulo'); echo $sub[0]; ?></span> <!-- substitui o meta por causa do disqus, some tela pequena -->
                    </div>
                
            <?php } ?>
        
    </div>
</div> <!-- Fecha Div Sidebares -->