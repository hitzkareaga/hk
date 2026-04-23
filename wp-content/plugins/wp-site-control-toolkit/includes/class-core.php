<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Core {

    public function __construct() {
        $this->load_modules();
    }

    private function load_modules() {

        $settings = get_option('wpsct_settings', []);

        $modules = [
            'disable-emojis',
            'disable-embeds',
            'heartbeat-control',
            'login-security',
            'disable-file-editor',
            'cleanup-head',
            'version-hiding',
            'disable-xmlrpc',
            'media-sizes'
        ];

        foreach ($modules as $module) {

            if (empty($settings[$module])) continue;

            $file = WPSCT_PATH . 'modules/class-' . $module . '.php';

            if (!file_exists($file)) continue;

            require_once $file;

            $class = 'WPSCT_' . str_replace('-', '_', ucwords($module, '-'));

            if (class_exists($class)) {
                new $class();
            }
        }
    }
}