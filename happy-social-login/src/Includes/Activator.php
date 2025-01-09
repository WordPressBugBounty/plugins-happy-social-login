<?php

namespace HappySocialLogin\Includes;

class Activator{

    /*
     * Activating Plugin
     */
    public static function activate(): void
    {
        // Add rewrite rule for Sso
        hslogin_log('Happy Social Login Activated');
        add_rewrite_rule('^sso/([^/]+)/?', 'index.php?sso=$matches[1]', 'top');
        flush_rewrite_rules();
    }
}