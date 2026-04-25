<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_Rest_Api {

    public function __construct() {
        add_filter('rest_authentication_errors', [$this, 'restrict_rest_api']);
    }

    public function restrict_rest_api($result) {

        if (!empty($result)) {
            return $result;
        }

        if (!is_user_logged_in()) {
            return new WP_Error(
                'rest_disabled',
                __('REST API access is restricted.', 'wp-site-control-toolkit'),
                ['status' => 401]
            );
        }

        return $result;
    }
}