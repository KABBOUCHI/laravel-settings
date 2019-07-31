<?php

namespace KABBOUCHI\Settings\Http\Controllers;

use KABBOUCHI\Settings\Setting;
use KABBOUCHI\Settings\Settings;
use Illuminate\Http\UploadedFile;
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
        ], [], [
            'value.en' => $field->name,
        ]);

        if ($field instanceof File) {
            if (! request()->hasFile('value')) {
                return $setting;
            }

            $files = [];
            /* @var File $field */
            foreach ($data['value'] as $key => $file) {
                if ($file instanceof UploadedFile) {
                    $files[$key] = $field->store($file);
                }
            }

            $data['value'] = array_merge($setting->getTranslations('value') ?? [], $files);
        }

        $data['meta'] = array_merge($data['meta'] ?? [], $field->meta);

        $setting->update($data);

        cache()->forget('laravel-settings');

        return $setting;
    }
}
