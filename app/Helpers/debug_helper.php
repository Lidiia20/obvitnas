<?php

if (!function_exists('debugLog')) {
    function debugLog($message, $level = 'error')
    {
        $levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

        if (!in_array($level, $levels)) {
            $level = 'error';
        }

        log_message($level, '[DEBUG] ' . $message);
    }
}
