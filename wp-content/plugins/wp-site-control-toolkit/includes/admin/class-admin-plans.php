<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Plans {

    public function get_plans(): array {

        return [
            'solo' => [
                'name' => __('SOLO', 'wp-site-control-toolkit'),
                'price' => 39,
                'sites' => __('1 website', 'wp-site-control-toolkit'),
                'audience' => __('Individual freelancer', 'wp-site-control-toolkit'),
            ],

            'studio' => [
                'name' => __('Studio', 'wp-site-control-toolkit'),
                'price' => 99,
                'sites' => __('Up to 5 websites', 'wp-site-control-toolkit'),
                'audience' => __('Small studio', 'wp-site-control-toolkit'),
            ],

            'small-agency' => [
                'name' => __('Small agency', 'wp-site-control-toolkit'),
                'price' => 199,
                'sites' => __('Up to 15 websites', 'wp-site-control-toolkit'),
                'audience' => __('Micro-agency', 'wp-site-control-toolkit'),
            ],

            'agency' => [
                'name' => __('Agency', 'wp-site-control-toolkit'),
                'price' => 299,
                'sites' => __('Up to 25 websites', 'wp-site-control-toolkit'),
                'audience' => __('Established agency', 'wp-site-control-toolkit'),
            ],
        ];
    }
}
