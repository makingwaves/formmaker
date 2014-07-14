<?php

namespace MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form;

use eZ\Publish\Core\FieldType\Value as BaseValue;

/**
 * Class Value
 * @package MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form
 */
class Value extends BaseValue
{
    public $formId;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->formId;
    }
}