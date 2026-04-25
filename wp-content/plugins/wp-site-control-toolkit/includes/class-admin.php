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
            'dashicons-yes',
            58
        );
    }

    public function register_settings() {
        register_setting('wpsct_group', 'wpsct_settings');
    }

    private function get_active_preset() {
        return get_option('wpsct_active_preset', 'custom');
    }

    public function handle_preset_save() {

        if (!isset($_POST['wpsct_set_preset'])) return;
        if (!current_user_can('manage_options')) return;

        update_option(
            'wpsct_active_preset',
            sanitize_text_field($_POST['wpsct_set_preset'])
        );
    }

    /* =========================
       PRESETS
    ========================= */

    private function get_presets() {

        return [

            'performance' => [
                'label' => 'Performance',
                'desc'  => 'Optimized for speed and reduced backend activity.',
                'settings' => [
                    'disable-emojis' => 1,
                    'disable-embeds' => 1,
                    'heartbeat-control' => 1,
                    'media-sizes' => 1,
                ]
            ],

            'secure' => [
                'label' => 'Secure',
                'desc'  => 'Basic security hardening.',
                'settings' => [
                    'login-security' => 1,
                    'disable-file-editor' => 1,
                    'disable-xmlrpc' => 1,
                ]
            ],

            'custom' => [
                'label' => 'Custom',
                'desc'  => 'Manual configuration.',
                'settings' => []
            ]
        ];
    }

    /* =========================
       FEATURES
    ========================= */

    private function get_features() {

        return [

            'disable-emojis' => [
                'title' => 'Disable Emojis',
                'desc' => 'Removes emoji scripts.',
                'changes' => 'Removes emoji assets from frontend/admin.',
                'why' => 'Reduces unnecessary HTTP requests.',
                'impact' => 'LOW',
                'group' => 'cleanup'
            ],

            'disable-embeds' => [
                'title' => 'Disable Embeds',
                'desc' => 'Disables oEmbeds.',
                'changes' => 'Stops embed scripts loading.',
                'why' => 'Reduces external requests.',
                'impact' => 'LOW',
                'group' => 'cleanup'
            ],

            'cleanup-head' => [
                'title' => 'Head Cleanup',
                'desc' => 'Removes unnecessary WP head tags.',
                'changes' => 'Cleans wp_head output.',
                'why' => 'Cleaner HTML and less metadata leakage.',
                'impact' => 'MEDIUM',
                'group' => 'cleanup'
            ],

            'heartbeat-control' => [
                'title' => 'Heartbeat Control',
                'desc' => 'Reduces WP heartbeat usage.',
                'changes' => 'Less admin AJAX calls.',
                'why' => 'Improves backend performance.',
                'impact' => 'MEDIUM',
                'group' => 'performance'
            ],

            'login-security' => [
                'title' => 'Hide Login Errors',
                'desc' => 'Generic login errors.',
                'changes' => 'Hides login hints.',
                'why' => 'Prevents user enumeration.',
                'impact' => 'LOW',
                'group' => 'security'
            ],

            'disable-file-editor' => [
                'title' => 'Disable File Editor',
                'desc' => 'Disables WP editor.',
                'changes' => 'Removes theme/plugin editor.',
                'why' => 'Prevents accidental code changes.',
                'impact' => 'MEDIUM',
                'group' => 'security'
            ],

            'disable-xmlrpc' => [
                'title' => 'Disable XML-RPC',
                'desc' => 'Disables XML-RPC.',
                'changes' => 'Blocks remote access endpoint.',
                'why' => 'Improves security surface.',
                'impact' => 'MEDIUM',
                'group' => 'security'
            ],

            'media-sizes' => [
                'title' => 'Control Image Sizes',
                'desc' => 'Stops extra image sizes.',
                'changes' => 'Prevents extra image generation.',
                'why' => 'Saves storage space.',
                'impact' => 'MEDIUM',
                'group' => 'media'
            ],
        ];
    }

    /* =========================
       ACTIVE FEATURES LIST
    ========================= */

    private function get_active_features_list($settings, $features) {

        $active = [];

        foreach ($settings as $key => $value) {

            if (empty($value)) continue;
            if (!isset($features[$key])) continue;

            $active[] = $features[$key]['title'];
        }

        return $active;
    }

    /* =========================
       RENDER
    ========================= */

    public function render() {

        $settings = get_option('wpsct_settings', []);
        $tab = $_GET['tab'] ?? 'cleanup';

        $presets = $this->get_presets();
        $features = $this->get_features();

        $active = $this->get_active_preset();

        // preset + overrides (important UX behavior)
        $preset_settings = $presets[$active]['settings'] ?? [];
        $settings = array_merge($preset_settings, $settings);

        ?>

        <div class="wrap wpsct-wrap">

            <h1>Site Control Toolkit</h1>

            <div class="wpsct-preset-grid">

                <!-- LEFT -->
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

                <!-- RIGHT -->
                <div class="wpsct-preset-info">

                    <div class="wpsct-preset-active-title">
                        <?php echo esc_html($presets[$active]['label']); ?>
                    </div>

                    <div class="wpsct-meta-title">What this setup is for</div>
                    <div class="wpsct-meta-text">
                        <?php echo esc_html($presets[$active]['desc']); ?>
                    </div>

                    <div class="wpsct-preset-activates">

                        <div class="wpsct-preset-activates-title">
                            What this setup activates
                        </div>

                        <div class="wpsct-preset-activates-list">

                            <?php
                            $active_list = $this->get_active_features_list($settings, $features);
                            ?>

                            <?php if (empty($active_list)): ?>
                                <em>No features enabled</em>
                            <?php else: ?>
                                <?php foreach ($active_list as $item): ?>
                                    <div>• <?php echo esc_html($item); ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>

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

    private function tab($key, $label, $current) {

        $active = $current === $key;

        ?>
        <a href="?page=wpsct&tab=<?php echo esc_attr($key); ?>"
           class="wpsct-tab <?php echo $active ? 'active' : ''; ?>">
            <?php echo esc_html($label); ?>
        </a>
        <?php
    }

    private function render_tab($tab, $settings, $features) {

        foreach ($features as $key => $f) {

            if ($f['group'] !== $tab) continue;

            $this->toggle(
                $key,
                $f['title'],
                $f['desc'],
                $f['changes'],
                $f['why'],
                $f['impact'],
                $settings
            );
        }
    }

    private function toggle($key, $title, $desc, $changes, $why, $impact, $settings) {

        $value = !empty($settings[$key]);
        ?>

        <div class="wpsct-card">

            <div class="wpsct-row-top">

                <div>

                    <div class="wpsct-title"><?php echo esc_html($title); ?></div>

                    <div class="wpsct-desc"><?php echo esc_html($desc); ?></div>

                </div>

                <label class="wpsct-toggle">

                    <input type="checkbox"
                           name="wpsct_settings[<?php echo esc_attr($key); ?>]"
                           value="1"
                           <?php checked(true, $value); ?>>

                    <span class="wpsct-slider"></span>

                </label>

            </div>

            <div class="wpsct-row-bottom">

                <div>
                    <div class="wpsct-sm-title">What this changes:</div>
                    <?php echo esc_html($changes); ?>
                </div>

                <div>
                    <div class="wpsct-sm-title">Why this is useful:</div>
                    <?php echo esc_html($why); ?>
                </div>

                <div class="wpsct-impact">
                    Impact: <?php echo esc_html($impact); ?>
                </div>

            </div>

        </div>

        <?php
    }
}