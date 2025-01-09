<?php

namespace HappySocialLogin\Utils;

class User {
    /**
     * Generate Password Reset Link
     * @param $user_id
     * @return string
     */
    public static function generate_password_reset_link($user_id){
        $user = get_userdata($user_id);
        $key = get_password_reset_key($user);
        $locale = get_user_locale($user);
        $user_login = $user->user_login;
        $url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale . "\r\n\r\n";
        return $url;
    }


    /**
     * Generate Random Password and Update it in User's Profile
     * @param $user_id
     * @return string
     */
    public static function get_user_password($user_id){
        //Since password are not stored in plain text, we can't get it directly
        //So we will generate a random password and update it in the user's profile
        $password = wp_generate_password(12, false);
        wp_set_password($password, $user_id);
        return $password;
    }


    /**
     * Replace User related Shortcodes in the message
     * @param $message
     * @param $user_id
     * @return string
     */
    public static function replace_user_shortcodes($message, $user_id){
        $user = get_userdata($user_id);

        return strtr( $message, [
            '[username]' => $user->user_login,
            '[password]' => User::get_user_password($user_id),
            '[user_email]' => $user->user_email,
            '[user_firstname]' => $user->first_name,
            '[user_lastname]' => $user->last_name,
            '[user_displayname]' => $user->display_name,
            '[user_nicename]' => $user->user_nicename,
            '[user_id]' => $user->ID,
            '[user_url]' => $user->user_url,
            '[password_reset_link]' => User::generate_password_reset_link($user_id)
        ]);
    }
}
