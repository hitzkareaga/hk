<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Render {

    private $features;
    private $plans;
    private $overview;

    public function __construct($features, $plans = null) {

        $this->features = $features;
        $this->plans = $plans;
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

        $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'cleanup';

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

            <div class="wpsct-page-header">
                <h1><?php _e('Site Control Toolkit', 'wp-site-control-toolkit'); ?></h1>

                <button type="button"
                        class="button wpsct-premium-open"
                        data-wpsct-modal-open="wpsct-premium-modal">
                    <?php _e('VER PLANES PREMIUM', 'wp-site-control-toolkit'); ?>
                </button>
            </div>

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

            <?php $this->render_premium_modal(); ?>

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

        $tab_features = array_filter($features, function ($feature) use ($tab) {
            return ($feature['group'] ?? '') === $tab;
        });

        uasort($tab_features, function ($a, $b) {
            return (int) !empty($a['pro']) <=> (int) !empty($b['pro']);
        });

        foreach ($tab_features as $key => $f) {

            $this->toggle(
                $key,
                $f['title'],
                $f['desc'],
                $f['changes'],
                $f['risk'] ?? '',
                $f['why'],
                $f['impact'],
                !empty($f['pro']),
                $settings,
                $tab
            );
        }
    }

    private function render_premium_modal() {

        $plans = $this->plans && method_exists($this->plans, 'get_plans')
            ? $this->plans->get_plans()
            : [];
        ?>

        <div class="wpsct-modal" id="wpsct-premium-modal" hidden>
            <div class="wpsct-modal-backdrop" data-wpsct-modal-close></div>

            <div class="wpsct-modal-dialog"
                 role="dialog"
                 aria-modal="true"
                 aria-labelledby="wpsct-premium-modal-title">

                <div class="wpsct-modal-header">
                    <div>
                        <h2 id="wpsct-premium-modal-title"><?php _e('Premium plans', 'wp-site-control-toolkit'); ?></h2>
                        <p><?php _e('Choose the license size that matches how many websites you manage.', 'wp-site-control-toolkit'); ?></p>
                    </div>

                    <button type="button"
                            class="wpsct-modal-close"
                            data-wpsct-modal-close
                            aria-label="<?php esc_attr_e('Close premium plans', 'wp-site-control-toolkit'); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="wpsct-plan-grid">
                    <?php foreach ($plans as $plan): ?>
                        <div class="wpsct-plan-card">
                            <div class="wpsct-plan-name"><?php echo esc_html($plan['name']); ?></div>

                            <div class="wpsct-plan-price">
                                <span class="wpsct-plan-currency">&euro;</span><?php echo esc_html($plan['price']); ?>
                            </div>

                            <div class="wpsct-plan-sites"><?php echo esc_html($plan['sites']); ?></div>
                            <div class="wpsct-plan-audience"><?php echo esc_html($plan['audience']); ?></div>

                            <button type="button" class="button wpsct-plan-button" disabled>
                                <?php _e('Coming soon', 'wp-site-control-toolkit'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php
    }

    private function toggle($key, $title, $desc, $changes, $risk, $why, $impact, $is_pro, $settings, $group) {

        $value = !$is_pro && !empty($settings[$group][$key]);
        $details_id = 'wpsct-details-' . sanitize_html_class($key);
        ?>

        <div class="wpsct-card <?php echo $is_pro ? 'wpsct-card-pro' : ''; ?>">

            <div class="wpsct-row-top">

                <div class="wpsct-feature-copy">
                    <div class="wpsct-title-row">
                        <button type="button"
                                class="wpsct-info-toggle"
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr($details_id); ?>"
                                title="<?php esc_attr_e('Show details', 'wp-site-control-toolkit'); ?>">
                            <span aria-hidden="true">i</span>
                            <span class="screen-reader-text"><?php _e('Show feature details', 'wp-site-control-toolkit'); ?></span>
                        </button>

                        <div class="wpsct-title">
                            <button type="button"
                                    class="wpsct-title-toggle"
                                    aria-expanded="false"
                                    aria-controls="<?php echo esc_attr($details_id); ?>">
                                <?php echo esc_html($title); ?>
                            </button>
                            <?php if ($is_pro): ?>
                                <span class="wpsct-pro-badge"><?php _e('Premium', 'wp-site-control-toolkit'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="wpsct-desc"><?php echo esc_html($desc); ?></div>
                </div>

                <div class="wpsct-feature-actions">
                    <div class="wpsct-action-row">
                        <div class="wpsct-toggle-stack">
                            <label class="wpsct-toggle">
                                <input type="checkbox"
                                       name="wpsct_settings[<?php echo esc_attr($group); ?>][<?php echo esc_attr($key); ?>]"
                                       value="1"
                                       <?php disabled(true, $is_pro); ?>
                                       <?php checked(true, $value); ?>>
                                <span class="wpsct-slider"></span>
                            </label>

                            <div class="wpsct-impact">
                                <?php _e('Impact:', 'wp-site-control-toolkit'); ?>
                                <?php echo esc_html($impact); ?>
                            </div>

                            <?php if ($is_pro): ?>
                                <button type="button"
                                        class="button wpsct-feature-buy"
                                        data-wpsct-modal-open="wpsct-premium-modal">
                                    <?php _e('COMPRAR', 'wp-site-control-toolkit'); ?>
                                </button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>

            <div class="wpsct-row-bottom" id="<?php echo esc_attr($details_id); ?>" hidden>

                <div>
                    <div class="wpsct-sm-title"><?php _e('What this changes:', 'wp-site-control-toolkit'); ?></div>
                    <?php echo esc_html($changes); ?>
                    <?php if ($risk !== ''): ?>
                        <div class="wpsct-risk-note">
                            <?php echo esc_html($risk); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="wpsct-sm-title"><?php _e('Why this is useful:', 'wp-site-control-toolkit'); ?></div>
                    <?php echo esc_html($why); ?>
                </div>

            </div>

        </div>

        <?php
    }
}
