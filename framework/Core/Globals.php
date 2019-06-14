<?php


if (!function_exists('config')) {
    function config($configpath, $default = null)
    {
        $keyPath = (explode('.', $configpath));
        $configFilePath = CONFIG_PATH . $keyPath[0] . '.php';
        if (!file_exists($configFilePath)) {
            return $default;
        }
        unset($keyPath[0]);
        $file = require $configFilePath;

        foreach ($keyPath as $key) {
            if (!array_key_exists($key, $file)) {
                return $default;
            }
            $file = $file[$key];
        }

        return $file;
    }
}
