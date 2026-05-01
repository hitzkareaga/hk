<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Dns_Prefetch_Control {

    public function __construct() {
        add_filter('wp_resource_hints', [$this, 'remove_dns_prefetch_hints'], 10, 2);
    }

    public function remove_dns_prefetch_hints($urls, $relation_type) {

        if ($relation_type !== 'dns-prefetch') {
            return $urls;
        }

        return [];
    }
}
