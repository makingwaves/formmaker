<?php

namespace MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{
    protected $formId;

    public function __toString()
    {
        return 'some value from Value';
    }
}