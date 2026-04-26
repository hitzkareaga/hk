<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Comments {

    private $settings;

    public function __construct() {

        $this->settings = get_option('wpsct_settings', []);

        add_action('init', [$this, 'disable_comments_support']);

        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        add_action('admin_menu', [$this, 'remove_comments_menu']);
        add_action('admin_init', [$this, 'remove_comments_support']);
    }

    public function disable_comments_support() {

        if (empty($this->settings['disable-comments'])) {
            return;
        }

        $post_types = get_post_types();

        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function remove_comments_menu() {

        if (empty($this->settings['disable-comments'])) {
            return;
        }

        remove_menu_page('edit-comments.php');
    }

    public function remove_comments_support() {

        if (empty($this->settings['disable-comments'])) {
            return;
        }

        global $wp_post_types;

        foreach ($wp_post_types as $post_type => $data) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }
}