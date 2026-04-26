<?php

if (!defined('ABSPATH')) exit;

class WPSCT_Admin_Overview {

    private $features;

    public function __construct(array $features) {
        $this->features = $features;
    }

    /**
     * 🔥 NORMALIZA settings por tabs -> flat list
     */
    private function flatten_settings(array $settings): array {

        $flat = [];

        foreach ($settings as $group => $items) {

            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $key => $value) {
                $flat[$key] = !empty($value);
            }
        }

        return $flat;
    }

    public function get_overview(array $settings): array {

        $settings = is_array($settings) ? $settings : [];

        $flat = $this->flatten_settings($settings);

        $total = count($this->features);

        $active = 0;
        $active_titles = [];

        foreach ($flat as $key => $value) {

            if (!$value) continue;
            if (!isset($this->features[$key])) continue;

            $active++;
            $active_titles[] = $this->features[$key]['title'];
        }

        $score = $total > 0
            ? (int)(($active / $total) * 100)
            : 0;

        $recommendations = $this->get_recommendations($flat);

        return [
            'score' => $score,
            'level' => $this->get_level($score),
            'recommendations' => $recommendations,
            'active_features' => $active_titles
        ];
    }

    private function get_level(int $score): string {

        if ($score >= 80) {
            return __('Optimized', 'wp-site-control-toolkit');
        }

        if ($score >= 50) {
            return __('Balanced', 'wp-site-control-toolkit');
        }

        if ($score >= 20) {
            return __('Basic', 'wp-site-control-toolkit');
        }

        return __('Minimal', 'wp-site-control-toolkit');
    }

    /**
     * 🔥 recomendaciones basadas en lo NO activo
     */
    private function get_recommendations(array $flat): array {

        $map = [
            'disable-emojis' => [
                'title' => __('Disable Emojis', 'wp-site-control-toolkit'),
                'reason' => __('Removes unnecessary scripts that slow frontend performance', 'wp-site-control-toolkit'),
                'impact' => 2,
                'group' => 'performance'
            ],
            'cleanup-head' => [
                'title' => __('Clean WordPress Head', 'wp-site-control-toolkit'),
                'reason' => __('Removes unnecessary metadata and clutter', 'wp-site-control-toolkit'),
                'impact' => 3,
                'group' => 'cleanup'
            ],
            'disable-embeds' => [
                'title' => __('Disable Embeds', 'wp-site-control-toolkit'),
                'reason' => __('Prevents external embed scripts loading', 'wp-site-control-toolkit'),
                'impact' => 4,
                'group' => 'performance'
            ],
            'limit-login' => [
                'title' => __('Limit Login Attempts', 'wp-site-control-toolkit'),
                'reason' => __('Protects against brute force attacks', 'wp-site-control-toolkit'),
                'impact' => 5,
                'group' => 'security'
            ]
        ];

        $recs = [];

        foreach ($map as $key => $data) {

            if (!empty($flat[$key])) {
                continue;
            }

            $recs[] = [
                'key' => $key,
                'title' => $data['title'],
                'reason' => $data['reason'],
                'impact' => $data['impact'],
                'group' => $data['group']
            ];
        }

        usort($recs, fn($a, $b) => $b['impact'] <=> $a['impact']);

        return $recs;
    }
}