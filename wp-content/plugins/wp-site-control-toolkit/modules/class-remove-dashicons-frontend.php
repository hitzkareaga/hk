<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Remove_Dashicons_Frontend {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'remove_dashicons'], 100);
    }

    public function remove_dashicons() {

        if (is_user_logged_in()) {
            return;
        }

        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }
}
