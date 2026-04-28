<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Interval {

    public function __construct() {

        add_filter('autosave_interval', [$this, 'set_interval']);
    }

    public function set_interval($seconds) {

        $custom = 120;

        return $custom;
    }
}
