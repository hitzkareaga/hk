<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin {

    private $features;
    private $render;

    public function __construct() {

        require_once WPSCT_PATH . 'includes/admin/class-admin-features.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-plans.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-overview.php';
        require_once WPSCT_PATH . 'includes/admin/class-admin-render.php';

        $this->features = new WPSCT_Admin_Features();

        $this->render = new WPSCT_Admin_Render(
            $this->features,
            new WPSCT_Admin_Plans()
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
            81
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

    public function sanitize_settings($input) {

        $old = get_option('wpsct_settings', []);
        $current_tab = isset($_POST['wpsct_current_tab'])
            ? sanitize_key(wp_unslash($_POST['wpsct_current_tab']))
            : '';
        $features = $this->features->get_features();

        if (!is_array($old)) {
            $old = [];
        }

        if (!is_array($input)) {
            $input = [];
        }

        $sanitized = $old;

        if ($current_tab !== '') {
            if (!isset($sanitized[$current_tab]) || !is_array($sanitized[$current_tab])) {
                $sanitized[$current_tab] = [];
            }

            foreach ($features as $key => $feature) {
                if (($feature['group'] ?? '') !== $current_tab) {
                    continue;
                }

                unset($sanitized[$current_tab][$key]);
            }
        }

        foreach ($input as $group => $items) {
            $group = sanitize_key($group);

            if (!is_array($items)) {
                continue;
            }

            if (!isset($sanitized[$group]) || !is_array($sanitized[$group])) {
                $sanitized[$group] = [];
            }

            foreach ($items as $key => $value) {
                $key = sanitize_key($key);

                if (!empty($features[$key]['pro'])) {
                    continue;
                }

                $sanitized[$group][$key] = empty($value) ? 0 : 1;
            }
        }

        return $sanitized;
    }

    public function enqueue_assets($hook) {

        if ($hook !== 'toplevel_page_wpsct') return;

        $css_path = WPSCT_PATH . 'assets/css/admin.css';
        $js_path = WPSCT_PATH . 'assets/js/admin.js';

        wp_enqueue_style(
            'wpsct-admin',
            WPSCT_URL . 'assets/css/admin.css',
            [],
            file_exists($css_path) ? filemtime($css_path) : '0.1.0'
        );

        wp_enqueue_script(
            'wpsct-admin',
            WPSCT_URL . 'assets/js/admin.js',
            [],
            file_exists($js_path) ? filemtime($js_path) : '0.1.0',
            true
        );
    }
}
