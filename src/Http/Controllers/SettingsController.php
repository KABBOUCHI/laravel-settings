<?php


namespace KABBOUCHI\Settings\Http\Controllers;


use Illuminate\Routing\Controller;
use KABBOUCHI\Settings\Setting;

class SettingsController extends controller
{
	public function update($key)
	{
		/** @var Setting $setting */
		$setting = Setting::byFullKey($key);

		return tap($setting)->update(request()->only(['value', 'meta']));
	}
}