<?php

/**
 * Write anything to debug.log file E.g hslogin_log("My first log message")
 */
if (!function_exists('hslogin_log')) {
    function hslogin_log($log = 'test') {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r, WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log(print_r($log, true));
            } else {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log($log);
            }
        }
    }
}

/**
 * Trace back from the point
 */
if (!function_exists('hslogin_trace')) {
    function hslogin_trace() {
        if (true === WP_DEBUG) {
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
            error_log(
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
                print_r(
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_wp_debug_backtrace_summary
                    wp_debug_backtrace_summary(null, 0, false), 
                    true
                )
            );
        }
    }
}
