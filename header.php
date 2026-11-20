<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <!-- SEO Meta Tags -->
        <meta name="google-site-verification" content="oHNfqbmV8rJT-HZuAkQLJ-ULReaLKpZhYAIsA2nZJJY">
        
        <!-- Preconnect para performance -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://cdn.jsdelivr.net">
        <link rel="preconnect" href="https://cdnjs.cloudflare.com">
        <link rel="preconnect" href="https://connect.facebook.net">
        <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
        
        <!-- Google AdSense -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2328129870371714" crossorigin="anonymous"></script>
        
        <?php wp_head(); ?>
    </head>
        
    <body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
        
        <!-- Facebook SDK -->
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v18.0&appId=1205465382814972&autoLogAppEvents=1" nonce="hdQdlpBh"></script>
        
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark header-categorias" role="navigation" aria-label="Menu principal">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="navbar-brand logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home">BARONS</a>
            <?php endif; ?>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'barons'); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse navbar-categorias" id="navbarNavAltMarkup">
                <div class="navbar-nav mx-auto">
                    <?php
                    $categories = barons_get_categories_config();
                    foreach ($categories as $slug => $config) {
                        $category_id = get_cat_ID($slug);
                        if ($category_id) {
                            $category_link = get_category_link($category_id);
                            echo sprintf(
                                '<a class="nav-item nav-link cat-%s" href="%s">%s</a>',
                                esc_attr($slug),
                                esc_url($category_link),
                                esc_html($config['label'])
                            );
                        }
                    }
                    ?>
                </div>
            </div>
        </nav>
