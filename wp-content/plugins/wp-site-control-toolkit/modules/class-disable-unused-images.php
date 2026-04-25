<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Unused_Sizes {

    public function __construct() {

        add_filter('intermediate_image_sizes_advanced', [$this, 'remove_unused_sizes']);
    }

    public function remove_unused_sizes($sizes) {

        /**
         * WordPress passes all registered image sizes here.
         * We can remove unwanted ones defined by themes/plugins.
         *
         * In MVP version we remove common defaults safely only if desired.
         */

        $blocked_sizes = apply_filters('wpsct_blocked_image_sizes', []);

        foreach ($blocked_sizes as $size) {
            if (isset($sizes[$size])) {
                unset($sizes[$size]);
            }
        }

        return $sizes;
    }
}