<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_File_Editor {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        $this->maybe_disable();
    }

    private function maybe_disable() {

        if (empty($this->settings['disable-file-editor'])) {
            return;
        }

        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }
}