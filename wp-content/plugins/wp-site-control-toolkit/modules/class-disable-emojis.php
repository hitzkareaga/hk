<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Emojis {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_action('init', [$this, 'run']);
    }

    public function run() {

        if (empty($this->settings['disable-emojis'])) {
            return;
        }

        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }
}