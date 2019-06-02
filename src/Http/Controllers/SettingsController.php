<?php

namespace KABBOUCHI\Settings\Http\Controllers;

use KABBOUCHI\Settings\Setting;
use Illuminate\Routing\Controller;

class SettingsController extends controller
{
    public function update($key)
    {
        /** @var Setting $setting */
        $setting = Setting::byFullKey($key);

        return tap($setting)->update(request()->only(['value', 'meta']));
    }
}
