<?php

if (!defined('ABSPATH')) exit;

return [

    'performance' => [
        'label' => __('Performance Setup', 'wpsct'),

        'desc' => __('Optimized for speed and reduced backend activity.', 'wpsct'),

        'meta' => [
            'changes' => __('Reduces background processes and frontend scripts.', 'wpsct'),
            'recommended' => __('Performance-focused websites.', 'wpsct'),
        ],

        'settings' => [
            'disable-emojis' => 1,
            'disable-embeds' => 1,
        ]
    ],

    'minimal' => [
        'label' => __('Minimal Setup', 'wpsct'),

        'desc' => __('Keeps only essential WordPress features.', 'wpsct'),

        'meta' => [
            'changes' => __('Removes unnecessary WordPress features.', 'wpsct'),
            'recommended' => __('Simple websites.', 'wpsct'),
        ],

        'settings' => [
            'disable-emojis' => 1,
        ]
    ],

    'custom' => [
        'label' => __('Custom Setup', 'wpsct'),

        'desc' => __('Manual configuration.', 'wpsct'),

        'meta' => [
            'changes' => __('No automatic changes.', 'wpsct'),
            'recommended' => __('Advanced users.', 'wpsct'),
        ],

        'settings' => []
    ]

];