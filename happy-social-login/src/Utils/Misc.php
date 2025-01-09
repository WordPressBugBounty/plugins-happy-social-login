<?php

namespace HappySocialLogin\Utils;

use HappySocialLogin\Includes\Plugin;
use enshrined\svgSanitize\Sanitizer;

class Misc {

    /**
     * Check if the plugin is being deactivated
     * @return bool
     */
    public static function is_being_deactivated(): bool {
        // Ensure the required parameters exist
        if (isset($_GET['action'], $_GET['plugin'], $_GET['_wpnonce'])) {
            // Unsalsh and sanitize inputs
            $action = sanitize_text_field(wp_unslash($_GET['action']));
            $plugin = sanitize_text_field(wp_unslash($_GET['plugin']));
            $nonce  = sanitize_text_field(wp_unslash($_GET['_wpnonce']));

            // Check if the current request is for the deactivation of this plugin
            if ($action === 'deactivate' && $plugin === Plugin::getInstance()->getPluginBaseName()) {
                // Verify the nonce for deactivation
                return wp_verify_nonce($nonce, 'deactivate-plugin_' . $plugin);
            }
        }

        return false;
    }


    public static function print_sanitized_svg(string $dirty_svg): void {
        $sanitizer = new Sanitizer();
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->minify(true);
        $sanitized_svg = $sanitizer->sanitize($dirty_svg);
        if($sanitized_svg !== false){
            echo wp_kses_post($sanitized_svg); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Render a button on settings page.
     * Mostly used to render "Verify" button for API settings.
     *
     * @param string $label
     * @param string $eventSpaceName
     * @param array $data
     * @return string
     */
    public static function render_button(string $label, string $eventSpaceName = '', array $data = []): string
    {
        ob_start();
        ?>
        <button type="button"
                data-event="<?php echo esc_attr($eventSpaceName); ?>"
            <?php foreach ($data as $key => $value): ?>
                <?php echo esc_attr($key) . '="' . esc_attr($value) . '" '; ?>
            <?php endforeach; ?>
                onclick="document.dispatchEvent(new CustomEvent('<?php echo esc_js($eventSpaceName); ?>', { detail: { el: this } }))"
                style="padding: 8px 20px; background-color: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            <?php echo esc_html($label); ?>
        </button>
        <?php

        return ob_get_clean();
    }

    /*
     * Get plugin settings which was stored using codestar framework
     * @return array
     */
    public static function get_plugin_settings() : array
    {
        $options = get_option('hslogin', []);

        return (array) $options;
    }

    /*
     * Get provider specific settings which was stored using codestar framework
     *
     * @param string $id Provider's ID
     * @return array
     */
    public static function get_provider_settings($id)
    {
        $settings = self::get_plugin_settings();
        if(!empty($settings) && isset($settings[$id])){
            return $settings[$id];
        }
        wp_die('Provider settings not found');
    }

    /*
     * Display error message and exit
     * @param string $msg
     * @param string $heading
     * @param string $title
     * @param string $css
     * @param string $script
     */
    public static function displayErrorAndExit($msg): void
    {
        // Prepare the message, heading, and title with default values and escaping
        $msg = $msg ?? 'An unexpected error occurred. Please try again later.';

        //HTML
        ob_start();
        ?>
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title><?php echo esc_html__('Error', 'happy-social-login'); ?></title>
                </head>
                <body>
                    <div class="error-box">
                        <h2><?php echo esc_html__('Oops! Something went wrong', 'happy-social-login'); ?></h2>
                        <p><?php echo esc_html($msg); ?></p>
                    </div>
                </body>
            </html>
        <?php
        $html = ob_get_clean();

        // Output HTML
        echo wp_kses_post($html);

        //CSS
        ob_start();
        ?>
            body {
                font-family: Arial, sans-serif;
                background-color: white;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .error-box {
                display: flex;
                flex-direction: column;
                justify-content: center;
                text-align: center;
                min-height: 250px;
                max-width: 400px;
                background-color: #fff2fbb0;
                border: 2px solid #b5096fb3;
                border-radius: 5px;
                padding: 20px;
                margin: 20px;
                box-shadow: -15px 16px 16px 2px rgba(0, 0, 0, 0.1);
            }
            .error-box h2 {
                color: #e51390;
                margin-top: 0;
                font-weight: 500;
            }
            .error-box p {
                color: #333;
                margin-bottom: 0;
                line-height: 25px;
            }
            .error-box a {
                color: #3498db;
                text-decoration: none;
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
            document.cookie = `hslogin_referer=""; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
        <?php
        $js = ob_get_clean();

        // Output JS
        wp_print_inline_script_tag($js);

        exit;
    }

}
