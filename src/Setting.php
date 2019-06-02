<?php namespace KABBOUCHI\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
	use HasTranslations;

	public $translatable = ['value'];

	protected $guarded = [];

	protected $casts = [
		'meta' => 'array'
	];

	public static function byFullKey($key)
	{
		return self::where('full_key', $key)->first();
	}

	protected function asJson($value)
	{
		return json_encode($value, JSON_UNESCAPED_UNICODE);
	}
}