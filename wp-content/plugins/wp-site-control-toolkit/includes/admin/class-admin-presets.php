<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Presets {

    public function __construct() {

        // Detect manual changes → switch to custom
        add_filter(
            'pre_update_option_wpsct_settings',
            [$this, 'maybe_switch_to_custom'],
            10,
            2
        );
    }

    public function get_presets() {

        return [

            'performance' => [
                'label' => __('Performance', 'wp-site-control-toolkit'),
                'desc'  => __('Optimized for speed and reduced backend activity.', 'wp-site-control-toolkit'),
                'settings' => [
                    'disable-emojis' => 1,
                    'disable-embeds' => 1,
                    'heartbeat-control' => 1,
                    'media-sizes' => 1,
                ]
            ],

            'minimal' => [
                'label' => __('Minimal', 'wp-site-control-toolkit'),
                'desc'  => __('Keeps only essential WordPress features.', 'wp-site-control-toolkit'),
                'settings' => [
                    'disable-emojis' => 1,
                    'disable-embeds' => 1,
                    'cleanup-head' => 1,
                    'version-hiding' => 1,
                ]
            ],

            'secure' => [
                'label' => __('Secure', 'wp-site-control-toolkit'),
                'desc'  => __('Basic security hardening.', 'wp-site-control-toolkit'),
                'settings' => [
                    'login-security' => 1,
                    'disable-file-editor' => 1,
                    'disable-xmlrpc' => 1,
                ]
            ],

            'custom' => [
                'label' => __('Custom', 'wp-site-control-toolkit'),
                'desc'  => __('Manual configuration.', 'wp-site-control-toolkit'),
                'settings' => []
            ]
        ];
    }

    public function get_active_preset() {
        return get_option('wpsct_active_preset', 'custom');
    }

    public function handle_preset_save() {

        if (!isset($_POST['wpsct_set_preset'])) return;
        if (!current_user_can('manage_options')) return;

        update_option(
            'wpsct_active_preset',
            sanitize_text_field($_POST['wpsct_set_preset'])
        );
    }

    /* =========================
       AUTO SWITCH TO CUSTOM
    ========================= */

    public function maybe_switch_to_custom($new, $old) {

        $active = $this->get_active_preset();

        // Already custom → do nothing
        if ($active === 'custom') {
            return $new;
        }

        $presets = $this->get_presets();
        $preset_settings = $presets[$active]['settings'] ?? [];

        // Normalize both arrays
        $normalized_new = $this->normalize_settings($new);
        $normalized_preset = $this->normalize_settings($preset_settings);

        // If user modified anything → switch to custom
        if ($normalized_new !== $normalized_preset) {
            update_option('wpsct_active_preset', 'custom');
        }

        return $new;
    }

    private function normalize_settings($settings) {

        if (!is_array($settings)) return [];

        // Remove empty values (unchecked toggles)
        $settings = array_filter($settings);

        // Ensure consistent order for comparison
        ksort($settings);

        return $settings;
    }
}