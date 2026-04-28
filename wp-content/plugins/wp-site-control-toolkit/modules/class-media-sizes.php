<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Media_Sizes {

    public function __construct() {

        add_filter('intermediate_image_sizes_advanced', [$this, 'filter_sizes']);
        add_filter('big_image_size_threshold', '__return_false');
    }

    public function filter_sizes($sizes) {

        $blocked = [
            'medium_large',
            '1536x1536',
            '2048x2048',
        ];

        foreach ($blocked as $size) {
            if (isset($sizes[$size])) {
                unset($sizes[$size]);
            }
        }

        return $sizes;
    }
}
