<?php

namespace KABBOUCHI\Settings\Http\Controllers;

use KABBOUCHI\Settings\Setting;
use KABBOUCHI\Settings\Settings;
use Illuminate\Routing\Controller;
use KABBOUCHI\Settings\Fields\File;

class SettingsController extends controller
{
    public function update($key)
    {
        /** @var Setting $setting */
        $setting = Setting::byFullKey($key);

        $field = Settings::getField($key);

        $data = request()->validate([
            $field->translatable() ? 'value.*' : 'value.en' => $field->rules,
            'meta'                                          => [],
        ], [

        ], [
            'value.en' => $field->name,
        ]);

        if ($field instanceof File) {
            /* @var File $field */

            $data['value'] = $field->store();
            $data['meta']['disk'] = $field->disk;
        }

        return tap($setting)->update($data);
    }
}
