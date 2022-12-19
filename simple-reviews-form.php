<?php
/*
Plugin Name: Simple Reviews Form
Plugin URI: https://
Description: Just another feedback plugin. Simple...
Author: LadislavBelov
Author URI: https://
Text Domain: simple-reviews-form
Version: 1.0
Licence: GPLv2 or later
*/

if (!defined('ABSPATH')) {
    die;
}

define('SRF__PLUGIN_PATH', plugin_dir_path(__FILE__));

if (!class_exists('SRFCpt')) {
    require SRF__PLUGIN_PATH . 'inc/srf-cpt.php';
}

require SRF__PLUGIN_PATH . 'inc/class-review-form.php';
class SRF {

    function register() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
    }
    public function enqueue_admin() {
        wp_enqueue_style('srf_admin', plugins_url('/assets/css/admin/style.css', __FILE__));
        wp_enqueue_script('srf_admin', plugins_url('/assets/js/admin/script.js', __FILE__), array('jquery'), 1.0, true);
    }
    public function enqueue_front() {
        wp_enqueue_style('srf_style', plugins_url('/assets/css/front/style.css', __FILE__));
        wp_enqueue_script('srf_script', plugins_url('/assets/js/front/script.js', __FILE__), array('jquery'), 1.0, true);
    }

    static function activation()
    {
        flush_rewrite_rules();
    }

    static function deactivation()
    {
        flush_rewrite_rules();
    }
}
if (class_exists('SRF')) {
    $SRF = new SRF();
    $SRF->register();
}


register_activation_hook(__FILE__, array($SRF, 'activation'));
register_deactivation_hook(__FILE__, array($SRF, 'deactivation'));