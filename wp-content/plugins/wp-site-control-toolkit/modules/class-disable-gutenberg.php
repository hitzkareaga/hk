<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Gutenberg {

    public function __construct() {

        add_filter('use_block_editor_for_post_type', '__return_false', 10);

        add_action('wp_enqueue_scripts', [$this, 'remove_block_assets'], 100);
    }

    public function remove_block_assets() {

        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
    }
}