<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Comments {

    public function __construct() {

        // Disable front-end comments
        add_action('init', [$this, 'disable_comments_support']);

        // Close comments on existing posts
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Hide comments UI in admin
        add_action('admin_menu', [$this, 'remove_comments_menu']);
        add_action('admin_init', [$this, 'remove_comments_support']);
    }

    public function disable_comments_support() {

        // Remove support from post types
        $post_types = get_post_types();

        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function remove_comments_menu() {
        remove_menu_page('edit-comments.php');
    }

    public function remove_comments_support() {
        global $wp_post_types;

        foreach ($wp_post_types as $post_type => $data) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }
}