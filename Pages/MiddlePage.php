<?php

namespace MakingWaves\FormMakerBundle\Pages;

use MakingWaves\FormMakerBundle\Entity\FormAttributes;

/**
 * Class MiddlePage
 * @package MakingWaves\FormMakerBundle\Services\Page
 */
class MiddlePage extends Page
{
    /**
     * @var FormAttributes
     */
    private $pageSeparator;

    /**
     * @var array
     */
    private $pageAttributes;

    /**
     * @var array
     */
    private $values;

    /**
     * @param FormAttributes $pageSeparator
     */
    public function setPageSeparator( FormAttributes $pageSeparator )
    {
        $this->pageSeparator = $pageSeparator;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->pageSeparator->getLabel();
    }

    /**
     * @param $pageAttributes
     * @return $this
     */
    public function setPageAttributes( $pageAttributes )
    {
        $this->pageAttributes = $pageAttributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->pageAttributes;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues( array $values )
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Return empty array - there are no attributes on this page
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
} 