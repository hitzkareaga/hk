<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Tuning {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_filter('autosave_interval', [$this, 'set_autosave_interval']);
    }

    public function set_autosave_interval($seconds) {

        /**
         * Feature toggle check
         * If disabled → keep WordPress default
         */
        if (empty($this->settings['autosave-tuning'])) {
            return $seconds;
        }

        /**
         * Tuned value:
         * increases autosave interval to reduce background activity
         */
        return 120;
    }
}