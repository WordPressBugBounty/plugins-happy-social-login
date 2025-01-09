<?php

namespace HappySocialLogin\Authentication;

use HappySocialLogin\Hybridauth\Storage\MemoryStorage;
use HappySocialLogin\Providers\ProviderManager;
use HappySocialLogin\Utils\Misc;
use Hybridauth\Exception\Exception;

class Hybridauth {

    private array $settings;

    private array $config;

    private MemoryStorage $storage;

    private static ?Hybridauth $instance = null;

    public static function getInstance($id): ?Hybridauth
    {
        if (self::$instance === null) {
            self::$instance = new Hybridauth($id);
        }
        return self::$instance;
    }

    private function __construct($id)
    {
        $this->settings = Misc::get_provider_settings($id);
        $provider = ProviderManager::getInstance()->getProvider($id);
        $this->config = $provider->getConfig($this->settings);
        $this->storage = new MemoryStorage();
    }

    public function getAdapter(): \Hybridauth\Adapter\AdapterInterface
    {
        $adapterClass = $this->settings['api']['adapter'];
        
        if (!class_exists($adapterClass)) {
            Misc::displayErrorAndExit('Adapter class ' . $adapterClass . ' not found');
        }

        try {
            $adapter =  new $adapterClass($this->config, null, $this->storage, null);
        } catch (\Exception $e) {
            Misc::displayErrorAndExit($e->getMessage());
        }
        
        return $adapter;
    }

    /*
     * @return array $response
     * @var $adapter \Hybridauth\Adapter\AdapterInterface
     */
    public function authenticate(): array
    {
        try {
            $adapter = $this->getAdapter();

            $adapter->authenticate();

            if($adapter->isConnected())
            {
                $profileData = $adapter->getUserProfile();
                $accessToken = $adapter->getAccessToken();
                return [
                    'success' => true,
                    'profileData' => (array) $profileData,
                    'access_token' => $accessToken
                ];
            }
            else
            {
                return [
                    'success' => false,
                    'error_message' => __('Authentication failed', 'happy-social-login')
                ];
            }
        }
        catch (Exception $e)
        {
            return [
                'success' => false,
                'error_message' => $e->getMessage()
            ];
        }
    }
}
