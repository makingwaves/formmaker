<?php

namespace MakingWaves\FormMakerBundle\Services\Page;

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

    abstract public function getAttributes();
} 