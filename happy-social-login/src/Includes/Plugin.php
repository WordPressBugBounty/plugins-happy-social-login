<?php

namespace HappySocialLogin\Includes;

final class Plugin
{

    /**
     * @var string
     */
    private static string $plugin_file = '';

    /**
     * @var string
     */
    private static string $prefix = '';

    /**
     * @var string
     */
    private static string $path = '';

    /**
     * @var string
     */
    private static string $url = '';

    /**
     * @var Plugin|null
     */
    private static ?Plugin $instance = null;

    /*
     * Plugin constructor.
     */
    private function __construct()
    {
        self::$plugin_file = dirname(__FILE__, 3) . '/happy-social-login.php';
        self::$prefix = preg_replace('/[^a-zA-Z0-9]/', '', basename(dirname(self::$plugin_file)));
        self::$path = plugin_dir_path(self::$plugin_file);
        self::$url = plugin_dir_url(self::$plugin_file);
    }


    /**
     * Unique name for the plugin that can be further
     * used for prefixing settings or anything else
     */
    public function getPrefix(): string
    {
        return self::$prefix;
    }

    /**
     * Return the plugin path
     */
    public function getPath(): string
    {
        return self::$path;
    }

    /**
     * Return the plugin url
     */
    public function getUrl(): string
    {
        return self::$url;
    }

    /**
     * Get the plugin file
     */
    public function getPluginFile(): string
    {
        return self::$plugin_file;
    }

    /**
     * Get the plugin base name
     * e.g happy-social-login/happy-social-login.php
     */
    public function getPluginBaseName(): string
    {
        return plugin_basename(self::$plugin_file);
    }

    /**
     * Get the plugin instance
     */
    public static function getInstance(): ?Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the plugin
     */
    public function initialize(): void
    {
        if(Compatibility::getInstance()->is_compatible()){
            register_activation_hook(self::$plugin_file, [Activator::class, 'activate']);
            Hooks::getInstance()->registerActions();
            Hooks::getInstance()->registerFilters();
        }
        //We need not require to check compatibility while deactivating the plugin
        register_deactivation_hook(self::$plugin_file, [Deactivator::class, 'deactivate']);
        //register_uninstall_hook(self::$plugin_file, [Uninstaller::class, 'uninstall']);
        hslogin_fs()->add_action('after_uninstall', [Uninstaller::class, 'uninstall']);
    }
}