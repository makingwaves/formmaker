<?php

namespace MakingWaves\FormMakerBundle\Pages;

/**
 * Class ConfirmationPage
 * @package MakingWaves\FormMakerBundle\Services\Page
 */
class ConfirmationPage extends Page
{
    /**
     * @var string
     */
    private $pageTitle;

    /**
     * Get pageTitle
     * @return string
     */
    public function getTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set pageTitle
     * @param string $pageTitle
     * @return $this
     */
    public function setPageTitle( $pageTitle )
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Return empty array - there are no attributes on this page
     * @return array
     */
    public function getAttributes()
    {
        return array();
    }
} 