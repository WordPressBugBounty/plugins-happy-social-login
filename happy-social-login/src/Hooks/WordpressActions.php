<?php

namespace HappySocialLogin\Hooks;

use HappySocialLogin\Settings\Settings;
use HappySocialLogin\Utils\Misc;
use HappySocialLogin\Utils\User;
use HappySocialLogin\Includes\Plugin;

class WordpressActions
{
    public static function plugins_loaded(): void
    {
        is_admin() && current_user_can('manage_options') && Settings::getInstance()->register();
    }

    public static function init(): void
    {
        // Add rewrite rules on every request except when the plugin is being deactivated
        if(!Misc::is_being_deactivated()){
            add_rewrite_rule('^sso/([^/]+)/?', 'index.php?sso=$matches[1]', 'top');
        }
    }

    public static function wp_loaded(){
        //auth-popup.js
        wp_register_script(
            'hslogin-social-login-buttons-script', 
            Plugin::getInstance()->getUrl() . 'assets/js/public/social-login-buttons.js',
            [],
            false,
            true
        );

        //login-buttons.css
        wp_register_style(
            'hslogin-social-login-buttons-style',
            Plugin::getInstance()->getUrl() . 'assets/css/public/social-login-buttons.css',
            [],
            false
        );
    }

    /**
     * Send Email to User and Admin on successful User Registration
     */
    public static function user_register($user_id, $userdata): void
    {
        $settings = Misc::get_plugin_settings();

        if(empty($settings)){
            return;
        }

        //Notify to user
        if($settings['user-notification']['enable'] == '1'){
            $subject = $settings['user-notification']['subject'];
            if($settings['user-notification']['type'] === 'plain'){
                $message = $settings['user-notification']['plain-message'];
                $headers = 'Content-Type: text/plain; charset=UTF-8';
            }
            else if($settings['user-notification']['type'] === 'html'){
                $message = $settings['user-notification']['html-message'];
                $headers = 'Content-Type: text/html; charset=UTF-8';
            }
            else{
                $message = "You have successfully registered on our website";
                $headers = 'Content-Type: text/plain; charset=UTF-8';
            }
            $message = User::replace_user_shortcodes($message, $user_id);
            $to = $userdata['user_email'];
            wp_mail($to, $subject, $message, $headers);
        }

        // Notify to admin
        if($settings['admin-notification']['enable'] == '1'){
            $subject = $settings['admin-notification']['subject'];
            if ($settings['admin-notification']['type'] === 'plain') {
                $message = $settings['admin-notification']['plain-message'];
                $headers = 'Content-Type: text/plain; charset=UTF-8';
            } else if ($settings['admin-notification']['type'] === 'html') {
                $message = $settings['admin-notification']['html-message'];
                $headers = 'Content-Type: text/html; charset=UTF-8';
            } else {
                $message = "A new user has registered on your website";
                $headers = 'Content-Type: text/plain; charset=UTF-8';
            }
            $message = User::replace_user_shortcodes($message, $user_id);
            $to = $settings['admin-notification']['to'];
            $to_emails = explode(',', $to);
            foreach ($to_emails as $email) {
                $email = trim($email);
                wp_mail($email, $subject, $message, $headers);
            }
        }
    }

    /**
     * Display User Meta Data in User Profile Page
     */
    public static function usermeta_list( $profileuser ): void
    {
        ?>
            <h3><?php echo esc_html__( 'User Meta', 'happy-social-login' ); ?></h3>
            <table class="form-table">
                <tbody>
                    <?php
                        // Fetch and display each user meta item
                        $items = get_user_meta( $profileuser->ID );
                        foreach ( $items as $key => $item ) {
                            ?>
                                <tr>
                                    <th><?php echo esc_html( $key ); ?></th>
                                    <td><input type="text" value="<?php echo esc_attr( array_shift( $item ) ); ?>" readonly="readonly" class="regular-text" /></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        <?php
    }

}