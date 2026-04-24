<?php

if (!defined('ABSPATH')) exit;

function wpsct_get_content() {

    return [
        'features' => require WPSCT_PATH . 'includes/content/features.php',
        'presets'  => require WPSCT_PATH . 'includes/content/presets.php',
    ];
}