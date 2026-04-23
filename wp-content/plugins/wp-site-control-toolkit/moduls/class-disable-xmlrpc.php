<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Xmlrpc {

    public function __construct() {
        add_filter('xmlrpc_enabled', '__return_false');
    }
}