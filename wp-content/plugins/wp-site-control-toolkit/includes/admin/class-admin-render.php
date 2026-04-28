<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Render {

    private $features;
    private $overview;

    public function __construct($features) {

        $this->features = $features;
        $this->overview = null;

        $overview_class = WPSCT_PATH . 'includes/admin/class-admin-overview.php';

        if (file_exists($overview_class)) {
            require_once $overview_class;

            if (class_exists('WPSCT_Admin_Overview')) {
                $this->overview = new WPSCT_Admin_Overview(
                    $this->features->get_features()
                );
            }
        }
    }

    public function render() {

        $settings = get_option('wpsct_settings', []);
        $settings = is_array($settings) ? $settings : [];

        $tab = $_GET['tab'] ?? 'cleanup';

        $features = $this->features->get_features();

        $overview = [
            'score' => 0,
            'level' => __('Unknown', 'wp-site-control-toolkit'),
            'recommendations' => [],
            'active_features' => []
        ];

        if ($this->overview instanceof WPSCT_Admin_Overview) {

            $data = $this->overview->get_overview($settings);

            if (is_array($data)) {
                $overview = array_merge($overview, $data);
            }
        }

        ?>

        <div class="wrap wpsct-wrap">

            <h1><?php _e('Site Control Toolkit', 'wp-site-control-toolkit'); ?></h1>

            <!-- OVERVIEW -->
            <div class="wpsct-overview">

                <!-- SCORE -->
                <div class="wpsct-overview-section wpsct-overview-score">

                    <h3><?php _e('Score', 'wp-site-control-toolkit'); ?></h3>

                    <span class="wpsct-score-number">
                        <span><?php echo esc_html($overview['score']); ?></span> / 100
                    </span>

                    <div class="wpsct-overview-level">
                        <?php echo esc_html($overview['level']); ?>
                    </div>

                </div>

                <!-- ACTIVE -->
                <div class="wpsct-overview-section">

                    <h3><?php _e('Active features', 'wp-site-control-toolkit'); ?></h3>

                    <?php if (empty($overview['active_features'])): ?>
                        <em class="wpsct-active-features-text"><?php _e('No features enabled', 'wp-site-control-toolkit'); ?></em>
                    <?php else: ?>
                        <div class="wpsct-active-features-text">
                            <?php echo esc_html(implode(', ', $overview['active_features'])); ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- RECOMMENDED -->
                <div class="wpsct-overview-section">

                    <h3><?php _e('Recommended improvements', 'wp-site-control-toolkit'); ?></h3>

                    <?php if (empty($overview['recommendations'])): ?>
                        <em><?php _e('No recommendations available', 'wp-site-control-toolkit'); ?></em>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($overview['recommendations'] as $rec): ?>
                                <li>
                                    <strong><?php echo esc_html($rec['title']); ?></strong><br>
                                    <small><?php echo esc_html($rec['reason']); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>

            </div>

            <form method="post" action="options.php">

                <?php settings_fields('wpsct_group'); ?>
                <input type="hidden" name="wpsct_current_tab" value="<?php echo esc_attr($tab); ?>">

                <!-- TABS -->
                <div class="wpsct-tabs">

                    <div class="wpsct-tabs-nav">
                        <?php $this->tab('cleanup', __('Core & UI Cleanup', 'wp-site-control-toolkit'), $tab); ?>
                        <?php $this->tab('performance', __('Performance', 'wp-site-control-toolkit'), $tab); ?>
                        <?php $this->tab('security', __('Security', 'wp-site-control-toolkit'), $tab); ?>
                        <?php $this->tab('access-api', __('API & Public Exposure', 'wp-site-control-toolkit'), $tab); ?>
                        <?php $this->tab('media', __('Media & Asset', 'wp-site-control-toolkit'), $tab); ?>
                    </div>

                    <div class="wpsct-tabs-actions">
                        <button type="submit" name="submit" class="button button-primary wpsct-save-button">
                            <span class="wpsct-save-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" focusable="false">
                                    <path d="M5 3h11l3 3v15H5V3zm2 2v4h8V5H7zm0 8v6h10v-6H7zm2 1h6v4H9v-4z" />
                                </svg>
                            </span>
                            <span><?php echo esc_html(sprintf(__('Save %s', 'wp-site-control-toolkit'), $this->get_tab_label($tab))); ?></span>
                        </button>
                    </div>

                </div>

                <!-- PANEL -->
                <div class="wpsct-panel">
                    <?php $this->render_tab($tab, $settings, $features); ?>
                </div>

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

    private function get_tab_label($tab) {

        $labels = [
            'cleanup' => __('Core & UI Cleanup', 'wp-site-control-toolkit'),
            'performance' => __('Performance', 'wp-site-control-toolkit'),
            'security' => __('Security', 'wp-site-control-toolkit'),
            'access-api' => __('API & Public Exposure', 'wp-site-control-toolkit'),
            'media' => __('Media & Asset', 'wp-site-control-toolkit'),
        ];

        return $labels[$tab] ?? __('Settings', 'wp-site-control-toolkit');
    }

    private function render_tab($tab, $settings, $features) {

        foreach ($features as $key => $f) {

            if (($f['group'] ?? '') !== $tab) continue;

            $this->toggle(
                $key,
                $f['title'],
                $f['desc'],
                $f['changes'],
                $f['why'],
                $f['impact'],
                $settings,
                $tab
            );
        }
    }

    private function toggle($key, $title, $desc, $changes, $why, $impact, $settings, $group) {

        $value = !empty($settings[$group][$key]);
        ?>

        <div class="wpsct-card">

            <div class="wpsct-row-top">

                <div>
                    <div class="wpsct-title"><?php echo esc_html($title); ?></div>
                    <div class="wpsct-desc"><?php echo esc_html($desc); ?></div>
                </div>

                <label class="wpsct-toggle">
                    <input type="checkbox"
                           name="wpsct_settings[<?php echo esc_attr($group); ?>][<?php echo esc_attr($key); ?>]"
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
                    <?php _e('Impact:', 'wp-site-control-toolkit'); ?>
                    <?php echo esc_html($impact); ?>
                </div>

            </div>

        </div>

        <?php
    }
}
