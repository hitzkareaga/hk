<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Emojis {

    public function __construct() {
        add_action('init', [$this, 'run']);
    }

    public function run() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }
}