<?php

namespace HappySocialLogin\Authentication;

use HappySocialLogin\Utils\Misc;
use HappySocialLogin\Utils\SocialButtons;

class Sso
{

    private static ?Sso $instance = null;

    public function __construct()
    {
        if (wp_doing_ajax()){
            return;
        }

        if(wp_doing_cron()){
            return;
        }

        if(headers_sent()){
            return;
        }

        $providerId = $this->getProviderId();

        if(!$this->isProviderEnabled($providerId)){
            wp_safe_redirect(home_url());
            exit;
        }

        $this->initHybridauth($providerId);
    }

    public static function getInstance(): ?Sso
    {
        if (self::$instance === null) {
            self::$instance = new Sso();
        }
        return self::$instance;
    }

    public function getProviderId(): string
    {
        $q = get_query_var('sso');
        return sanitize_text_field($q);
    }


    public function isProviderEnabled($providerId): bool
    {
        if(!empty($providerId)){
            $settings = Misc::get_provider_settings($providerId);
            if(isset($settings['enabled']) && $settings['enabled'] == '1'){
                return true;
            }
        }
        return false;
    }

    /*
     * Authenticate user using Hybridauth
     */
    private function initHybridauth($providerId): void
    {
        //Authenticate User using Hybridauth
        $response = Hybridauth::getInstance($providerId)->authenticate();

        if (!empty($response) && is_array($response)) {
            if ($response['success'] === true) {
                $profileData = $response['profileData'];
                $this->is_on_plugin_settings_page()
                    ? $this->handleBackend($profileData)
                    : $this->handleFrontend($profileData);
            } else {
                $errorMsg = $response['error_message'];
                $this->handleError($errorMsg);
            }
        }

    }

    /*
     * Don't log in to WordPress.
     * Just show the output on popup
     */
    private function handleBackend($profileData): void
    {
        //HTML
        ob_start();
        ?>
            <div class="hslogin-heading">SETTINGS VALIDATED ✅</div>
            <div class="hslogin-message">You can expect similar data from the users and store them in your WordPress Database following User Fields settings.</div>
            <table class="hslogin-table">
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($profileData as $key => $value): ?>
                    <tr>
                        <td><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></td>
                        <td>
                            <?php
                            if (is_bool($value)) {
                                echo esc_html($value ? 'true' : 'false');
                            } elseif (is_array($value)) {
                                echo esc_html(!empty($value) ? wp_json_encode($value) : '');
                            } else {
                                echo esc_html($value);
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php
        $html = ob_get_clean();

        //Output HTML
        echo wp_kses_post($html);
    
        //CSS
        ob_start();
        ?>
            .hslogin-heading {
                margin-top: 30px;
                font-size: 24px;
                margin-bottom: 10px;
                color: #050505;
                font-weight: 800;
            }
            .hslogin-message {
                font-size: 16px;
                margin-bottom: 20px;
                color: #666;
            }
            .hslogin-table {
                width: 100%;
                border-collapse: collapse;
                overflow: hidden;
                margin-bottom: 20px;
            }
            .hslogin-table th,
            .hslogin-table td {
                padding: 12px;
                text-align: left;
                border: 1px solid #8b86863b;
            }
            .hslogin-table thead {
                background-color: #050505;
                color: #fff;
                font-weight: bold;
            }
            .hslogin-table tbody tr td:nth-child(odd) {
                background-color: #222;
                color: #999;
                font-size: 12px;
                font-family: monospace;
            }
            .hslogin-table tbody tr:hover {
                background-color: #ebebeb9e;
            }
        <?php
        $css = ob_get_clean();

        //JS
        ob_start();
        ?>
            const style = `<?php echo esc_html($css); ?>`;
            const styleElement = document.createElement("style");
            styleElement.appendChild(document.createTextNode(style));
            document.head.appendChild(styleElement);
            document.cookie = "hslogin_referer=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
        <?php
        $js = ob_get_clean();
    
        //Output JS
        wp_print_inline_script_tag($js);
    }

    /*
     * Check if user is on Plugin Settings Page
     */
    private function is_on_plugin_settings_page(): bool
    {
        if (isset($_COOKIE['hslogin_referer'])) {
            $referer = sanitize_text_field(wp_unslash($_COOKIE['hslogin_referer']));
            if (str_contains($referer, 'admin.php?page=hslogin')) {
                if (current_user_can('manage_options')) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     * Login to WordPress
     * Use Social Login User Profile Data
     */
    private function handleFrontend($profileData): void
    {
        //Login to WordPress
        $response = Wpauth::getInstance($profileData)->wp_login();

        if($response['success'] === true){
            $redirect_to = isset($response['redirect_to']) ? $response['redirect_to'] : site_url();
            
            //JS
            ob_start();
            ?>  
                let redirect_to = "<?php echo esc_js($redirect_to) ?>";
                localStorage.setItem('hsloginResponse', JSON.stringify({redirect_to : redirect_to}));
                if(window.opener === null){
                    localStorage.removeItem('hsloginResponse');
                    window.location.href = redirect_to;
                }
                document.cookie = `hslogin_referer=""; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
                window.close();
            <?php

            $js = ob_get_clean();
            wp_print_inline_script_tag($js);
        } else {
            $errMsg = $response['error_message'];
            $this->handleError($errMsg);
        }
    }

    /*
     * Handle Error
     */
    private function handleError($msg): void
    {
        Misc::displayErrorAndExit($msg);
    }
}

Sso::getInstance();
