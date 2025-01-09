<?php
namespace HappySocialLogin\Settings;
use CSF;


CSF::createSection($this->prefix, [
    'title'  => 'Notification',
    'icon'   => 'fas fa-bell',
    //'class' => 'no-fieldset-border',
    'fields' => [
        //User Notification
        [
            'title' => 'User Notification',
            'id' => 'user-notification',
            'type' => 'fieldset',
            'fields'=> [
                [
                    'title' => 'Enable',
                    'id' => 'enable',
                    'type' => 'switcher',
                ],
                [
                    'title' => 'Subject',
                    'id' => 'subject',
                    'type' => 'text',
                    'placeholder' => 'Welcome to our website',
                    'dependency' => ['enable', '==', true]
                ],
                [
                    'title' => 'Type',
                    'id' => 'type',
                    'type' => 'select',
                    'options' =>[
                        'plain' => 'Plain Text',
                        'html' => 'HTML'
                    ],
                    'help' => 'Choose the type of message you want to receive',
                    'dependency' => ['enable', '==', true]
                ],
                [
                    'title' => 'Message',
                    'id' => 'plain-message',
                    'type' => 'textarea',
                    'dependency' =>[
                        ['enable', '==', true],
                        ['type', '==', 'plain']
                    ],
                    'placeholder' => 'Welcome to our website. We are glad to have you on board. Your login credentials are as follows: Username: [username] Password: [password].',
                    'desc' => 'Available shortcodes are [username], [password], [user_email], [user_firstname], [user_lastname], [user_displayname], [user_nicename], [user_id], [user_url], [password_reset_link]'
                ],
                [
                    'title' => 'Message',
                    'id' => 'html-message',
                    'type' => 'wp_editor',
                    'dependency' =>[
                        ['enable', '==', true],
                        ['type', '==', 'html']
                    ],
                    'tinymce' => true,
                    'quicktags' => true,
                    'desc' => 'Available shortcodes are [username], [password], [user_email], [user_firstname], [user_lastname], [user_displayname], [user_nicename], [user_id], [user_url], [password_reset_link]'
                ]
            ],
            'default' =>[
                'enable' => false,
                'subject' => 'Welcome to our website',
                'type' => 'plain',
                'plain-message' => 'Welcome to our website. We are glad to have you on board. Your login credentials are as follows: Username: [username] Password: [password].',
                'html-message' => '<p>Welcome to our website. We are glad to have you on board. Your login credentials are as follows:</p><p>Username: [username]</p><p>Password: [password]</p>'
            ]
        ],
        //Admin Notification
        [
            'title' => 'Admin Notification',
            'id' => 'admin-notification',
            'type' => 'fieldset',
            'fields'=> [
                [
                    'title' => 'Enable',
                    'id' => 'enable',
                    'type' => 'switcher',
                ],
                [
                    'title' => 'To',
                    'id' => 'to',
                    'type' => 'text',
                    'placeholder' => get_option('admin_email'),
                    'desc' => 'You can enter multiple email address separated by commas.',
                    'dependency' => ['enable', '==', true]
                ],
                [
                    'title' => 'Subject',
                    'id' => 'subject',
                    'type' => 'text',
                    'placeholder' => 'New User Registration',
                    'dependency' => ['enable', '==', true]
                ],
                [
                    'title' => 'Type',
                    'id' => 'type',
                    'type' => 'select',
                    'options' =>[
                        'plain' => 'Plain Text',
                        'html' => 'HTML'
                    ],
                    'help' => 'Choose the type of message you want to receive',
                    'dependency' => ['enable', '==', true]
                ],
                [
                    'title' => 'Message',
                    'id' => 'plain-message',
                    'type' => 'textarea',
                    'dependency' =>[
                        ['enable', '==', true],
                        ['type', '==', 'plain']
                    ],
                    'placeholder' => 'A new user has registered on your website. Please check the user details in the user section of your website.',
                    'desc' => 'Available shortcodes are [username], [password], [user_email], [user_firstname], [user_lastname], [user_displayname], [user_nicename], [user_id], [user_url], [password_reset_link]'
                ],
                [
                    'title' => 'Message',
                    'id' => 'html-message',
                    'type' => 'wp_editor',
                    'dependency' =>[
                        ['enable', '==', true],
                        ['type', '==', 'html']
                    ],
                    'tinymce' => true,
                    'quicktags' => true,
                    'desc' => 'Available shortcodes are [username], [password], [user_email], [user_firstname], [user_lastname], [user_displayname], [user_nicename], [user_id], [user_url], [password_reset_link]'
                ]
            ],
            'default' =>[
                'enable' => false,
                'to' => get_option('admin_email'),
                'subject' => 'New User Registration',
                'type' => 'plain',
                'plain-message' => 'A new user has registered on your website. Please check the user details in the user section of your website.',
                'html-message' => '<p>A new user has registered on your website. Please check the user details in the user section of your website.</p>'
            ]
        ]
    ],
]);