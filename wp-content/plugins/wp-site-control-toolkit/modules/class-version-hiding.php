<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Version_Hiding {

    public function __construct() {
        add_filter('the_generator', '__return_empty_string');
    }
}