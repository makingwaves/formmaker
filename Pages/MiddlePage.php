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
} 