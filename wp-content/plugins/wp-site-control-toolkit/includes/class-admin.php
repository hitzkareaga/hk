<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin {

    private $features;
    private $render;

    public function __construct() {

        require_once WPSCT_PATH . 'includes/admin/class-admin-features.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-overview.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-render.php';

        $this->features = new WPSCT_Admin_Features();

        $this->render = new WPSCT_Admin_Render(
            $this->features
        );

        add_action('admin_menu', [$this, 'menu']);
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

        register_setting(
            'wpsct_group',
            'wpsct_settings',
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitize_settings'],
                'default' => []
            ]
        );
    }

    /**
     * 🔥 FIX REAL: merge global plano SIN romper tabs
     */
    public function sanitize_settings($input) {

        $old = get_option('wpsct_settings', []);

        if (!is_array($old)) {
            $old = [];
        }

        if (!is_array($input)) {
            $input = [];
        }

        /**
         * IMPORTANTE:
         * input viene SOLO con lo enviado en el submit actual
         * pero old contiene todo lo anterior
         */
        return array_merge($old, $input);
    }

    public function enqueue_assets($hook) {

        if ($hook !== 'toplevel_page_wpsct') return;

        wp_enqueue_style(
            'wpsct-admin',
            WPSCT_URL . 'assets/css/admin.css',
            [],
            '0.1.0'
        );
    }
}