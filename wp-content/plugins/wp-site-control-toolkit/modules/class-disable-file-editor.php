<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Disable_File_Editor {

    public function __construct() {

        $this->maybe_disable();
    }

    private function maybe_disable() {

        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }
}
