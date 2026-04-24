<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'handle_preset_save']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /* =========================================================
     * ASSETS
     * ========================================================= */

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

    /* =========================================================
     * MENU
     * ========================================================= */

    public function menu() {

        add_menu_page(
            'Site Control Toolkit',
            'Site Control',
            'manage_options',
            'wpsct',
            [$this, 'render'],
            'dashicons-yes',
            58
        );
    }

    /* =========================================================
     * SETTINGS
     * ========================================================= */

    public function register_settings() {
        register_setting('wpsct_group', 'wpsct_settings');
    }

    public function handle_preset_save() {

        if (!isset($_POST['wpsct_set_preset'])) return;
        if (!current_user_can('manage_options')) return;

        update_option(
            'wpsct_active_preset',
            sanitize_text_field($_POST['wpsct_set_preset'])
        );
    }

    private function get_active_preset() {
        return get_option('wpsct_active_preset', 'custom');
    }

    /* =========================================================
     * RENDER
     * ========================================================= */

    public function render() {

        $settings = get_option('wpsct_settings', []);
        $tab = $_GET['tab'] ?? 'cleanup';

        $content = wpsct_get_content();
        $features = $content['features'];
        $presets  = $content['presets'];

        $active = $this->get_active_preset();

        $settings = array_merge(
            $presets[$active]['settings'] ?? [],
            $settings
        );
        ?>

        <div class="wrap wpsct-wrap">

            <h1>Site Control Toolkit</h1>

            <!-- PRESETS -->
            <div class="wpsct-preset-grid">

                <div class="wpsct-preset-controls">

                    <div class="wpsct-box-title">Configuration Mode</div>

                    <form method="post" class="wpsct-presets">

                        <?php foreach ($presets as $key => $preset): ?>

                            <button class="wpsct-preset-btn <?php echo $active === $key ? 'active' : ''; ?>"
                                    name="wpsct_set_preset"
                                    value="<?php echo esc_attr($key); ?>">

                                <?php echo esc_html($preset['label']); ?>

                            </button>

                        <?php endforeach; ?>

                    </form>

                </div>

                <div class="wpsct-preset-info">

                    <div class="wpsct-preset-active-title">
                        <?php echo esc_html($presets[$active]['label']); ?>
                    </div>

                    <div class="wpsct-meta-title">What this setup is for</div>
                    <div class="wpsct-meta-text">
                        <?php echo esc_html($presets[$active]['desc']); ?>
                    </div>

                </div>

            </div>

            <!-- TABS -->
            <div class="wpsct-tabs">

                <?php $this->tab('cleanup', 'System Cleanup', $tab); ?>
                <?php $this->tab('performance', 'Performance', $tab); ?>
                <?php $this->tab('security', 'Security', $tab); ?>
                <?php $this->tab('media', 'Media', $tab); ?>

            </div>

            <!-- FORM -->
            <form method="post" action="options.php">

                <?php settings_fields('wpsct_group'); ?>

                <div class="wpsct-panel">

                    <?php $this->render_tab($tab, $settings, $features); ?>

                </div>

                <?php submit_button(); ?>

            </form>

        </div>

        <?php
    }

    /* =========================================================
     * TAB NAV
     * ========================================================= */

    private function tab($key, $label, $current) {

        $active = $current === $key;

        ?>
        <a href="?page=wpsct&tab=<?php echo esc_attr($key); ?>"
           class="wpsct-tab <?php echo $active ? 'active' : ''; ?>">
            <?php echo esc_html($label); ?>
        </a>
        <?php
    }

    /* =========================================================
     * FEATURE RENDERING
     * ========================================================= */

    private function render_tab($tab, $settings, $features) {

        foreach ($features as $key => $feature) {

            if (($feature['group'] ?? '') !== $tab) continue;

            $this->toggle(
                $key,
                $feature['label'] ?? '',
                $feature['desc'] ?? '',
                $feature['impact'] ?? 'low',
                $settings
            );
        }
    }

    /* =========================================================
     * TOGGLE UI
     * ========================================================= */

    private function toggle($key, $title, $desc, $impact, $settings) {

        $value = $settings[$key] ?? false;
        ?>

        <div class="wpsct-card">

            <div>

                <div class="wpsct-title">
                    <?php echo esc_html($title); ?>
                </div>

                <div class="wpsct-desc">
                    <?php echo nl2br(esc_html($desc)); ?>
                </div>

                <div class="wpsct-impact">
                    Impact: <?php echo esc_html(strtoupper($impact)); ?>
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
}