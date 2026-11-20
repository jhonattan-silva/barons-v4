/**
 * Barons Theme - Custom Scripts
 * @version 3.0
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        /**
         * Configuração do Carousel Bootstrap
         */
        if ($('.carousel').length) {
            $('.carousel').carousel({
                pause: false,        // Não pausa quando coloca o mouse em cima
                interval: 7000,      // Tempo em cada slide (7 segundos)
                ride: 'carousel'     // Inicia automaticamente
            });
        }
        
        /**
         * Ajustando a imagem do single post como background
         * Para efeito visual melhor
         */
        $('.overlay div.content img').each(function() {
            var imgSrc = $(this).attr('src');
            
            if (imgSrc) {
                $(this).parent().css({
                    'background': 'url(' + imgSrc + ') center center no-repeat',
                    'background-size': 'cover',
                    'z-index': '-99'
                });
                
                // Remove a imagem original para deixar apenas o background
                $('.content > img, .capa').remove();
            }
        });
        
        /**
         * Botão Show/Hide para conteúdo adicional
         */
        $('.ver').on('click', function(e) {
            e.preventDefault();
            $(this).parent().nextAll('.invisivel').first().slideToggle('slow');
            
            // Toggle texto do botão
            var $btn = $(this);
            if ($btn.text().includes('Ver')) {
                $btn.text($btn.text().replace('Ver', 'Ocultar'));
            } else {
                $btn.text($btn.text().replace('Ocultar', 'Ver'));
            }
        });
        
        /**
         * Menu Mobile Toggle
         */
        $('.menu-mobile').on('click touchstart', function(e) {
            e.preventDefault();
            $('html').toggleClass('menu-active');
            $(this).attr('aria-expanded', function(i, attr) {
                return attr === 'true' ? 'false' : 'true';
            });
        });
        
        /**
         * Lazy Loading de Imagens (performance)
         */
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading=\"lazy\"]');
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        } else {
            // Fallback para navegadores antigos
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
        }
        
        /**
         * Smooth Scroll para links âncora
         */
        $('a[href^=\"#\"]').on('click', function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
        
        /**
         * Acessibilidade: Focus visível em navegação por teclado
         */
        $(document).on('keydown', function(e) {
            if (e.key === 'Tab') {
                $('body').addClass('keyboard-navigation');
            }
        });
        
        $(document).on('mousedown', function() {
            $('body').removeClass('keyboard-navigation');
        });
        
    }); // End document ready
    
})(jQuery);
