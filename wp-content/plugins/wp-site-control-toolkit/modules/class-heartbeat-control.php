<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Heartbeat_Control {

    public function __construct() {
        add_filter('heartbeat_settings', [$this, 'control']);
    }

    public function control($settings) {
        $settings['interval'] = 60;
        return $settings;
    }
}