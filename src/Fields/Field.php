<?php

namespace KABBOUCHI\Settings\Fields;

use Illuminate\Support\Str;
use KABBOUCHI\Settings\Setting;

abstract class Field extends Element
{
    /**
     * The custom components registered for fields.
     *
     * @var array
     */
    public static $customComponents = [];
    /**
     * The displayable name of the field.
     *
     * @var string
     */
    public $name;
    /**
     * The key of the field.
     *
     * @var string
     */
    public $key;
    /**
     * The field's resolved value.
     *
     * @var mixed
     */
    public $value;
    /**
     * The validation rules for creation and updates.
     *
     * @var array
     */
    public $rules = [];
    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'left';
    /** @var Group */
    public $panel;
    /**
     * @var bool
     */
    protected $translatable = false;

    /**
     * Create a new field.
     *
     * @param string $name
     * @param null $key
     */
    public function __construct($name, $key = null)
    {
        $this->name = $name;
        $this->key = $key ?? str_replace(' ', '_', Str::lower($name));
    }

    /**
     * Set the help text for the field.
     *
     * @param string $helpText
     * @return $this
     */
    public function help($helpText)
    {
        return $this->withMeta(['helpText' => $helpText]);
    }

    /**
     * Set the validation rules for the field.
     *
     * @param \Closure|array $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = is_string($rules) ? func_get_args() : $rules;

        return $this;
    }

    public function setPanel($panel)
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $panelKey = $this->panelKey();

        $fullKey = $this->fullKey();

        /** @var Setting $setting */
        $setting = Setting::query()->firstOrCreate([
            'full_key'  => $fullKey,
            'group_key' => $panelKey,
            'key'       => $this->key,
        ]);

        return [
            'group'        => $panelKey,
            'component'    => $this->component(),
            'translatable' => $this->translatable,
            'name'         => $this->name,
            'key'          => $this->key,
            'full_key'     => $fullKey,
            'value'        => $setting->getTranslations('value'),
            'meta'         => array_merge($this->meta(), $setting->meta ?? []),
        ];
    }

    public function panelKey()
    {
        return $this->panel ? $this->panel->key() : null;
    }

    public function fullKey()
    {
        $panelKey = $this->panelKey();

        return $panelKey ? "{$panelKey}.{$this->key}" : $this->key;
    }

    public function setTranslatable(bool $translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function translatable()
    {
        return $this->translatable;
    }
}
