<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Tuning {

    public function __construct() {

        add_filter('autosave_interval', [$this, 'set_autosave_interval']);
    }

    public function set_autosave_interval($seconds) {
        return 120;
    }
}
