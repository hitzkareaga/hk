<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Embeds {

    public function __construct() {

        add_action('init', [$this, 'disable']);
    }

    public function disable() {

        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }
}
