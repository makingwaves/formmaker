<?php

namespace MakingWaves\FormMakerBundle\Pages;

/**
 * Class ReceiptPage
 * @package MakingWaves\FormMakerBundle\Services\Page
 */
class ReceiptPage extends Page
{
    /**
     * @var string
     */
    private $pageTitle;

    /**
     * @var string
     */
    const TYPE = 'receipt';

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

    /**
     * @param array $values
     * @return mixed|void
     */
    public function setValues( array $values )
    {
        // do nothing, since this page doesn't have a values
    }

    /**
     * Return empty array - there are no attributes on this page
     * @return array
     */
    public function getValues()
    {
        return array();
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