<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Autosave_Interval {

    public function __construct() {

        add_filter('autosave_interval', [$this, 'set_interval']);
    }

    public function set_interval($seconds) {

        /**
         * Default WordPress: 60 seconds
         * Here we give a slightly more balanced value.
         * You can later make this configurable.
         */

        return 90;
    }
}