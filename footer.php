
<footer class="bg-dark text-center text-white" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
  <!-- Grid container -->
    <div class="container p-4 pb-0">
        <section class="mb-4" aria-label="Redes Sociais">
            <!-- Facebook -->
            <a class="btn btn-lg btn-outline-light btn-floating m-3" 
               href="https://facebook.com/baronsnews" 
               role="button" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="Visite nosso Facebook">
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
            </a>
            <!-- Twitter -->
            <a class="btn btn-lg btn-outline-light btn-floating m-3" 
               href="https://twitter.com/baronsnews" 
               role="button" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="Visite nosso Twitter">
                <i class="fab fa-twitter" aria-hidden="true"></i>
            </a>
            <!-- Instagram -->
            <a class="btn btn-lg btn-outline-light btn-floating m-3" 
               href="https://instagram.com/barons.br/" 
               role="button" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="Visite nosso Instagram">
                <i class="fab fa-instagram" aria-hidden="true"></i>
            </a>
        </section>
    </div>
    <div class="text-center p-2">
        <p>&copy; 2015 - <?php echo esc_html(date('Y')); ?> Barons. Todos os direitos reservados.</p>
        <?php if (has_nav_menu('footer')) : ?>
            <nav aria-label="Menu do rodapé">
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'footer-menu',
                    'depth' => 1,
                )); ?>
            </nav>
        <?php endif; ?>
    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
