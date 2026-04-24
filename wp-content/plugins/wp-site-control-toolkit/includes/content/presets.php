<?php

if (!defined('ABSPATH')) exit;

return [

    'performance' => [
        'label' => __('Performance Setup', 'wp-site-control-toolkit'),
        'desc'  => __('Optimized for speed and reduced backend activity.', 'wp-site-control-toolkit'),
        'meta'  => [
            'changes' => __('Reduces background processes and frontend scripts.', 'wp-site-control-toolkit'),
            'recommended' => __('Performance-focused websites.', 'wp-site-control-toolkit'),
        ],
        'settings' => [
            'disable-emojis' => 1,
            'disable-embeds' => 1,
            'heartbeat-control' => 1,
            'media-sizes' => 1,
        ]
    ],

    'minimal' => [
        'label' => __('Minimal Setup', 'wp-site-control-toolkit'),
        'desc'  => __('Keeps only essential WordPress features.', 'wp-site-control-toolkit'),
        'meta'  => [
            'changes' => __('Removes frontend and backend bloat.', 'wp-site-control-toolkit'),
            'recommended' => __('Simple websites.', 'wp-site-control-toolkit'),
        ],
        'settings' => [
            'disable-emojis' => 1,
            'disable-embeds' => 1,
        ]
    ],

    'secure' => [
        'label' => __('Secure Setup', 'wp-site-control-toolkit'),
        'desc'  => __('Basic security hardening for WordPress sites.', 'wp-site-control-toolkit'),
        'meta'  => [
            'changes' => __('Reduces attack surface.', 'wp-site-control-toolkit'),
            'recommended' => __('Production websites.', 'wp-site-control-toolkit'),
        ],
        'settings' => [
            'login-security' => 1,
            'disable-file-editor' => 1,
            'disable-xmlrpc' => 1,
        ]
    ],

    'custom' => [
        'label' => __('Custom Setup', 'wp-site-control-toolkit'),
        'desc'  => __('Manual configuration.', 'wp-site-control-toolkit'),
        'meta'  => [
            'changes' => __('No automatic changes applied.', 'wp-site-control-toolkit'),
            'recommended' => __('Advanced users.', 'wp-site-control-toolkit'),
        ],
        'settings' => []
    ],
];