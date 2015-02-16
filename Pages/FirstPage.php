<?php

namespace MakingWaves\FormMakerBundle\Pages;

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
     * @var array
     */
    private $values;

    /**
     * @var string
     */
    const TYPE = 'first';

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

    /**
     * Returns the type name of current page
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }
} 