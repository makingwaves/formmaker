<?php

namespace MakingWaves\FormMakerBundle\Services;

use MakingWaves\FormMakerBundle\Entity\FormAttributes;
use MakingWaves\FormMakerBundle\Pages\ConfirmationPage;
use MakingWaves\FormMakerBundle\Pages\FirstPage;
use MakingWaves\FormMakerBundle\Pages\MiddlePage;
use MakingWaves\FormMakerBundle\Pages\Page;
use MakingWaves\FormMakerBundle\Pages\ReceiptPage;

/**
 * Class PagesFactory
 * @package MakingWaves\FormMakerBundle\Services
 */
class PagesFactory
{
    /**
     * @var int
     */
    private $formId;

    /**
     * @var FormSession
     */
    private $formSession;

    /**
     * @param FormSession $formSession
     */
    public function __construct( FormSession $formSession )
    {
        $this->formSession = $formSession;
    }

    /**
     * Set formId
     * @param $formId
     * @return $this
     */
    public function setFormId( $formId )
    {
        $this->formId = $formId;
        $this->formSession->setFormId( $formId );

        return $this;
    }

    /**
     * @param Page $page
     * @param null|string $pageTitle
     * @param null|FormAttributes $pageSeparator
     * @param null|array $pageAttributes
     * @return Page
     */
    public function factoryMethod( Page $page, $pageTitle = null, $pageSeparator = null, $pageAttributes = null )
    {
        if ( $page instanceof FirstPage ) {

            $this->createFirstPage( $page, $pageTitle, $pageAttributes );
        }
        elseif ( $page instanceof MiddlePage ) {

            $this->createMiddlePage( $page, $pageSeparator, $pageAttributes );
        }
        elseif( $page instanceof ConfirmationPage ) {

            $this->createConfirmationPage( $page, $pageTitle );
        }
        elseif( $page instanceof ReceiptPage ) {

            $this->createReceiptPage( $page, $pageTitle );
        }

        return $page;
    }

    /**
     * @param FirstPage $page
     * @param string $pageTitle
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes[] $pageAttributes
     */
    private function createFirstPage( FirstPage $page, $pageTitle, array $pageAttributes )
    {
        $page->setPageTitle( $pageTitle );
        $this->setAttributeSessionValues( $pageAttributes );
        $page->setPageAttributes( $pageAttributes );
    }

    /**
     * Method sets the session data for attributes
     * @param \MakingWaves\FormMakerBundle\Entity\FormAttributes[] $pageAttributes
     * @return $this
     */
    private function setAttributeSessionValues( array $pageAttributes )
    {
        $sessionData = $this->formSession->getPageAttributeValues();

        foreach( $pageAttributes as $attribute ) {

            if ( !isset( $sessionData[$attribute->getId()] ) ) {
                continue;
            }

            $attribute->setDefaultValue( $sessionData[$attribute->getId()] );
        }

        return $this;
    }

    /**
     * @param ConfirmationPage $page
     * @param string $pageTitle
     */
    private function createConfirmationPage( ConfirmationPage $page, $pageTitle )
    {
        $page->setPageTitle( $pageTitle );
    }

    /**
     * @param ReceiptPage $page
     * @param string $pageTitle
     */
    private function createReceiptPage( ReceiptPage $page, $pageTitle )
    {
        $page->setPageTitle( $pageTitle );
    }

    /**
     * @param MiddlePage $page
     * @param FormAttributes $pageSeparator
     * @param array $pageAttributes
     */
    private function createMiddlePage( MiddlePage $page, FormAttributes $pageSeparator, array $pageAttributes )
    {
        $page->setPageSeparator( $pageSeparator );
        $this->setAttributeSessionValues( $pageAttributes );
        $page->setPageAttributes( $pageAttributes );
    }
} 