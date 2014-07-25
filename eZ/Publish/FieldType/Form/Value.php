<?php

namespace MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form;

use eZ\Publish\Core\FieldType\Value as BaseValue;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;
use MakingWaves\FormMakerBundle\Entity\FormTypes;

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
     * @var \MakingWaves\FormMakerBundle\Services\PagesContainer
     */
    public $pagesContainer;


    /**
     * Runs parent constructor and sets the formId for currentPage
     * @param array $properties
     */
    public function __construct( array $properties = array() )
    {
        parent::__construct( $properties );

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFormName();
    }

    /**
     * @return \MakingWaves\FormMakerBundle\Services\PagesContainer
     */
    public function getPagesContainer()
    {
        $this->pagesContainer->setFormId( $this->formId );
        return $this->pagesContainer;
    }

    /**
     * Returns the name of current form, or empty string in case of empty set of data.
     * @return string
     */
    private function getFormName()
    {
        $formName = '';
        if ( $this->pagesContainer->getFormDefinition() instanceof FormDefinitions ) {
            $formName = $this->pagesContainer->getFormDefinition()->getName();
        }

        return $formName;
    }
}