<?php

namespace HappySocialLogin\Settings;
use CSF;

//Integration Settings
CSF::createSection($this->prefix,[
    'title' => 'Integration',
    'id'    => 'integration-tab',
    'icon' => 'fas fa-puzzle-piece',
]);

//WordPress
CSF::createSection($this->prefix, [
    'title'  => 'WordPress',
    'parent' => 'integration-tab',
    'icon'   => 'fab fa-wordpress',
    'fields' => [
        [
            'id' => 'wordpress',
            'type' => 'fieldset',
            'class'  => 'no-border',
            'fields' => [
                [
                    'title' => 'Display on wp-login page',
                    'id' => 'display-on-wp-login',
                    'type' => 'switcher',
                    'subtitle' => 'Display Social Login options on default Wordpress login & registration Page',
                ]
            ],
            'default' => [
                'display-on-wp-login' => true,
            ],
        ]
    ],
]);

//Elementor
CSF::createSection($this->prefix, [
    'title'  => 'Elementor',
    'parent' => 'integration-tab',
    'icon'   => 'fab fa-elementor',
    'fields' => [
        [
            'id' => 'elementor',
            'type' => 'fieldset',
            'class'  => 'no-border',
            'fields' => [
                [
                    'title' => 'Enable Widget',
                    'id' => 'enable',
                    'type' => 'switcher',
                    'subtitle' => 'Enable Social Login Widget for Elementor',
                ]
            ],
            'default' => [
                'enable' => true,
            ],
        ]
    ],
]);