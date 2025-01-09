<?php

if ( !function_exists( 'hslogin_fs' ) ) {
    // Create a helper function for easy SDK access.
    function hslogin_fs() {
        global $hslogin_fs;
        if ( !isset( $hslogin_fs ) ) {
            // Include Freemius SDK.
            require_once 'vendor/freemius/wordpress-sdk/start.php';
            $hslogin_fs = fs_dynamic_init( array(
                'id'             => '15590',
                'slug'           => 'happy-social-login',
                'premium_slug'   => 'happy-social-login-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_c982f5e7ca939eb29447c3ab2e2f5',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                    'slug'       => 'hslogin',
                    'first-path' => 'admin.php?page=hslogin',
                ),
                'is_live'        => true,
            ) );
        }
        return $hslogin_fs;
    }

    // Init Freemius.
    hslogin_fs();
    // Signal that SDK was initiated.
    do_action( 'hslogin_fs_loaded' );
}