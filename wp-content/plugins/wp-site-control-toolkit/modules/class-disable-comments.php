<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Comments {

    public function __construct() {

        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        add_filter('comments_array', '__return_empty_array', 10, 2);

        add_action('admin_menu', [$this, 'remove_menu']);
    }

    public function remove_menu() {
        remove_menu_page('edit-comments.php');
    }
}