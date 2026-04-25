<?php

if (!defined('ABSPATH')) exit;

class WPSCT_SVG_Sanitization {

    public function __construct() {

        add_filter('upload_mimes', [$this, 'allow_svg']);
        add_filter('wp_handle_upload_prefilter', [$this, 'sanitize_svg']);
    }

    public function allow_svg($mimes) {

        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }

    public function sanitize_svg($file) {

        if (empty($file['type']) || $file['type'] !== 'image/svg+xml') {
            return $file;
        }

        if (!file_exists($file['tmp_name'])) {
            return $file;
        }

        $content = file_get_contents($file['tmp_name']);

        if (!$content) {
            return $file;
        }

        /**
         * BASIC SAFE LAYER (MVP)
         * For production you should use a real sanitizer library
         */
        $content = $this->basic_sanitize($content);

        file_put_contents($file['tmp_name'], $content);

        return $file;
    }

    private function basic_sanitize($svg) {

        // Remove script tags
        $svg = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $svg);

        // Remove on* event handlers (onload, onclick, etc.)
        $svg = preg_replace('#on[a-z]+\s*=\s*"[^"]*"#i', '', $svg);
        $svg = preg_replace("#on[a-z]+\s*=\s*'[^']*'#i", '', $svg);

        // Remove javascript: urls
        $svg = preg_replace('#javascript:#i', '', $svg);

        return $svg;
    }
}