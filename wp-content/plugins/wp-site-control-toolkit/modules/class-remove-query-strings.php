<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Remove_Query_Strings {

    public function __construct() {

        add_filter('style_loader_src', [$this, 'remove_version'], 9999, 1);
        add_filter('script_loader_src', [$this, 'remove_version'], 9999, 1);
    }

    public function remove_version($src) {

        if (strpos($src, 'ver=') === false) {
            return $src;
        }

        return remove_query_arg('ver', $src);
    }
}