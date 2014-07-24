<?php

namespace MakingWaves\FormMakerBundle\Services\Page;

/**
 * Class FirstPage
 * @package MakingWaves\FormMakerBundle\Services\Page
 */
class FirstPage extends Page
{
    private $pageAttributes;

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
     * @return array
     */
    public function getAttributes()
    {
        return $this->pageAttributes;
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
} 