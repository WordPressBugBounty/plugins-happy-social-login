<?php
/**
 * Plugin Name: Happy Social Login
 * Description: Enables user authentication through various social media accounts. Login through Google, Facebook, LinkedIn, GitHub and more.
 * Version:     1.5.0
 * Author:      wpfolk
 * Author URI:  https://wpfolk.com
 * license:     GPL-3.0
 * Text Domain: happy-social-login
 *
  */


//=================================================
// Security: Abort if this file is called directly
//=================================================

if ( !defined('ABSPATH') ) {
    die;
}

/*
 * Include the autoload.php file from the vendor directory.
 */
include 'vendor/autoload.php';

/*
 * Include Freemius SDK
 */
include 'freemius.php';

/*
 * Include the debug.php file.
 */
include 'debug.php';

/*
 * Initialize the Plugin here
 */
$pluginInstance = \HappySocialLogin\Includes\Plugin::getInstance();
$pluginInstance->initialize();
