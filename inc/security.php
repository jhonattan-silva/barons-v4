<?php
/**
 * Security Enhancements
 * 
 * @package Barons
 * @version 3.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove WordPress Version from Head and RSS
 */
function barons_remove_version() {
    return '';
}
add_filter('the_generator', 'barons_remove_version');

/**
 * Remove Version from Scripts and Styles
 */
function barons_remove_script_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'barons_remove_script_version', 15, 1);
add_filter('style_loader_src', 'barons_remove_script_version', 15, 1);

/**
 * Hide WordPress Login Errors
 */
function barons_hide_login_errors() {
    return __('Algo está errado!', 'barons');
}
add_filter('login_errors', 'barons_hide_login_errors');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove RSD Link
 */
remove_action('wp_head', 'rsd_link');

/**
 * Disable File Editing in Dashboard
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Add Security Headers
 */
function barons_security_headers() {
    // X-Content-Type-Options
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer-Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions-Policy
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    
    // Content-Security-Policy (ajuste conforme necessário)
    // Descomente e ajuste se necessário
    // header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://connect.facebook.net https://pagead2.googlesyndication.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;");
}
add_action('send_headers', 'barons_security_headers');

/**
 * Disable Directory Browsing
 */
function barons_disable_directory_browsing() {
    $htaccess_file = ABSPATH . '.htaccess';
    
    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $htaccess_content = file_get_contents($htaccess_file);
        
        if (strpos($htaccess_content, 'Options -Indexes') === false) {
            $new_content = "# Disable Directory Browsing\nOptions -Indexes\n\n" . $htaccess_content;
            file_put_contents($htaccess_file, $new_content);
        }
    }
}
// Execute uma vez via admin se necessário
// add_action('after_switch_theme', 'barons_disable_directory_browsing');

/**
 * Sanitize File Names on Upload
 */
function barons_sanitize_file_name($filename) {
    $sanitized = remove_accents($filename);
    $sanitized = preg_replace('/[^a-zA-Z0-9._-]/', '', $sanitized);
    return strtolower($sanitized);
}
add_filter('sanitize_file_name', 'barons_sanitize_file_name', 10);

/**
 * Limit Login Attempts (básico - considere usar plugin dedicado)
 */
function barons_check_attempted_login($user, $username, $password) {
    if (get_transient('attempted_login_' . $username)) {
        $datas = get_transient('attempted_login_' . $username);

        if ($datas['tried'] >= 3) {
            $until = get_option('_transient_timeout_' . 'attempted_login_' . $username);
            $time = barons_time_to_go($until);

            return new WP_Error('too_many_tried', sprintf(__('<strong>ERRO</strong>: Você atingiu o limite de tentativas de login. Por favor, tente novamente em %1$s.', 'barons'), $time));
        }
    }

    return $user;
}
add_filter('authenticate', 'barons_check_attempted_login', 30, 3);

function barons_login_failed($username) {
    if (get_transient('attempted_login_' . $username)) {
        $datas = get_transient('attempted_login_' . $username);
        $datas['tried']++;

        if ($datas['tried'] <= 3) {
            set_transient('attempted_login_' . $username, $datas, 300); // 5 minutos
        }
    } else {
        $datas = array(
            'tried' => 1
        );
        set_transient('attempted_login_' . $username, $datas, 300);
    }
}
add_action('wp_login_failed', 'barons_login_failed', 10, 1);

function barons_time_to_go($timestamp) {
    $periods = array('segundo', 'minuto', 'hora', 'dia', 'semana', 'mês', 'ano');
    $lengths = array(60, 60, 24, 7, 4.35, 12);
    $current_timestamp = time();
    $difference = abs($current_timestamp - $timestamp);

    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i++) {
        $difference /= $lengths[$i];
    }

    $difference = round($difference);
    
    if ($difference != 1) {
        $periods[$i] .= 's';
    }

    return "$difference $periods[$i]";
}

/**
 * Force SSL for Admin and Login
 * Descomente se tiver certificado SSL
 */
// define('FORCE_SSL_ADMIN', true);

/**
 * Change Default WordPress Database Prefix
 * Faça isso manualmente durante a instalação
 * Tabelas não devem usar wp_ como prefixo
 */

/**
 * Protect wp-config.php
 * Adicione ao .htaccess (fora do WordPress):
 * 
 * <files wp-config.php>
 * order allow,deny
 * deny from all
 * </files>
 */

/**
 * Protect .htaccess
 * Adicione ao .htaccess:
 * 
 * <files .htaccess>
 * order allow,deny
 * deny from all
 * </files>
 */
