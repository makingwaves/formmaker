<?php

namespace MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form;

use eZ\Publish\Core\FieldType\Value as BaseValue;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;

/**
 * Class Value
 * @package MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form
 */
class Value extends BaseValue
{
    /**
     * @var int
     */
    public $formId;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormDefinitions
     */
    public $formDefinition;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFormName();
    }

    /**
     * Returns the name of current form, or empty string in case of empty set of data.
     * @return string
     */
    private function getFormName()
    {
        $formName = '';
        if ($this->formDefinition instanceof FormDefinitions) {
            $formName = $this->formDefinition->getName();
        }

        return $formName;
    }

    /**
     * @return FormDefinitions
     */
    public function getFormDefinition()
    {
        return $this->formDefinition;
    }
}