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
     * @var \MakingWaves\FormMakerBundle\Entity\FormDefinitions
     */
    private $formDefinition;

    /**
     * @var
     */
    public $doctrine;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormAttributes
     */
    private $formAttributes;

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
        if ( $this->getFormDefinition() instanceof FormDefinitions ) {
            $formName = $this->getFormDefinition()->getName();
        }

        return $formName;
    }

    /**
     * Returns the formDefinition object fot current form
     * @return FormDefinitions
     */
    public function getFormDefinition()
    {
        if ( is_null( $this->formDefinition ) ) {

            $formDefinitions = $this->doctrine->getRepository( 'FormMakerBundle:FormDefinitions' );
            $this->formDefinition = is_null( $this->formId ) ? null : $formDefinitions->find( $this->formId );
        }

        return $this->formDefinition;
    }

    /**
     * Return a set of form attributes
     * @return \MakingWaves\FormMakerBundle\Entity\FormAttributes
     */
    private function getFormAttributes()
    {
        if ( is_null( $this->formAttributes ) ) {

            $this->formAttributes = $this->doctrine->getRepository( 'FormMakerBundle:FormAttributes' )->findBy( array(
                'definition' => $this->getFormDefinition()->getId(),
            ), array(
                'attrOrder' => 'ASC'
            ) );
        }

        return $this->formAttributes;
    }

    /**
     * Returns a set of attributes that are typed as page separators
     * @param bool $onlyEnabled
     * @return array
     */
    private function getPageSeparators( $onlyEnabled = true )
    {
        $pageSeparators = array();

        foreach( $this->getFormAttributes() as $attribute ) {

            if ( $attribute->getType()->getStringId() !== FormTypes::SEPARATOR_ID ) {
                continue;
            }

            if ( !( $onlyEnabled ) || ( $onlyEnabled && $attribute->getEnabled() ) ) {
                $pageSeparators[] = $attribute;
            }
        }

        return $pageSeparators;
    }

    /**
     * Returns an array containing all pages with their labels
     * @return array
     */
    public function getBreadcrumb()
    {
        // first page first :)
        $breadcrumb[] = $this->getFormDefinition()->getFirstPage();

        // then all of enabled page separators
        foreach( $this->getPageSeparators() as $pageSeparator ) {
            $breadcrumb[] = $pageSeparator->getLabel();
        }

        // then confirmation page, if enabled
        if ( $this->getFormDefinition()->getSummaryPage() ) {
            $breadcrumb[] = $this->getFormDefinition()->getSummaryLabel();
        }

        // last item is always receipt page
        $breadcrumb[] = $this->getFormDefinition()->getReceiptLabel();

        return $breadcrumb;
    }

    /**
     * Returns the integer index of current page
     * @return int
     */
    public function getCurrentPageIndex()
    {
        // as for now return first page (start counting from 0)
        return 0;
    }

    /**
     * Returns the attributes for current page index
     * @return array
     */
    public function getCurrentPageAttributes()
    {
        $currentPageAttributes = $this->getPageAttributes( $this->getCurrentPageIndex() );

        return $currentPageAttributes;
    }

    /**
     * Returns the attributes for given page index
     * @param int $pageIndex
     * @return array
     */
    private function getPageAttributes( $pageIndex )
    {
        $loopPageIndex = 0;
        $attributes = array();

        foreach( $this->getFormAttributes() as $attribute ) {

            switch( $attribute->getType()->getStringId() ) {

                case FormTypes::SEPARATOR_ID;

                    $loopPageIndex++;
                    break;

                default:

                    if ( $loopPageIndex === $pageIndex ) {
                        $attributes[] = $attribute;
                    }
                    break;
            }
        }

        return $attributes;
    }
}