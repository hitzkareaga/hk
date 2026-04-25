<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Cleanup {

    public function __construct() {
        add_action('init', [$this, 'cleanup']);
    }

    public function cleanup() {
        // Remove WP version from <head>
        remove_action('wp_head', 'wp_generator');

        // Remove legacy links
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove WP version from feeds and other outputs
        add_filter('the_generator', '__return_empty_string');
    }
}