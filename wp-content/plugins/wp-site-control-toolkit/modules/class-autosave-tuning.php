<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Tuning {

    public function __construct() {

        // Reduce autosave interval
        add_filter('autosave_interval', [$this, 'set_autosave_interval']);
    }

    public function set_autosave_interval($seconds) {

        // Default WP is 60 seconds
        // We increase interval to reduce background noise

        return 120;
    }
}