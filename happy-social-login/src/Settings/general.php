<?php
namespace HappySocialLogin\Settings;
use CSF;

CSF::createSection($this->prefix, [
    'title'  => 'General',
//            'parent' => 'global-tab',
    'icon'   => 'fas fa-tools',
    //'class' => 'no-fieldset-border',
    'fields' => [
        //User Role
        [
            'id'      => 'user-role',
            'title'   => 'Default User Role',
            'subtitle'    => 'Role to be assigned on new user registration',
            'type'    => 'select',
            'options' => 'roles',
            'default' => 'subscriber'
        ],
        //Login Redirection Rules
        [
            'id' => 'login-redirection-rules',
            'title' => 'Login Redirection Rules',
            'subtitle'    => "Choose where users go after logging in",
            'type' => 'group',
            'max' => count(wp_roles()->get_names()),
            //'button_title' => 'Add New Rule',
            //'accordion_title_by' => ['user-role', 'redirect-to'],
            //'accordion_title_by_prefix' => ' | ',
            'fields' => [
                [
                    'id' => 'user-role',
                    'type' => 'select',
                    'title' => 'User Role',
                    'options'=> 'roles'
                ],
                [
                    'id'         => 'redirect-to',
                    'type'       => 'text',
                    'title'      => 'Redirect to',
                    'placeholder' => 'Enter complete URL including http(s)',
                ],
            ],
            'default' =>[
                [
                    'user-role' => 'administrator',
                    'redirect-to' => admin_url()
                ],
                [
                    'user-role' => 'subscriber',
                    'redirect-to' => home_url()
                ]
            ]
        ],
        //Logout Redirection Rules
        [
            'id' => 'logout-redirection-rules',
            'title' => 'Logout Redirection Rules',
            'subtitle'    => "Choose where users go after logging out",
            'type' => 'group',
            'max' => count(wp_roles()->get_names()),
            'button_title' => 'Add New Rule',
            'fields' => [
                [
                    'id' => 'user-role',
                    'type' => 'select',
                    'title' => 'User Role',
                    'options'=> 'roles'
                ],
                [
                    'id'         => 'redirect-to',
                    'type'       => 'text',
                    'title'      => 'Redirect to',
                    'placeholder' => 'Enter complete URL including http(s)',
                ],
            ],
            'default' =>[
                [
                    'user-role' => 'administrator',
                    'redirect-to' => wp_login_url()
                ],
                [
                    'user-role' => 'subscriber',
                    'redirect-to' => home_url()
                ]
            ]
        ]
    ],
]);