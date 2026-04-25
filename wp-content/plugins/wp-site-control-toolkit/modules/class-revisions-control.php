<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Revisions_Control {

    public function __construct() {
        add_filter('wp_revisions_to_keep', [$this, 'limit_revisions'], 10, 2);
    }

    public function limit_revisions($num, $post) {

        // You can later make this configurable from settings
        return 5;
    }
}