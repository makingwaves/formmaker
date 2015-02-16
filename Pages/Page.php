<?php

namespace MakingWaves\FormMakerBundle\Pages;

/**
 * Class Page
 * @package MakingWaves\FormMakerBundle\Services\Page
 */
abstract class Page
{
    /**
     * Return the title of the page
     * @return string
     */
    abstract public function getTitle();

    /**
     * Method should return a list of attributes from current page
     * @return array
     */
    abstract public function getAttributes();

    /**
     * Set the values from POST
     * @param array $values
     * @return mixed
     */
    abstract public function setValues( array $values );

    /**
     * Returns page attribute values
     * @return array
     */
    abstract public function getValues();

    /**
     * Returns the type name of current page
     * @return string
     */
    abstract public function getType();
} 