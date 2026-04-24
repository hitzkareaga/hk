<?php
/**
 * Plugin Name: WP Site Control Toolkit
 * Description: Remove unnecessary WordPress behavior and improve baseline performance.
 * Version: 0.1.0
 * Text Domain: wp-site-control-toolkit
 * Author: Hitz Kareaga
 */

if (!defined('ABSPATH')) exit;

/* =========================
 * CONSTANTS
 * ========================= */

define('WPSCT_PATH', plugin_dir_path(__FILE__));
define('WPSCT_URL', plugin_dir_url(__FILE__));

/* =========================
 * TEXTDOMAIN
 * ========================= */

add_action('plugins_loaded', function () {
    load_plugin_textdomain(
        'wp-site-control-toolkit',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});

/* =========================
 * CONTENT LAYER (MUST LOAD FIRST)
 * ========================= */

require_once WPSCT_PATH . 'includes/content/content.php';

/* =========================
 * CORE + ADMIN
 * ========================= */

require_once WPSCT_PATH . 'includes/class-core.php';
require_once WPSCT_PATH . 'includes/class-admin.php';

/* =========================
 * INIT
 * ========================= */

add_action('plugins_loaded', function () {
    new WPSCT_Core();
    new WPSCT_Admin();
});