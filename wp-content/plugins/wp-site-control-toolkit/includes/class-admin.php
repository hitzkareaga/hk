<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'handle_preset_save']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets($hook) {

        if ($hook !== 'toplevel_page_wpsct') return;

        wp_enqueue_style(
            'wpsct-admin',
            WPSCT_URL . 'assets/css/admin.css',
            [],
            '0.1.0'
        );

        wp_enqueue_script(
            'wpsct-admin',
            WPSCT_URL . 'assets/js/admin.js',
            [],
            '0.1.0',
            true
        );
    }

    public function menu() {

        add_menu_page(
            'Site Control Toolkit',
            'Site Control',
            'manage_options',
            'wpsct',
            [$this, 'render'],
            'dashicons-admin-tools',
            58
        );
    }

    public function register_settings() {
        register_setting('wpsct_group', 'wpsct_settings');
    }

    /* =========================
       PRESETS
    ========================= */

    private function get_presets() {

        return [

            'performance' => [
                'label' => __('Performance Setup', 'wp-site-control-toolkit'),
                'desc'  => __('Optimized for speed and reduced backend activity.', 'wp-site-control-toolkit'),
                'settings' => [
                    'disable-emojis' => 1,
                    'disable-embeds' => 1,
                    'heartbeat-control' => 1,
                    'media-sizes' => 1,
                    'login-security' => 1,
                ]
            ],

            'minimal' => [
                'label' => __('Minimal Setup', 'wp-site-control-toolkit'),
                'desc'  => __('Removes unnecessary WordPress features while keeping core functionality.', 'wp-site-control-toolkit'),
                'settings' => [
                    'disable-emojis' => 1,
                    'disable-embeds' => 1,
                    'cleanup-head' => 1,
                    'version-hiding' => 1,
                ]
            ],

            'secure' => [
                'label' => __('Secure Setup', 'wp-site-control-toolkit'),
                'desc'  => __('Applies basic security hardening for production environments.', 'wp-site-control-toolkit'),
                'settings' => [
                    'login-security' => 1,
                    'disable-file-editor' => 1,
                    'disable-xmlrpc' => 1,
                ]
            ],

            'custom' => [
                'label' => __('Custom Setup', 'wp-site-control-toolkit'),
                'desc'  => __('Manually configure each option based on your needs.', 'wp-site-control-toolkit'),
                'settings' => []
            ]
        ];
    }

    private function get_active_preset() {
        return get_option('wpsct_active_preset', 'custom');
    }

    public function handle_preset_save() {

        if (!isset($_POST['wpsct_set_preset'])) return;
        if (!current_user_can('manage_options')) return;

        $preset = sanitize_text_field($_POST['wpsct_set_preset']);
        update_option('wpsct_active_preset', $preset);
    }

    /* =========================
       RENDER
    ========================= */

    public function render() {

        $settings = get_option('wpsct_settings', []);
        $tab = $_GET['tab'] ?? 'cleanup';

        $presets = $this->get_presets();
        $active_preset = $this->get_active_preset();

        $preset_settings = $presets[$active_preset]['settings'] ?? [];
        $settings = array_merge($preset_settings, $settings);

        ?>

        <div class="wrap">

            <h1><?php _e('Site Control Toolkit', 'wp-site-control-toolkit'); ?></h1>

            <p class="description">
                <em><?php _e('Remove unnecessary WordPress behavior and simplify your setup.', 'wp-site-control-toolkit'); ?></em>
            </p>

            <!-- PRESETS -->
            <div class="wpsct-box">

                <strong><?php _e('Setup Mode', 'wp-site-control-toolkit'); ?></strong>

                <form method="post" class="wpsct-presets">

                    <?php foreach ($presets as $key => $preset): ?>

                        <button name="wpsct_set_preset"
                                value="<?php echo esc_attr($key); ?>"
                                class="wpsct-preset-btn <?php echo $active_preset === $key ? 'active' : ''; ?>">
                            <?php echo esc_html($preset['label']); ?>
                        </button>

                    <?php endforeach; ?>

                </form>

                <p class="wpsct-preset-desc">
                    <?php echo esc_html($presets[$active_preset]['desc'] ?? ''); ?>
                </p>

            </div>

            <!-- TABS -->
            <div class="wpsct-tabs">

                <?php $this->tab('cleanup', __('System Cleanup', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('performance', __('Performance', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('security', __('Security', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('media', __('Media', 'wp-site-control-toolkit'), $tab); ?>

            </div>

            <form method="post" action="options.php">

                <?php settings_fields('wpsct_group'); ?>

                <div class="wpsct-panel">

                    <?php $this->render_tab($tab, $settings); ?>

                </div>

                <?php submit_button(); ?>

            </form>

        </div>

        <?php
    }

    private function tab($key, $label, $current) {

        $active = $current === $key;
        ?>

        <a href="?page=wpsct&tab=<?php echo esc_attr($key); ?>"
           class="wpsct-tab <?php echo $active ? 'active' : ''; ?>">
            <?php echo esc_html($label); ?>
        </a>

        <?php
    }

    private function render_tab($tab, $settings) {

        if ($tab === 'cleanup') $this->cleanup($settings);
        if ($tab === 'performance') $this->performance($settings);
        if ($tab === 'security') $this->security($settings);
        if ($tab === 'media') $this->media($settings);
    }

    /* =========================
       TOGGLE UI
    ========================= */

    private function toggle($key, $title, $desc, $settings) {

        $value = $settings[$key] ?? false;

        ?>

        <div class="wpsct-card">

            <div>

                <div class="wpsct-title">
                    <?php echo esc_html($title); ?>
                </div>

                <div class="wpsct-desc">
                    <?php echo wp_kses_post(nl2br($desc)); ?>
                </div>

            </div>

            <label class="wpsct-toggle">

                <input type="checkbox"
                       name="wpsct_settings[<?php echo esc_attr($key); ?>]"
                       value="1"
                       <?php checked(1, $value); ?>>

                <span class="wpsct-slider"></span>

            </label>

        </div>

        <?php
    }

    /* =========================
       FEATURES
    ========================= */

    private function cleanup($settings) {

        $this->toggle(
            'disable-emojis',
            __('Disable Emojis', 'wp-site-control-toolkit'),
            __("Removes WordPress emoji support.\n\nEmoji scripts load even if not used.\n\nRecommended for most websites.", 'wp-site-control-toolkit'),
            $settings
        );

        $this->toggle(
            'disable-embeds',
            __('Disable Embeds', 'wp-site-control-toolkit'),
            __("Disables automatic embedding of external content.\n\nRemoves unnecessary scripts and requests.\n\nRecommended for performance.", 'wp-site-control-toolkit'),
            $settings
        );

        $this->toggle(
            'cleanup-head',
            __('Head Cleanup', 'wp-site-control-toolkit'),
            __("Removes unnecessary elements from the <head>.\n\nIncludes version tags and metadata.\n\nRecommended for cleaner output.", 'wp-site-control-toolkit'),
            $settings
        );

        $this->toggle(
            'version-hiding',
            __('Hide WordPress Version', 'wp-site-control-toolkit'),
            __("Removes WordPress version from HTML and feeds.\n\nReduces system exposure.\n\nRecommended for all sites.", 'wp-site-control-toolkit'),
            $settings
        );
    }

    private function performance($settings) {

        $this->toggle(
            'heartbeat-control',
            __('Heartbeat Control', 'wp-site-control-toolkit'),
            __("Reduces background requests from WordPress Heartbeat API.\n\nImproves backend performance and reduces server load.\n\nRecommended for most websites.", 'wp-site-control-toolkit'),
            $settings
        );
    }

    private function security($settings) {

        $this->toggle(
            'login-security',
            __('Hide Login Errors', 'wp-site-control-toolkit'),
            __("Replaces login error messages with generic responses.\n\nPrevents user enumeration.\n\nRecommended for production sites.", 'wp-site-control-toolkit'),
            $settings
        );

        $this->toggle(
            'disable-file-editor',
            __('Disable File Editor', 'wp-site-control-toolkit'),
            __("Disables theme and plugin file editor in admin.\n\nPrevents direct code modification from dashboard.\n\nRecommended for all production sites.", 'wp-site-control-toolkit'),
            $settings
        );

        $this->toggle(
            'disable-xmlrpc',
            __('Disable XML-RPC', 'wp-site-control-toolkit'),
            __("Disables XML-RPC endpoint.\n\nReduces attack surface and abuse vectors.\n\nRecommended unless explicitly required.", 'wp-site-control-toolkit'),
            $settings
        );
    }

    private function media($settings) {

        $this->toggle(
            'media-sizes',
            __('Control Image Sizes', 'wp-site-control-toolkit'),
            __("Prevents unnecessary image sizes from being generated.\n\nReduces storage usage and database clutter.\n\nRecommended for most websites.", 'wp-site-control-toolkit'),
            $settings
        );
    }
}