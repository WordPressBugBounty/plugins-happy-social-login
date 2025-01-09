<?php

namespace HappySocialLogin\Providers;

use HappySocialLogin\Includes\Plugin;
use HappySocialLogin\Utils\Misc;

class ProviderManager
{
    private array $providers = [];

    private static ?ProviderManager $instance = null;

    public static function getInstance(): ?ProviderManager
    {
        if (self::$instance === null) {
            self::$instance = new ProviderManager();
        }
        return self::$instance;
    }

    public function getProviders(): array
    {
        $providersDir = [
            'Free' => Plugin::getInstance()->getPath() . 'src/Providers/Free',
            'Premium' => Plugin::getInstance()->getPath() . 'src/Providers/Premium'
        ];

        // Instantiate all the providers
        foreach ($providersDir as $type => $dir) {
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file) {
                    // Skip directories starting with . & ..
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    // Skip files that do not end with '.php'
                    if (!preg_match('/\.php$/', $file)) {
                        continue;
                    }
                    $providerClass = 'HappySocialLogin\\Providers\\' . $type .'\\'. str_replace('.php', '', $file);
                    $provider = new $providerClass();
                    if ($provider instanceof ProviderBase) {
                        $this->providers[$provider->getID()] = $provider;
                    }
                }
            }
        }

        return $this->providers;
    }


    public function getProvider($id)
    {
        $providers = $this->getProviders();
        return $providers[$id] ?? null;
    }

    public function getEnabledProviders(): array
    {
        $enabledProviders = [];

        $settings = Misc::get_plugin_settings();

        $providers = $this->getProviders();

        foreach ($providers as $provider) {
            $id = $provider->getID();
            if (isset($settings[$id]['enabled']) && $settings[$id]['enabled'] == "1") {
                $enabledProviders[$id] = $provider;
            }
        }
        return $enabledProviders;
    }

    public function getProvidersList(): array
    {
        $providersList = [];
        $providers = $this->getProviders();
        foreach ($providers as $provider){
            $providersList[$provider->getID()] = $provider->getLabel();
        }
        return $providersList;
    }

    public function getEnabledProvidersList(): array
    {
        $enabledProviders = [];

        $settings = Misc::get_plugin_settings();

        $providers = $this->getProviders();
        foreach ($providers as $provider) {
            $id = $provider->getID();
            if (isset($settings[$id]) && isset($settings[$id]['enabled']) && $settings[$id]['enabled'] == "1") {
                $enabledProviders[$id] = $provider->getLabel();
            }
        }
        return $enabledProviders;
    }
}
