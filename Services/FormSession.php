<?php

namespace MakingWaves\FormMakerBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class FormSession
 * @package MakingWaves\FormMakerBundle\Services
 */
class FormSession
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var int
     */
    private $formId;

    /**
     * Default constructor
     * @param Session $session
     */
    public function __construct( Session $session )
    {
        $this->session = $session;
    }

    /**
     * Set formId
     * @param $formId
     * @return $this
     */
    public function setFormId( $formId )
    {
        $this->formId = $formId;

        return $this;
    }

    /**
     * Method returns the string identifier for page index
     * @return string
     */
    private function getPageIndexIdentifier()
    {
        $pageIndexIdentifier = 'formmaker_' . $this->formId . '_page_index';

        return $pageIndexIdentifier;
    }

    /**
     * Returns the current page index
     * @return mixed
     */
    public function getPageIndex()
    {
        $pageIndex = $this->session->get( $this->getPageIndexIdentifier(), 0 );

        return $pageIndex;
    }

    /**
     * Increment page index
     * @return $this
     */
    public function increasePageIndex()
    {
        $pageIndex = $this->getPageIndex();
        $pageIndex++;
        $this->session->set( $this->getPageIndexIdentifier(), $pageIndex );

        return $this;
    }

    /**
     * Decrement page index
     * @return $this
     */
    public function decreasePageIndex()
    {
        $pageIndex = $this->getPageIndex();
        $pageIndex--;
        $this->session->set( $this->getPageIndexIdentifier(), $pageIndex );

        return $this;
    }
} 