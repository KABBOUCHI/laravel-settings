<?php

namespace KABBOUCHI\Settings;

use Closure;
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

	public static function get(string $key)
	{
		/** @var Setting $setting */
		$setting = Setting::where('full_key', $key)->first();

		return optional($setting)->value;
	}

	public static function set(string $key, $value)
	{
		/** @var Setting $setting */
		$setting = Setting::where('full_key', $key)->first();

		if ($setting)
			$setting->update(['value' => $value]);

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
