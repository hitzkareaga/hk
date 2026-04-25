<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Gutenberg {

    public function __construct() {

        add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
        add_filter('gutenberg_can_edit_post_type', [$this, 'disable_gutenberg'], 10, 2);

        add_action('admin_init', [$this, 'force_classic_editor']);
    }

    public function disable_gutenberg($use_block_editor, $post_type) {
        return false;
    }

    public function force_classic_editor() {
        // fallback compatibility for plugins expecting classic editor behavior
        remove_post_type_support('post', 'editor');
        add_post_type_support('post', 'editor');
    }
}