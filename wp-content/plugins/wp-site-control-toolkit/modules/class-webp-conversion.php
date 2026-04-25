<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Webp_Conversion {

    public function __construct() {

        /**
         * Hook into image upload process
         * Note: this is a simplified MVP approach
         */
        add_filter('wp_handle_upload', [$this, 'convert_to_webp']);
    }

    public function convert_to_webp($upload) {

        if (empty($upload['file'])) {
            return $upload;
        }

        $file = $upload['file'];
        $type = wp_check_filetype($file);

        // Only process images
        if (!in_array($type['type'], ['image/jpeg', 'image/png', 'image/jpg'])) {
            return $upload;
        }

        /**
         * Convert to WebP (requires GD or Imagick with WebP support)
         * This is a simplified implementation.
         */

        $image_editor = wp_get_image_editor($file);

        if (is_wp_error($image_editor)) {
            return $upload;
        }

        $new_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

        $saved = $image_editor->save($new_file, 'image/webp');

        if (!is_wp_error($saved)) {
            // Optionally replace original or keep both
            $upload['webp'] = $saved['path'];
        }

        return $upload;
    }
}