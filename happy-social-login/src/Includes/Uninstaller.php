<?php

namespace HappySocialLogin\Includes;

class Uninstaller{

    public static function uninstall(): void
    {
        $option_name = 'hslogin';

        delete_option( $option_name );

        // for site options in Multisite
        delete_site_option( $option_name );
    }
}