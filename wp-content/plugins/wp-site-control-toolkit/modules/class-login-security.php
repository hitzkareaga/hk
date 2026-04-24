<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Login_Security {

    public function __construct() {
        add_filter('login_errors', [$this, 'hide_errors']);
    }

    public function hide_errors() {
        return 'Login error';
    }
}