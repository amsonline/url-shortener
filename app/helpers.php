<?php


use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Setting::getSetting($key, $default);
    }

    function social_enabled($key)
    {
        $rawSocials = Setting::getSetting('social');
        $socials = explode(',', $rawSocials);
        return in_array($key, $socials);
    }
}

if (!function_exists('getProjectVersion')) {
    function getProjectVersion()
    {
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(file_get_contents($composerJsonPath), true);

        return $composerJson['version'] ?? 'unknown';
    }
}
