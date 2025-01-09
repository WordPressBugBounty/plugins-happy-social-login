<?php

namespace HappySocialLogin\Providers\Free;

use HappySocialLogin\Providers\ProviderBase;

class X extends ProviderBase
{
    public function getID()
    {
        return 'x';
    }

    public function getLabel()
    {
        return 'X (Twitter)';
    }

    public function getIcons()
    {
        return [
            'fontawesome' => 'fab fa-twitter',
            //https://www.iconfinder.com/icons/11053970/x_logo_twitter_new_brand_x.com_social_icon
            'svg' => '<?xml version="1.0" ?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="24px" height="24px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve"><path d="M14.095479,10.316482L22.286354,1h-1.940718l-7.115352,8.087682L7.551414,1H1l8.589488,12.231093L1,23h1.940717  l7.509372-8.542861L16.448587,23H23L14.095479,10.316482z M11.436522,13.338465l-0.871624-1.218704l-6.924311-9.68815h2.981339  l5.58978,7.82155l0.867949,1.218704l7.26506,10.166271h-2.981339L11.436522,13.338465z"/></svg>'
        ];
    }

    public function getApiSettings(){
        return [
            [
               'title' => 'API Version',
                'id' => 'adapter',
                'type' => 'radio',
                'options' =>[
                    'Hybridauth\Provider\Twitter' => 'OAuth 1.0',
                    'HappySocialLogin\Hybridauth\Provider\X' => 'Oauth 2.0',
                ],
                'default' => 'HappySocialLogin\Hybridauth\Provider\X',
                'inline' => true
            ],
            [
                'title' => 'Api key',
                'id' => 'api-key',
                'type' => 'text',
                'dependency' => ['adapter', '==', 'Hybridauth\Provider\Twitter']
            ],
            [
                'title' => 'Api Secret',
                'id' => 'api-secret',
                'type' => 'text',
                'dependency' => ['adapter', '==', 'Hybridauth\Provider\Twitter']
            ],
            [
                'title' => 'Client ID',
                'id' => 'client-id',
                'type' => 'text',
                'dependency' => ['adapter', '==', 'HappySocialLogin\Hybridauth\Provider\X']
            ],
            [
                'title' => 'Client Secret',
                'id' => 'client-secret',
                'type' => 'text',
                'dependency' => ['adapter', '==', 'HappySocialLogin\Hybridauth\Provider\X']
            ]
        ];
    }

    public function getConfig($settings)
    {
        return parent::getConfig($settings) + [
            'keys' => [
                'id' => $settings['api']['adapter'] === 'Hybridauth\Provider\X' ? $settings['api']['api-key'] : $settings['api']['client-id'],
                'secret' => $settings['api']['adapter'] === 'Hybridauth\Provider\X' ? $settings['api']['api-secret'] : $settings['api']['client-secret'],
            ]
        ];
    }
}