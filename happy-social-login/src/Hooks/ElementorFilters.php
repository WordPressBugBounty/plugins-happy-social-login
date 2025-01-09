<?php

namespace HappySocialLogin\Hooks;

use HappySocialLogin\Includes\Plugin;

class ElementorFilters
{
    public static function elementor_icons_manager($icons)
    {
        $icons['hslogin'] = [
            'name' => 'hslogin',
            'label' => esc_html__( 'Social Login', 'happy-social-login' ),
            'url' => ( \Elementor\Plugin::$instance->editor->is_edit_mode()) ? Plugin::getInstance()->getUrl() . 'assets/icons/icons.css' : false,
            'prefix' => 'hslogin-',
            'displayPrefix' => 'hslogin',
            'labelIcon' => 'fab fa-font-awesome-flag',
            'ver' => '1.0.0',
            'fetchJson' => Plugin::getInstance()->getUrl() . '/assets/icons/icons.json',
            'native' => false,
        ];
        return $icons;
    }
}