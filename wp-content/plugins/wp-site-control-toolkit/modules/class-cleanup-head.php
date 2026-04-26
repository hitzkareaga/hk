<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Cleanup_Head {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_action('init', [$this, 'run']);
    }

    public function run() {

        if (empty($this->settings['cleanup-head'])) {
            return;
        }

        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
}