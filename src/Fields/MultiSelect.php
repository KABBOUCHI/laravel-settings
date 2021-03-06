<?php

namespace KABBOUCHI\Settings\Fields;

class MultiSelect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'multi-select-field';

    public function options(array $options)
    {
        $this->withMeta([
            'options' => $options,
        ]);

        return $this;
    }
}
