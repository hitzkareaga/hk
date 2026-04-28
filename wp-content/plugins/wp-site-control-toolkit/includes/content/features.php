<?php

if (!defined('ABSPATH')) exit;

return [

    'disable-emojis' => [
        'label' => __('Disable Emojis', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress loads emoji scripts by default even if your site does not use them.

What this changes:
- Removes emoji scripts from frontend and admin
- Reduces unnecessary HTTP requests
- Cleans HTML output

Why this is useful:
If you are not using emojis, this removes unnecessary performance overhead.",
        'wp-site-control-toolkit'),
        'group' => 'cleanup',
        'impact' => 'low',
    ],

    'disable-embeds' => [
        'label' => __('Disable Embeds', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress loads oEmbed functionality to support embedded content.

What this changes:
- Disables oEmbed scripts
- Prevents external embed requests

Why this is useful:
Improves performance if you do not embed external content.",
        'wp-site-control-toolkit'),
        'group' => 'cleanup',
        'impact' => 'low',
    ],

    'cleanup-head' => [
        'label' => __('Head Cleanup', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress adds multiple unnecessary tags in the <head>.

What this changes:
- Removes version tags
- Removes RSD, shortlinks and other metadata

Why this is useful:
Produces cleaner HTML and reduces unnecessary output.",
        'wp-site-control-toolkit'),
        'group' => 'cleanup',
        'impact' => 'medium',
    ],


    'disable-comments' => [
        'label' => __('Disable Comments', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress enables comments by default.

What this changes:
- Disables comments on posts, pages and media
- Removes comment forms
- Hides comment admin menu

Why this is useful:
Removes spam risk and unnecessary database queries if comments are not needed.",
        'wp-site-control-toolkit'),
        'group' => 'cleanup',
        'impact' => 'medium',
    ],

    'heartbeat-control' => [
        'label' => __('Heartbeat Control', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress Heartbeat runs background requests in admin.

What this changes:
- Reduces heartbeat frequency

Why this is useful:
Improves backend performance and reduces server load.",
        'wp-site-control-toolkit'),
        'group' => 'performance',
        'impact' => 'medium',
    ],

    'disable-gutenberg' => [
        'label' => __('Disable Gutenberg and enable Classic Editor', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress uses Gutenberg block editor by default.

What this changes:
- Disables block editor
- Restores classic editor experience

Why this is useful:
Simplifies editing experience and improves compatibility with page builders.",
        'wp-site-control-toolkit'),
        'group' => 'cleanup',
        'impact' => 'high',
    ],

    'login-security' => [
        'label' => __('Hide Login Errors', 'wp-site-control-toolkit'),
        'desc'  => __(
"Login errors can reveal valid usernames.

What this changes:
- Shows generic login error messages

Why this is useful:
Prevents user enumeration attacks.",
        'wp-site-control-toolkit'),
        'group' => 'security',
        'impact' => 'low',
    ],

    'disable-file-editor' => [
        'label' => __('Disable File Editor', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress allows editing code from admin panel.

What this changes:
- Disables theme and plugin editor

Why this is useful:
Prevents accidental or malicious code changes.",
        'wp-site-control-toolkit'),
        'group' => 'security',
        'impact' => 'medium',
    ],

    'disable-xmlrpc' => [
        'label' => __('Disable XML-RPC', 'wp-site-control-toolkit'),
        'desc'  => __(
"XML-RPC is often abused for attacks.

What this changes:
- Disables XML-RPC endpoint

Why this is useful:
Reduces attack surface.",
        'wp-site-control-toolkit'),
        'group' => 'security',
        'impact' => 'medium',
    ],

    'media-sizes' => [
        'label' => __('Control Image Sizes', 'wp-site-control-toolkit'),
        'desc'  => __(
"WordPress generates multiple image sizes automatically.

What this changes:
- Stops unnecessary image size generation

Why this is useful:
Saves storage space and reduces media clutter.",
        'wp-site-control-toolkit'),
        'group' => 'media',
        'impact' => 'medium',
    ],
];
