<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Interval {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_filter('autosave_interval', [$this, 'set_interval']);
    }

    public function set_interval($seconds) {

        // Feature disabled → no override
        if (empty($this->settings['autosave-interval'])) {
            return $seconds;
        }

        /**
         * Default override (can later be PRO configurable)
         * Example: 120 seconds instead of 60
         */

        $custom = 120;

        return $custom;
    }
}