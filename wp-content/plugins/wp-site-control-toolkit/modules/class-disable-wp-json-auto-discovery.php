<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Wp_Json_Auto_Discovery {

    public function __construct() {
        add_action('init', [$this, 'disable_auto_discovery']);
    }

    public function disable_auto_discovery() {

        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('template_redirect', 'rest_output_link_header', 11);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
    }
}
