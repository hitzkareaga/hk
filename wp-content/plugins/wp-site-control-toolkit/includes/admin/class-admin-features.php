<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Features {

    public function get_features() {

        return [

            /* =========================
               CLEANUP
            ========================= */

            'disable-emojis' => [
                'title' => __('Disable Emojis', 'wp-site-control-toolkit'),
                'desc' => __('Stops loading emoji-related scripts that WordPress adds by default.', 'wp-site-control-toolkit'),
                'changes' => __('Prevents extra emoji files from loading on your site.', 'wp-site-control-toolkit'),
                'why' => __('Makes your pages lighter and can slightly improve loading speed.', 'wp-site-control-toolkit'),
                'impact' => 'LOW',
                'group' => 'cleanup',
                'category' => 'cleanup'
            ],

            'disable-embeds' => [
                'title' => __('Disable Embeds', 'wp-site-control-toolkit'),
                'desc' => __('Prevents WordPress from automatically embedding external content.', 'wp-site-control-toolkit'),
                'changes' => __('Stops loading scripts required for embeds.', 'wp-site-control-toolkit'),
                'why' => __('Reduces external requests and improves performance.', 'wp-site-control-toolkit'),
                'impact' => 'LOW',
                'group' => 'cleanup',
                'category' => 'cleanup'
            ],

            'cleanup-head' => [
                'title' => __('Head Cleanup', 'wp-site-control-toolkit'),
                'desc' => __('Removes unnecessary tags from the <head> section.', 'wp-site-control-toolkit'),
                'changes' => __('Removes version, shortlinks and legacy tags.', 'wp-site-control-toolkit'),
                'why' => __('Cleaner HTML and less information exposure.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'cleanup',
                'category' => 'cleanup'
            ],

            'disable-comments' => [
                'title' => __('Disable Comments', 'wp-site-control-toolkit'),
                'desc' => __('Turns off the WordPress comments system across your site.', 'wp-site-control-toolkit'),
                'changes' => __('Removes comment forms and disables new comments.', 'wp-site-control-toolkit'),
                'why' => __('Useful for sites that don’t need user comments, helps reduce spam, and minimizes potential abuse through comment forms.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'cleanup',
                'category' => 'cleanup'
            ],

            /* =========================
               PERFORMANCE
            ========================= */

            'remove-query-strings' => [
                'title' => __('Remove Query Strings', 'wp-site-control-toolkit'),
                'desc' => __('Removes version query strings from CSS and JavaScript files.', 'wp-site-control-toolkit'),
                'changes' => __('Strips ?ver= parameters from static assets.', 'wp-site-control-toolkit'),
                'why' => __('Helps improve caching behavior and keeps URLs cleaner.', 'wp-site-control-toolkit'),
                'impact' => 'LOW',
                'group' => 'performance',
                'category' => 'performance'
            ],

            'disable-gutenberg' => [
                'title' => __('Disable Gutenberg and show classic editor', 'wp-site-control-toolkit'),
                'desc' => __('Replaces the block editor with the classic WordPress editor.', 'wp-site-control-toolkit'),
                'changes' => __('Disables Gutenberg and restores the classic editing interface.', 'wp-site-control-toolkit'),
                'why' => __('Useful if you prefer the old editor or rely on plugins not compatible with Gutenberg.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'performance',
                'category' => 'performance'
            ],

            'heartbeat-control' => [
                'title' => __('Heartbeat Control', 'wp-site-control-toolkit'),
                'desc' => __('Limits background admin requests.', 'wp-site-control-toolkit'),
                'changes' => __('Reduces AJAX calls in admin.', 'wp-site-control-toolkit'),
                'why' => __('Improves admin performance.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'performance',
                'category' => 'performance'
            ],

            'revisions-control' => [
                'title' => __('Revisions Control', 'wp-site-control-toolkit'),
                'desc' => __('Controls how many post revisions WordPress stores.', 'wp-site-control-toolkit'),
                'changes' => __('Limits the number of saved versions for posts and pages.', 'wp-site-control-toolkit'),
                'why' => __('Helps reduce database size and keeps content history manageable.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'performance',
                'category' => 'performance'
            ],

            'autosave-tuning' => [
                'title' => __('Autosave Tuning', 'wp-site-control-toolkit'),
                'desc' => __('Adjusts how WordPress handles automatic saving while editing content.', 'wp-site-control-toolkit'),
                'changes' => __('Controls how aggressively WordPress saves drafts in the background.', 'wp-site-control-toolkit'),
                'why' => __('Reduces unnecessary autosave activity and can improve editor performance.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'performance',
                'category' => 'performance'
            ],

            'autosave-interval' => [
                'title' => __('Autosave Interval', 'wp-site-control-toolkit'),
                'desc' => __('Controls how frequently WordPress saves your content while editing.', 'wp-site-control-toolkit'),
                'changes' => __('Increases the time between automatic background saves while writing posts or pages.', 'wp-site-control-toolkit'),
                'why' => __('Reduces autosave frequency while maintaining safe recovery of content.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'performance',
                'category' => 'performance'
            ],

            /* =========================
               SECURITY
            ========================= */

            'login-security' => [
                'title' => __('Hide Login Errors', 'wp-site-control-toolkit'),
                'desc' => __('Hides detailed login errors.', 'wp-site-control-toolkit'),
                'changes' => __('Removes login hints.', 'wp-site-control-toolkit'),
                'why' => __('Prevents user enumeration.', 'wp-site-control-toolkit'),
                'impact' => 'LOW',
                'group' => 'security',
                'category' => 'security'
            ],

            'disable-file-editor' => [
                'title' => __('Disable File Editor', 'wp-site-control-toolkit'),
                'desc' => __('Disables theme and plugin editor.', 'wp-site-control-toolkit'),
                'changes' => __('Removes editor from admin.', 'wp-site-control-toolkit'),
                'why' => __('Prevents accidental or malicious edits.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'security',
                'category' => 'security'
            ],

            'disable-xmlrpc' => [
                'title' => __('Disable XML-RPC', 'wp-site-control-toolkit'),
                'desc' => __('Disables legacy remote access.', 'wp-site-control-toolkit'),
                'changes' => __('Blocks xmlrpc.php.', 'wp-site-control-toolkit'),
                'why' => __('Reduces attack surface.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'security',
                'category' => 'security'
            ],

            /* =========================
               ACCESS / API
            ========================= */

            'disable-rss-feed' => [
                'title' => __('Disable RSS Feed', 'wp-site-control-toolkit'),
                'desc' => __('Disables all RSS feeds on your WordPress site.', 'wp-site-control-toolkit'),
                'changes' => __('Removes access to content feeds for posts, categories, and comments.', 'wp-site-control-toolkit'),
                'why' => __('Reduces unnecessary endpoints for sites that do not use RSS.', 'wp-site-control-toolkit'),
                'impact' => 'LOW',
                'group' => 'access-api',
                'category' => 'access-api'
            ],

            'disable-rest-api' => [
                'title' => __('Disable REST API (visitors only)', 'wp-site-control-toolkit'),
                'desc' => __('Limits access to the WordPress REST API to logged-in users only.', 'wp-site-control-toolkit'),
                'changes' => __('Blocks public access to REST API endpoints unless authenticated.', 'wp-site-control-toolkit'),
                'why' => __('Reduces public exposure of structured site data.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'access-api',
                'category' => 'access-api'
            ],

            /* =========================
               MEDIA
            ========================= */

            'media-sizes' => [
                'title' => __('Control Image Sizes', 'wp-site-control-toolkit'),
                'desc' => __('Stops generating extra image sizes.', 'wp-site-control-toolkit'),
                'changes' => __('Prevents additional image creation.', 'wp-site-control-toolkit'),
                'why' => __('Saves storage space.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'media',
                'category' => 'media'
            ],

            'disable-unused-sizes' => [
                'title' => __('Disable Unused Sizes', 'wp-site-control-toolkit'),
                'desc' => __('Stops WordPress from generating image sizes that are not used.', 'wp-site-control-toolkit'),
                'changes' => __('Prevents creation of extra image variants defined by themes or plugins.', 'wp-site-control-toolkit'),
                'why' => __('Reduces storage usage and keeps your media library cleaner.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'media',
                'category' => 'media'
            ],

            'svg-sanitization' => [
                'title' => __('Secure SVG Uploads', 'wp-site-control-toolkit'),
                'desc' => __('Allows SVG uploads with automatic sanitization.', 'wp-site-control-toolkit'),
                'changes' => __('Cleans SVG files by removing unsafe scripts and attributes.', 'wp-site-control-toolkit'),
                'why' => __('Prevents XSS while allowing vector graphics.', 'wp-site-control-toolkit'),
                'impact' => 'HIGH',
                'group' => 'media',
                'category' => 'media'
            ],

            'webp-conversion' => [
                'title' => __('WebP Conversion', 'wp-site-control-toolkit'),
                'desc' => __('Converts uploaded images to WebP format.', 'wp-site-control-toolkit'),
                'changes' => __('Automatically converts images into WebP format.', 'wp-site-control-toolkit'),
                'why' => __('Reduces image size and improves performance.', 'wp-site-control-toolkit'),
                'impact' => 'MEDIUM',
                'group' => 'media',
                'category' => 'media'
            ],
        ];
    }
}