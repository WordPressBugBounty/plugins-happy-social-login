<?php

namespace HappySocialLogin\Hooks;

use HappySocialLogin\Includes\Plugin;
use HappySocialLogin\Providers\ProviderManager;
use HappySocialLogin\Utils\Misc;
use HappySocialLogin\Utils\SocialButtons;

class WordpressFilters
{

    public static function query_vars($query_vars)
    {
        $query_vars[] = 'sso';
        return $query_vars;
    }

    public static function template_include($template)
    {
        if (get_query_var('sso')) {
            $template = Plugin::getInstance()->getPath() . 'src/Authentication/Sso.php';
        }
        return $template;
    }

    public static function logout_url($logout_url, $redirect)
    {
        if(empty($redirect)){

            if(is_user_logged_in()) {
                $settings = Misc::get_plugin_settings();

                if(empty($settings)){
                    return $logout_url;
                }

                $logout_redirection_rules = $settings['logout-redirection-rules'];
                $user = wp_get_current_user();
                $user_role = $user->roles[0];
                $redirect_to = '';

                if (!empty($logout_redirection_rules)) {
                    foreach ($logout_redirection_rules as $rule) {
                        if ($rule['user-role'] === $user_role) {
                            $redirect_to = $rule['redirect-to'];
                            break;
                        }
                    }
                }

                if (empty($redirect_to)) {
                    $redirect_to = home_url();
                }
                $logout_url = add_query_arg(['redirect_to' => urlencode($redirect_to)], $logout_url);
            }

        }

        return $logout_url;
    }

    public static function login_message($message)
    {
        $enabledSocialButtons = ProviderManager::getInstance()->getEnabledProviders();

        if(!empty($enabledSocialButtons)){
            SocialButtons::render_enabled_social_login_buttons();
            echo wp_kses_post('<p>&nbsp;</p>');
            SocialButtons::render_or_element();
            echo wp_kses_post($message);
        }

        return $message;
    }
}