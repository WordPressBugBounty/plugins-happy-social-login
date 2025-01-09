<?php

namespace HappySocialLogin\Includes;

final class Compatibility {

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     * @var string Minimum Elementor version required to run the addon.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.16.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the addon.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    private static ?Compatibility $instance = null;

    public static function getInstance(): ?Compatibility
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Compatibility Checks
     *
     * Checks whether the site meets the addon requirement.
     *
     * @since 1.0.0
     * @access public
     */
    public function is_compatible() {
//        // @toDo only check if Elementor Widget is enabled on Settings
//        // Check if Elementor is installed and activated
//        if ( ! did_action( 'elementor/loaded' ) ) {
//            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
//            return false;
//        }
//
//        // Check for required Elementor version
//        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
//            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
//            return false;
//        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return false;
        }

        return true;

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'happy-social-login' ),
            '<strong>' . esc_html__( 'Happy Social Login', 'happy-social-login' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'happy-social-login' ) . '</strong>'
        );
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-social-login' ),
            '<strong>' . esc_html__( 'Happy Social Login', 'happy-social-login' ) . '</strong>',
            '<strong>' . esc_html__( 'happy-social-login', 'happy-social-login' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'happy-social-login' ),
            '<strong>' . esc_html__( 'Happy Social Login', 'happy-social-login' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'happy-social-login' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

}
