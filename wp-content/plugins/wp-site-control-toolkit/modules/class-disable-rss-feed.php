<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Rss_Feed {

    public function __construct() {

        add_action('do_feed', [$this, 'disable_feed'], 1);
        add_action('do_feed_rdf', [$this, 'disable_feed'], 1);
        add_action('do_feed_rss', [$this, 'disable_feed'], 1);
        add_action('do_feed_rss2', [$this, 'disable_feed'], 1);
        add_action('do_feed_atom', [$this, 'disable_feed'], 1);
    }

    public function disable_feed() {

        wp_die(
            __('RSS feeds are disabled on this site.', 'wp-site-control-toolkit')
        );
    }
}