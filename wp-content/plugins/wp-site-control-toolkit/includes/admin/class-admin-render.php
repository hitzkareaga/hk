<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Render {

    private $presets;
    private $features;

    public function __construct($presets, $features) {
        $this->presets = $presets;
        $this->features = $features;
    }

    private function get_active_features_list($settings, $features) {

        $active = [];

        foreach ($settings as $key => $value) {

            if (empty($value)) continue;
            if (!isset($features[$key])) continue;

            $active[] = $features[$key]['title'];
        }

        return $active;
    }

    public function render() {

        $settings = get_option('wpsct_settings', []);
        $tab = $_GET['tab'] ?? 'cleanup';

        $presets = $this->presets->get_presets();
        $features = $this->features->get_features();

        $active = $this->presets->get_active_preset();

        $preset_settings = $presets[$active]['settings'] ?? [];
        $settings = array_merge($preset_settings, $settings);
        ?>

        <div class="wrap wpsct-wrap">

            <h1><?php _e('Site Control Toolkit', 'wp-site-control-toolkit'); ?></h1>

            <div class="wpsct-preset-grid">

                <div class="wpsct-preset-controls">

                    <div class="wpsct-box-title"><?php _e('Configuration Mode', 'wp-site-control-toolkit'); ?></div>

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

                    <div class="wpsct-meta-title"><?php _e('What this setup is for', 'wp-site-control-toolkit'); ?></div>
                    <div class="wpsct-meta-text">
                        <?php echo esc_html($presets[$active]['desc']); ?>
                    </div>

                    <div class="wpsct-preset-activates">

                        <div class="wpsct-preset-activates-title">
                            <?php _e('What this setup activates', 'wp-site-control-toolkit'); ?>
                        </div>

                        <div class="wpsct-preset-activates-list">

                            <?php $active_list = $this->get_active_features_list($settings, $features); ?>

                            <?php if (empty($active_list)): ?>
                                <em><?php _e('No features enabled', 'wp-site-control-toolkit'); ?></em>
                            <?php else: ?>
                                <?php foreach ($active_list as $item): ?>
                                    <div>• <?php echo esc_html($item); ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

            <div class="wpsct-tabs">

                <?php $this->tab('cleanup', __('System Cleanup', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('performance', __('Performance', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('security', __('Security', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('access-api', __('Access & API', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('media', __('Media', 'wp-site-control-toolkit'), $tab); ?>

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
                    <div class="wpsct-sm-title"><?php _e('What this changes:', 'wp-site-control-toolkit'); ?></div>
                    <?php echo esc_html($changes); ?>
                </div>

                <div>
                    <div class="wpsct-sm-title"><?php _e('Why this is useful:', 'wp-site-control-toolkit'); ?></div>
                    <?php echo esc_html($why); ?>
                </div>

                <div class="wpsct-impact">
                    <?php _e('Impact:', 'wp-site-control-toolkit'); ?> <?php echo esc_html($impact); ?>
                </div>

            </div>

        </div>

        <?php
    }
}