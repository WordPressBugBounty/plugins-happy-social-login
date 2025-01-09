<?php

namespace HappySocialLogin\Settings;
use CSF;
use HappySocialLogin\Providers\ProviderManager;

CSF::createSection($this->prefix,[
    'title' => 'Providers',
    'id'    => 'providers-tab',
    'icon' => 'fas fa-network-wired',
]);

$providerManager = ProviderManager::getInstance();
$providers = $providerManager->getProviders();

foreach ($providers as $provider) {
    CSF::createSection($this->prefix, [
        'title' => $provider->getLabel(),
        'parent' => 'providers-tab',
        'icon' => $provider->getIcons()['fontawesome'],
        'fields' => [
            [
                'id' => $provider->getId(),
                'type' => 'fieldset',
                'class' => 'no-border',
                'fields' => $provider->getSettings(),
                'default' => [
                    'enabled' => false,
                ],
            ]
        ],
    ]);
}





