<?php

use KABBOUCHI\Settings\Settings;

if (! function_exists('setting')) {
    function setting($key = false, $defaultValue = false)
    {
        $setting = app(Settings::class);

        if ($key === false) {
            return $setting;
        }
        $value = $setting->get($key);

        return $value ? $value : $defaultValue;
    }
}

if (! function_exists('setting_storage_url')) {
    function setting_storage_url($key, $defaultValue = false)
    {
        $setting = app(Settings::class);
        $value = $setting->getStorageUrl($key);

        return $value ? $value : $defaultValue;
    }
}
