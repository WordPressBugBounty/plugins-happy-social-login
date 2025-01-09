<?php

namespace HappySocialLogin\Includes;

class Deactivator{

    public static function deactivate(): void
    {
        hslogin_log('Happy Social Login Deactivated');
        flush_rewrite_rules();
    }
}