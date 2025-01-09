<?php

namespace HappySocialLogin\Settings;
use CSF;

// Get available Profile Fields from Hybridauth
//function get_hybridauth_profile_fields(){
//    $profile = new \Hybridauth\User\Profile();
//    // Get all declared fields as an array
//    $profileFields = get_object_vars($profile);
//    $profileFields = array_keys($profileFields);
//    return $profileFields;
//}

function getBasicFields(){
    $basicFields = [
        [
            'id' => 'name',
            'type' => 'fieldset',
            'title' => 'Name',
            //'class' => 'no-fieldset-border',
            'fields' => [
                [
                    'id' => 'store',
                    'type' => 'button_set',
                    'options'=>[
                        'no' => 'Don\'t store',
                        'yes' => 'Store',
                    ]
                ]
            ]
        ],
        [
            'id' => 'email',
            'type' => 'fieldset',
            //'class' => 'no-fieldset-border',
            'title' => 'Email',
            'fields' => [
                [
                    'id' => 'store',
                    'type' => 'button_set',
                    'options'=>[
                        'no' => 'Don\'t store',
                        'yes' => 'Store',
                    ]
                ]
            ]
        ],
        [
            'id' => 'website',
            'type' => 'fieldset',
            //'class' => 'no-fieldset-border',
            'title' => 'Website',
            'fields' => [
                [
                    'id' => 'store',
                    'type' => 'button_set',
                    'options'=>[
                        'no' => 'Don\'t store',
                        'yes' => 'Store',
                    ]
                ]
            ]
        ]
    ];
    return $basicFields;
}

function getAdditionalFields(){
    // WordPress User Meta => Field got from Hybrid Auth
    $fields = [
        'locale' => 'language',
        'description' => 'description',
        'identifier' => 'identifier',
        'profile_url' => 'profileURL',
        'photo_url' => 'photoURL',
        'gender' => 'gender',
        'age' => 'age',
        'birth_day' => 'birthDay',
        'birth_month' => 'birthMonth',
        'birth_year' => 'birthYear',
        'phone' => 'phone',
        'address' => 'address',
        'country' => 'country',
        'region' => 'region',
        'city' => 'city',
        'zip' => 'zip',
        'data' => 'data'
    ];

    $additionalFields = [];

    foreach ($fields as $wp_user_meta => $provider_field) {
        $fieldData = [
            'id' => $provider_field,
            'type' => 'fieldset',
            'title' => ucfirst($provider_field),
            'fields' => [
                [
                    'type' => 'button_set',
                    'id' => 'store',
                    'options' => [
                        'no' => 'Don\'t store',
                        'yes'  => 'Store in default meta',
                        'custom-meta' => 'Store in custom meta'
                    ],
                ],
                [
                    'type' => 'submessage',
                    'content'=> "Data will be stored in user's meta table following meta key \"$wp_user_meta\"",
                    'dependency' => ['store', '==', 'yes']
                ],
                [
                    'type' => 'submessage',
                    'content'=> "Data will be stored in user's meta table following custom meta key",
                    'dependency' => ['store', '==', 'custom-meta']
                ],
                [
                    'id' => 'default-meta',
                    'type' => 'text',
                    'class' => 'hidden',
                    'attributes' => array(
                        'type' => 'hidden',
                    ),
                    'dependency' => ['store', '==', 'yes']
                ],
                [
                    'id' => 'custom-meta',
                    'type' => 'text',
                    'placeholder' => 'Enter a custom meta key',
                    'dependency' => ['store', '==', 'custom-meta']
                ]
            ]
        ];

        $additionalFields[] = $fieldData;
    }

    return $additionalFields;
}

CSF::createSection($this->prefix, [
    'title'  => 'User Fields',
    'icon'   => 'fas fa-user-cog',
    'fields' => [
        [
            'id' => 'user-fields',
            'type' => 'fieldset',
            'class' => 'no-border',
            'fields' => [
                [
                    'id' => 'basic',
                    'type' => 'fieldset',
                    'title' => 'Basic Fields',
                    'fields' => getBasicFields()
                ],
                [
                    'id' => 'additional',
                    'title' => 'Additional Fields',
                    'type' => 'fieldset',
                    'fields' => getAdditionalFields()
                ]
            ],
            'default' => [
                'basic' => [
                    'name' => [
                        'store' => 'yes'
                    ],
                    'email' => [
                        'store' => 'yes'
                    ],
                    'website' => [
                        'store' => 'yes'
                    ]
                ],
                'additional' => [
                    'language' => [
                        'store' => 'no',
                        'default-meta' => 'locale',
                        'custom-meta' => '',
                    ],
                    'description' => [
                        'store' => 'no',
                        'default-meta' => 'description',
                        'custom-meta' => '',
                    ],
                    'identifier' => [
                        'store' => 'no',
                        'default-meta' => 'identifier',
                        'custom-meta' => '',
                    ],
                    'profileURL' => [
                        'store' => 'no',
                        'default-meta' => 'profile_url',
                        'custom-meta' => '',
                    ],
                    'photoURL' => [
                        'store' => 'no',
                        'default-meta' => 'photo_url',
                        'custom-meta' => '',
                    ],
                    'gender' => [
                        'store' => 'no',
                        'default-meta' => 'gender',
                        'custom-meta' => '',
                    ],
                    'age' => [
                        'store' => 'no',
                        'default-meta' => 'age',
                        'custom-meta' => '',
                    ],
                    'birthDay' => [
                        'store' => 'no',
                        'default-meta' => 'birth_day',
                        'custom-meta' => '',
                    ],
                    'birthMonth' => [
                        'store' => 'no',
                        'default-meta' => 'birth_month',
                        'custom-meta' => '',
                    ],
                    'birthYear' => [
                        'store' => 'no',
                        'default-meta' => 'birth_year',
                        'custom-meta' => '',
                    ],
                    'phone' => [
                        'store' => 'no',
                        'default-meta' => 'phone',
                        'custom-meta' => '',
                    ],
                    'address' => [
                        'store' => 'no',
                        'default-meta' => 'address',
                        'custom-meta' => '',
                    ],
                    'country' => [
                        'store' => 'no',
                        'default-meta' => 'country',
                        'custom-meta' => '',
                    ],
                    'region' => [
                        'store' => 'no',
                        'default-meta' => 'region',
                        'custom-meta' => '',
                    ],
                    'city' => [
                        'store' => 'no',
                        'default-meta' => 'city',
                        'custom-meta' => '',
                    ],
                    'zip' => [
                        'store' => 'no',
                        'default-meta' => 'zip',
                        'custom-meta' => '',
                    ],
                    'data' => [
                        'store' => 'no',
                        'default-meta' => 'data',
                        'custom-meta' => '',
                    ],
                ]
            ]
        ]
    ],
]);
