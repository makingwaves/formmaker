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
        $pageIndexIdentifier = $this->getSessionPrefix() . '.pageIndex';

        return $pageIndexIdentifier;
    }

    /**
     * @return string
     */
    private function getSessionPrefix()
    {
        $sessionPrefix = 'formmaker.' . $this->formId;

        return $sessionPrefix;
    }

    /**
     * @param $attributeId
     * @return string
     */
    private function getAttribuiteIdentifier( $attributeId )
    {
        $attributeIdentifier = $this->getSessionPrefix() . '.page.' . $this->getPageIndex() . '.attribute.' . $attributeId;

        return $attributeIdentifier;
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

    /**
     * Set the values from POST in session
     * @param array $postValues
     * @return $this
     */
    public function setValues( array $postValues )
    {
        foreach( $postValues as $postValue ) {

            $this->session->set( $this->getAttribuiteIdentifier( $postValue['id'] ), $postValue['value'] );
        }

        return $this;
    }

    public function getPageAttributeValues()
    {
        $pageAttributeValues = array();

        foreach( $this->session->all() as $key => $value ) {

            // TODO: 1. Wygenerować tablicę z danymi strony
            var_dump( $key, $value );
        }
    }
} 