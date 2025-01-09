<?php

namespace HappySocialLogin\Hooks;

use HappySocialLogin\Includes\Plugin;

class ElementorActions
{
    public static function elementor_widgets_register($widgets_manager){
        $widgets_manager->register(new \HappySocialLogin\Integration\Elementor\Widgets\SocialLogin\SocialLogin());
    }

    public static function elementor_editor_enqueue_scripts(){
        wp_register_script(
            'hslogin-editor', 
            Plugin::getInstance()->getUrl() . 'assets/js/editor/editor.js',
            [],
            false,
            true
        );
        wp_enqueue_script('hslogin-editor');
    }

    public static function elementor_editor_enqueue_styles(){
        wp_enqueue_style('dashicons');
    }
}