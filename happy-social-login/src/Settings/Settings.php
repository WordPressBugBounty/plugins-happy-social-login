<?php
namespace HappySocialLogin\Settings;

use CSF;
use HappySocialLogin\Includes\Plugin;
use HappySocialLogin\Utils\SocialButtons;

class Settings {

    private $prefix = 'hslogin';

    private $plugin_url;

    private $plugin_path;

    private static $instance = null;

    private function __construct() {
        $this->plugin_url = Plugin::getInstance()->getUrl();
        $this->plugin_path = Plugin::getInstance()->getPath();
    }

    /**
     * Get the plugin instance
     */
    public static function getInstance(): ?Settings
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register() {
        $this->create_options();
        $this->create_tabs();
        $this->enqueue_assets();
    }

    public function enqueue_assets(){
        add_action('csf_enqueue', function(){
            wp_enqueue_script('hslogin-social-login-buttons-script');
            wp_enqueue_script('hslogin-admin-script',  $this->plugin_url . 'assets/js/admin/settings.js', ['jquery'], '1.0.0', true);
            wp_enqueue_style('hslogin-admin-style', $this->plugin_url . 'assets/css/admin/settings.css', [], '1.0.0');
        });
    }

    private function create_options() {
        CSF::createOptions($this->prefix, [
            'framework_title'       => 'HAPPY SOCIAL LOGIN <small>&nbsp;<a style="color:inherit;text-decoration:none" href="https://wpfolk.com" target="_blank">By Wpfolk.com</a></small>',
            'menu_icon'             => 'dashicons-image-filter',
            'save_defaults'         => true,
            'show_all_options'      => false,
            'menu_title'            => 'Happy Social Login',
            'menu_slug'             => $this->prefix,
            'show_search'           => false,
            'show_footer'           => true,
            'footer_text'           => '',
            'footer_after'          => '',
            'footer_credit'         => '',
            'show_reset_all'        => false,
            'show_reset_section'    => false,
        ]);
    }

    private function create_tabs() {
        require $this->plugin_path . 'src/Settings/general.php';
        require $this->plugin_path . 'src/Settings/notification.php';
        require $this->plugin_path . 'src/Settings/user-fields.php';
        require $this->plugin_path . 'src/Settings/providers.php';
        require $this->plugin_path . 'src/Settings/integration.php';
    }
}
