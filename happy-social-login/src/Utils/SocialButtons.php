<?php

namespace HappySocialLogin\Utils;

use HappySocialLogin\Providers\ProviderManager;

class SocialButtons {

    /*
     * Get the icon html
     *
     * @param string $id Provider Id
     *
     * @param array $icon
     *
     * $icon['library'] = 'hslogin' | 'fa-solid' | 'fa-brands' | 'fa-regular'
     * $icon['value'] = 'hslogin hslogin-google' | 'fab fa-google'
     *
     * @return string <i></i> | <svg></svg> | ''
     */
    public static function get_provider_icon($id, $icon) : string
    {
        $library = $icon['library'];

        if($library === 'hslogin'){
            $provider = ProviderManager::getInstance()->getProvider($id);
            return $provider->getIcons()['svg'];
        }
        
        if($library === 'fa-solid' || $library === 'fa-brands' || $library === 'fa-regular' || $library === 'svg'){
            if(did_action( 'elementor/loaded' )){
                ob_start();
                \Elementor\Icons_Manager::render_icon( 
                    $icon, 
                    [ 
                        'aria-hidden' => 'true',
                        'class' => 'hslogin-icon'
                    ]);
                return ob_get_clean();
            }
        }

        return '';
    }

    /*
     * Print the login buttons html
     *
     * @param array $buttons
     *
     * $buttons = [
     *     0 => [
     *         'provider' => 'github',
     *         'label' => 'Continue with Github',
     *         'icon' => [
     *             'value' => 'fab fa-github',
     *             'library' => 'fa-solid',
     *         ],
     *     ],
     *     1 => [
     *         'provider' => 'google',
     *         'label' => 'Continue with Google',
     *         'icon' => [
     *             'value' => 'fab fa-google',
     *             'library' => 'fa-solid',
     *         ],
     *     ],
     * ]
     */
    public static function render_social_login_buttons_html($buttons) : void
    {
        // Check if buttons are set and not empty
        if ( !empty( $buttons ) ) {
            ?>
            <div class="hslogin-buttons-group">
                <?php foreach ( $buttons as $button ) :
                    $provider = $button['provider'];
                    $icon = $button['icon'];
                    $icon_html = SocialButtons::get_provider_icon($provider, $icon);
                    $label = $button['label'];
                    $id = $button['provider'];
                    if ( isset( $provider, $icon, $label ) ) : ?>
                        <div id="<?php echo esc_attr('hslogin-'.$provider)?>" class="hslogin-button" role="button" tabindex="0" data-provider=<?php echo esc_attr($provider)?> onclick="launchAuthWindow('<?php echo esc_attr($provider)?>')">
                            <div class="hslogin-icon-wrapper">
                                <?php Misc::print_sanitized_svg($icon_html); ?>
                            </div>
                            <div class="hslogin-label-wrapper">
                                <span class="hslogin-label"><?php echo esc_html($label); ?> </span>
                            </div>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
            <?php
        }
    }

    public static function enque_social_login_buttons_assets()
    {
        global $hslogin_assets_rendered; //prevent double rendering of assets
        $hslogin_assets_rendered = isset($hslogin_assets_rendered) && $hslogin_assets_rendered;
        if (!$hslogin_assets_rendered){
            wp_enqueue_style('hslogin-social-login-buttons-style');
            wp_enqueue_script('hslogin-social-login-buttons-script');
            $hslogin_assets_rendered = true;
        }
    }

    public static function render_enabled_social_login_buttons(): void
    {
        $enabled_providers = ProviderManager::getInstance()->getEnabledProviders();

        if(!empty($enabled_providers)){
            $buttons = [];
            foreach ($enabled_providers as $provider) {
                $buttons[] = [
                    'provider' => $provider->getID(),
                    'label' => __('Continue with ', 'happy-social-login') . $provider->getLabel(),
                    'icon' => [
                        'value' => '',
                        'library' => 'hslogin',
                    ]
                ];
            }

            self::enque_social_login_buttons_assets();
            self::render_social_login_buttons_html($buttons);
        }
    }

    public static function render_or_element(): void
    {
        $html = '<div class="hslogin-line-wrapper"><span class="hslogin-line"></span> <span class="hslogin-or">OR</span> <span class="hslogin-line"></span></div>';
        echo wp_kses_post($html);
    }
}
