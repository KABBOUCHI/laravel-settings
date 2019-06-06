<?php

namespace KABBOUCHI\Settings\Fields;

use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

class Group implements JsonSerializable
{
    use ConditionallyLoadsAttributes;

    protected $name;
    protected $id = null;
    protected $fields;
    protected $key = null;
    /**
     * @var bool
     */
    protected $resolveFields = false;

    public function __construct($name, $fields = [])
    {
        $this->name = $name;
        $this->id = str_replace(' ', '_', Str::lower($name));
        $this->fields = $fields;
    }

    /**
     * Create a new panel.
     *
     * @param array $arguments
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * @param $key
     * @return Group
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param $group
     * @return Group
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    public function jsonSerialize()
    {
        $panel = $this;
        $fields = call_user_func($this->fields, request());

        return [
            'id'     => $this->id(),
            'key'    => $this->key,
            'name'   => $this->name,
            'fields' => $this->when($this->resolveFields, collect($fields)->map(function (Field $field) use ($panel) {
                return $field->setPanel($panel);
            }), []),
        ];
    }

    public function id()
    {
        return $this->id;
    }

    public function key()
    {
        return $this->key;
    }

	public function fields()
	{
		return $this->fields;
	}

    public function resolveFields()
    {
        $this->resolveFields = true;

        return $this;
    }
}
