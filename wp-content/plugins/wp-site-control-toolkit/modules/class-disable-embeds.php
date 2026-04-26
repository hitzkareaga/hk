<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Embeds {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_action('init', [$this, 'disable']);
    }

    public function disable() {

        if (empty($this->settings['disable-embeds'])) {
            return;
        }

        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }
}