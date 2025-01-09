<?php

namespace HappySocialLogin\Providers;

use HappySocialLogin\Utils\Misc;

abstract class ProviderBase
{
    abstract public function getID();

    abstract public function getLabel();

    //https://www.iconfinder.com/
    //https://github.com/dheereshagrwal/colored-icons/tree/master/public/logos
    abstract public function getIcons();

    public function getSettings()
    {
        $settings = [];

        //Enable Button
        $settings[] = [
            'title' => 'Enable',
            'id'    => 'enabled',
            'type'  => 'switcher'
        ];

        //API settings
        $apiSettings = $this->getApiSettings();
        $fields = [];
        foreach($apiSettings as $apiSetting){
            $fields[] = $apiSetting;
        }
        $fields[] = [
            'type' => 'content',
            'content' => Misc::render_button(__('Verify', 'happy-social-login'), 'hslogin::verify', ['data-provider'=> $this->getID()])
        ];
        $settings[] = [
            'title'  => 'API Settings',
            'id'     => 'api',
            'type'   => 'fieldset',
            'fields' => $fields,
            'dependency' => ['enabled', '==', true]
        ];

        return $settings;
    }

    public function getApiSettings()
    {
        return [
            [
                'id' => 'adapter',
                'type' => 'text',
                'class' => 'hidden',
                'attributes' => ['type' => 'hidden'],
                'default' => $this->getAdapter()
            ],
            [
                'title' => 'Client ID',
                'id' => 'client-id',
                'type' => 'text',
            ],
            [
                'title' => 'Client Secret',
                'id' => 'client-secret',
                'type' => 'text',
            ]
        ];
    }

//    public function getAdapter()
//    {
//        return 'Hybridauth\\Provider\\' . ucfirst($this->getID());
//    }

    public function getConfig($settings)
    {
        return [
            'enabled' => $settings['enabled'],
            'callback' => $this->getCallback(),
            'keys' => [
                'id' => $settings['api']['client-id'],
                'secret' => $settings['api']['client-secret']
            ],
            'debug_mode' => false,
            'debug_file' => ABSPATH . 'wp-content/debug.log',
        ];
    }

    public function getCallback()
    {
        return rtrim(get_site_url(), '/') . '/sso/'. $this->getID();
    }

}

