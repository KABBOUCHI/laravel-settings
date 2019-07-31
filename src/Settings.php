<?php

namespace KABBOUCHI\Settings;

use Closure;
use KABBOUCHI\Settings\Fields\Field;
use KABBOUCHI\Settings\Fields\Group;
use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    public static $fieldsCallback = null;
    public static $runsMigrations = true;
    public static $authUsing;
    public static $languages = null;

    public static function fields(Closure $fieldsCallback)
    {
        static::$fieldsCallback = $fieldsCallback;
    }

    public static function all()
    {
        if ($cacheDuration = config('laravel-settings.cache')) {
            return cache()->remember('laravel-settings', $cacheDuration, function () {
                return Setting::all();
            });
        }

        return Setting::all();
    }

    public static function get(string $key)
    {
        /** @var Setting $setting */
        $setting = self::all()->whereFirst('full_key', $key);

        return optional($setting)->value;
    }

    public static function set(string $key, $value)
    {
        /** @var Setting $setting */
        $setting = Setting::all()->whereFirst('full_key', $key);

        if ($setting) {
            $setting->update(['value' => $value]);
        }

        cache()->forgot('laravel-settings');

        return optional($setting)->value;
    }

    /**
     * Determine if the given request can access the Horizon dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate Horizon users.
     *
     * @param \Closure $callback
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    public static function languages(Closure $callback)
    {
        static::$languages = $callback;

        return new static;
    }

    public static function getField($fullKey): Field
    {
        $groups = call_user_func(self::$fieldsCallback, request());

        return collect($groups)
            ->flatMap(function (Group $group) {
                return  collect(call_user_func($group->fields(), request()))->map(function (Field $field) use ($group) {
                    $field->setPanel($group);

                    return $field;
                });
            })
            ->flatten()
            ->first(function (Field $field) use ($fullKey) {
                return $field->fullKey() === $fullKey;
            });
    }

    public static function getGroup($group): Group
    {
        $groups = call_user_func(self::$fieldsCallback, request());

        return collect($groups)->first(function (Group $g) use ($group) {
            return $g->id() === $group;
        });
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-settings';
    }
}
