<?php
/**
 * Plugin Name: WP Site Control Toolkit
 * Description: Remove unnecessary WordPress behavior and improve baseline performance.
 * Version: 0.1.0
 * Text Domain: wp-site-control-toolkit
 */

if (!defined('ABSPATH')) exit;

define('WPSCT_PATH', plugin_dir_path(__FILE__));
define('WPSCT_URL', plugin_dir_url(__FILE__));

// Load textdomain
add_action('plugins_loaded', function () {
    load_plugin_textdomain('wp-site-control-toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Includes
require_once WPSCT_PATH . 'includes/class-core.php';
require_once WPSCT_PATH . 'includes/class-admin.php';

// Init
function wpsct_init() {
    new WPSCT_Core();
    new WPSCT_Admin();
}
add_action('plugins_loaded', 'wpsct_init');