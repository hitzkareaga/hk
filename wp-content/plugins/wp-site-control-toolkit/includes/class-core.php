<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Core {

    public function __construct() {
        $this->load_modules();
    }

    private function load_modules() {

        $settings = get_option('wpsct_settings', []);

        if (!function_exists('wpsct_get_content')) {
            return;
        }

        $content  = wpsct_get_content();
        $features = $content['features'] ?? [];

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
}