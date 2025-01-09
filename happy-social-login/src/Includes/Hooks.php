<?php

namespace HappySocialLogin\Includes;

use HappySocialLogin\Hooks\ElementorActions;
use HappySocialLogin\Hooks\ElementorFilters;
use HappySocialLogin\Hooks\WordpressActions;
use HappySocialLogin\Hooks\WordpressFilters;
use HappySocialLogin\Utils\Misc;

class Hooks
{
    private static ?Hooks $instance = null;

    /**
     * Get the plugin instance
     */
    public static function getInstance(): ?Hooks
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerActions(): void
    {
        add_action('plugins_loaded', [WordpressActions::class, 'plugins_loaded']);
        add_action('init', [WordpressActions::class, 'init']);
        add_action('wp_loaded', [WordpressActions::class, 'wp_loaded']);
        add_action('user_register', [WordpressActions::class, 'user_register'], 10, 2);
        add_action('show_user_profile', [WordpressActions::class, 'usermeta_list'] );
        add_action('edit_user_profile', [WordpressActions::class, 'usermeta_list'] );

        $settings = Misc::get_plugin_settings();
        if(isset($settings['elementor']['enable']) &&  $settings['elementor']['enable'] == "1"){
            add_action('elementor/widgets/register', [ElementorActions::class, 'elementor_widgets_register']);
            add_action('elementor/editor/after_enqueue_scripts', [ElementorActions::class, 'elementor_editor_enqueue_scripts']);
            add_action('elementor/editor/after_enqueue_styles', [ElementorActions::class, 'elementor_editor_enqueue_styles']);
        }
    }

    public function registerFilters(): void
    {
        add_filter('query_vars', [WordpressFilters::class, 'query_vars']);
        add_filter('template_include', [WordpressFilters::class, 'template_include']);
        add_filter('logout_url', [WordpressFilters::class, 'logout_url'], 10, 2);

        $settings = Misc::get_plugin_settings();

        if(isset($settings['wordpress']['display-on-wp-login']) && $settings['wordpress']['display-on-wp-login'] == "1"){
            //Add Social Login Buttons on wp-login.php page before login message
            add_filter('login_message', [WordpressFilters::class, 'login_message']);
        }

        if(isset($settings['elementor']['enable']) && $settings['elementor']['enable'] == "1") {
            add_filter('elementor/icons_manager/native', [ElementorFilters::class, 'elementor_icons_manager']);
        }
    }
}