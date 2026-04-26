<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Gutenberg {

    public function __construct() {

        add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
        add_filter('gutenberg_can_edit_post_type', [$this, 'disable_gutenberg'], 10, 2);
    }

    public function disable_gutenberg($use_block_editor, $post_type) {

        // Force Classic Editor globally for all post types
        return false;
    }
}