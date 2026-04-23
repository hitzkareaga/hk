<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Cleanup_Head {

    public function __construct() {
        add_action('init', [$this, 'run']);
    }

    public function run() {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
}