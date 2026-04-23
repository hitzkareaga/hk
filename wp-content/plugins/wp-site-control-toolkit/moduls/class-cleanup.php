<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Cleanup {

    public function __construct() {
        add_action('init', [$this, 'cleanup']);
    }

    public function cleanup() {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
}