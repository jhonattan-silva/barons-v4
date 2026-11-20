<?php get_header(); ?> <!--Equivale ao include, vai chamar o header-->


     
    <div class="container-fluid">
        <div class="row col-12">
            <h1 class="palavra-buscada"><?php single_tag_title(); ?></h1>
        </div>
    </div>


    <div class="main-conteudo container-fluid "><!-- a partir do carrossel até chegar no footer -->
        <div class="row">
            <div class="main-posts col-12 col-sm-9 row h-100"> <!-- toda listagem de posts até a paginação-->
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?> <!-- verifica se tem posts--> 
                <div id="post-<?php the_ID(); ?>" <?php post_class("card-post col-md-5 col-12 row border"); ?> > <!-- cmd para identificar a categoria e criar a classe com o nome + a classe md-5 -->
                    <div class="card-thumbnail col"><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(200,350)); ?></a> </div><!--img do post-->
                    <div class="card-texto col">
                        <h3 > <a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a> </h3> <!-- Chama o titulo do post--><!-- permalink faz ele linkar com o post correspondente-->
                        <p> <?php the_excerpt(); ?> </p> <!--the_content(); Chama o conteudo do post, ja o excerpt mostra o resumo-->
                    </div>
                </div>
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
                </div> <!-- Fecha paginação -->
            </div> <!-- Fecha a div com posts-->

            <?php get_sidebar(); ?>
        </div> <!--fecha row-->

    </div> <!-- Fecha a div(conteudo-total) com posts e sidebar --> 



<?php get_footer(); ?>