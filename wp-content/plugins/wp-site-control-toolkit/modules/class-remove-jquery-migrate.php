<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Remove_Jquery_Migrate {

    public function __construct() {
        add_action('wp_default_scripts', [$this, 'remove_jquery_migrate']);
    }

    public function remove_jquery_migrate($scripts) {

        if (is_admin() || empty($scripts->registered['jquery'])) {
            return;
        }

        $dependencies = $scripts->registered['jquery']->deps;

        if (!is_array($dependencies)) {
            return;
        }

        $scripts->registered['jquery']->deps = array_values(
            array_diff($dependencies, ['jquery-migrate'])
        );
    }
}
