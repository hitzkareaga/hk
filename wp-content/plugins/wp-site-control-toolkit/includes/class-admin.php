<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin {

    private $presets;
    private $features;
    private $render;

    public function __construct() {

        require_once WPSCT_PATH . 'includes/admin/class-admin-presets.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-features.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-render.php';

        // IMPORTANT: presets ahora tiene hooks internos
        $this->presets = new WPSCT_Admin_Presets();

        $this->features = new WPSCT_Admin_Features();
        $this->render = new WPSCT_Admin_Render($this->presets, $this->features);

        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this->presets, 'handle_preset_save']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function menu() {
        add_menu_page(
            __('Site Control Toolkit', 'wp-site-control-toolkit'),
            __('Site Control', 'wp-site-control-toolkit'),
            'manage_options',
            'wpsct',
            [$this->render, 'render'],
            'dashicons-yes',
            58
        );
    }

    public function register_settings() {
        register_setting('wpsct_group', 'wpsct_settings');
    }

    public function enqueue_assets($hook) {

        if ($hook !== 'toplevel_page_wpsct') return;

        wp_enqueue_style(
            'wpsct-admin',
            WPSCT_URL . 'assets/css/admin.css',
            [],
            '0.1.0'
        );

        wp_enqueue_script(
            'wpsct-admin',
            WPSCT_URL . 'assets/js/admin.js',
            [],
            '0.1.0',
            true
        );
        
    }
}