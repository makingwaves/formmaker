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
     * @var string
     */
    const SESSION_PREFIX = 'formmaker';

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
        $sessionPrefix = self::SESSION_PREFIX . '.' . $this->formId;

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

    /**
     * Returns the values for current page attributes
     * @return array
     */
    public function getPageAttributeValues()
    {
        $pageAttributeValues = array();

        foreach( $this->session->all() as $key => $value ) {

            $parts = explode( '.', $key );

            if ( ( $parts[0] !== self::SESSION_PREFIX ) || ( !isset( $parts[1] ) || $parts[1] != $this->formId ) ||
                 ( !isset( $parts[3] ) || $parts[3] != $this->getPageIndex() ) || !isset( $parts[5] )) {

                continue;
            }

            $pageAttributeValues[$parts[5]] = $value;
        }

        return $pageAttributeValues;
    }
} 