<?php

namespace KABBOUCHI\Settings\Fields;

class Select extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-field';

    protected $translatable = false;

    public function options(array $options)
    {
        $this->withMeta([
            'options' => $options,
        ]);

        return $this;
    }
}
