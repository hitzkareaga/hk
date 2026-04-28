<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Core {

    public function __construct() {
        $this->load_modules();
    }

    private function load_modules() {

        require_once WPSCT_PATH . 'includes/admin/class-admin-features.php';

        if (!class_exists('WPSCT_Admin_Features')) {
            return;
        }

        $features = (new WPSCT_Admin_Features())->get_features();

        $settings = get_option('wpsct_settings', []);
        $settings = $this->flatten_settings($settings);

        if (!is_array($settings)) {
            $settings = [];
        }

        foreach ($features as $key => $feature) {

            if (empty($settings[$key])) {
                continue;
            }

            if (!empty($feature['pro'])) {
                continue;
            }

            $file = WPSCT_PATH . 'modules/class-' . $key . '.php';

            if (!file_exists($file)) {
                continue;
            }

            require_once $file;

            $class = $this->get_class_name($key);

            if (class_exists($class)) {
                new $class();
            }
        }
    }

    private function get_class_name($key) {

        $parts = explode('-', $key);
        $parts = array_map('ucfirst', $parts);

        return 'WPSCT_' . implode('_', $parts);
    }

    private function flatten_settings($settings) {

        if (!is_array($settings)) {
            return [];
        }

        $flat = [];

        foreach ($settings as $key => $value) {

            if (is_array($value)) {
                foreach ($value as $child_key => $child_value) {
                    $flat[$child_key] = !empty($child_value);
                }

                continue;
            }

            $flat[$key] = !empty($value);
        }

        return $flat;
    }
}
