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
                        <em><?php _e('No features enabled', 'wp-site-control-toolkit'); ?></em>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($overview['active_features'] as $title): ?>
                                <li><?php echo esc_html($title); ?></li>
                            <?php endforeach; ?>
                        </ul>
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

            <!-- TABS -->
            <div class="wpsct-tabs">

                <?php $this->tab('cleanup', __('System Cleanup', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('performance', __('Performance', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('security', __('Security', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('access-api', __('Access & API', 'wp-site-control-toolkit'), $tab); ?>
                <?php $this->tab('media', __('Media', 'wp-site-control-toolkit'), $tab); ?>

            </div>

            <!-- PANEL -->
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