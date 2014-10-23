<?php

namespace MakingWaves\FormMakerBundle\Services;

use Doctrine\ORM\EntityManager;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;
use MakingWaves\FormMakerBundle\Entity\FormTypes;
use MakingWaves\FormMakerBundle\Pages\ConfirmationPage;
use MakingWaves\FormMakerBundle\Pages\FirstPage;
use MakingWaves\FormMakerBundle\Pages\MiddlePage;
use MakingWaves\FormMakerBundle\Pages\Page;
use MakingWaves\FormMakerBundle\Pages\ReceiptPage;

class PagesContainer
{
    /**
     * @var array
     */
    private $pages = array();

    /**
     * @var int
     */
    private $formId;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormDefinitions
     */
    private $formDefinition;

    /**
     * @var \MakingWaves\FormMakerBundle\Entity\FormAttributes
     */
    private $formAttributes;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var PagesFactory
     */
    private $pagesFactory;

    /**
     * @var FormSession
     */
    private $formSession;

    /**
     * Default constructor
     * @param EntityManager $entityManager
     * @param PagesFactory $pagesFactory
     * @param FormSession $formSession
     */
    public function __construct( EntityManager $entityManager, PagesFactory $pagesFactory, FormSession $formSession )
    {
        $this->entityManager = $entityManager;
        $this->pagesFactory = $pagesFactory;
        $this->formSession = $formSession;
    }

    /**
     * Returns the formDefinition object for current form
     * @return FormDefinitions
     */
    public function getFormDefinition()
    {
        if ( is_null( $this->formDefinition ) ) {

            $formDefinitions = $this->entityManager->getRepository( 'FormMakerBundle:FormDefinitions' );
            $this->formDefinition = is_null( $this->formId ) ? null : $formDefinitions->find( $this->formId );
        }

        return $this->formDefinition;
    }

    /**
     * Return a set of form attributes
     * @return \MakingWaves\FormMakerBundle\Entity\FormAttributes[]
     */
    private function getFormAttributes()
    {
        if ( is_null( $this->formAttributes ) ) {

            $this->formAttributes = $this->entityManager->getRepository( 'FormMakerBundle:FormAttributes' )->findBy( array(
                'definition' => $this->getFormDefinition()->getId(),
            ), array(
                'attrOrder' => 'ASC'
            ) );
        }

        return $this->formAttributes;
    }

    /**
     * Returns a set of attributes that are typed as page separators
     * @param bool $onlyEnabled
     * @return array
     */
    protected function getPageSeparators( $onlyEnabled = true )
    {
        $pageSeparators = array();

        foreach( $this->getFormAttributes() as $attribute ) {

            if ( $attribute->getType()->getStringId() !== FormTypes::SEPARATOR_ID ) {
                continue;
            }

            if ( !( $onlyEnabled ) || ( $onlyEnabled && $attribute->getEnabled() ) ) {
                $pageSeparators[] = $attribute;
            }
        }

        return $pageSeparators;
    }

    /**
     * Set formId
     * @param int $formId
     * @return $this
     */
    public function setFormId( $formId )
    {
        $this->formId = $formId;
        $this->formSession->setFormId( $formId );

        return $this;
    }

    /**
     * Returns an array containing all pages with their labels. Use force flag to reload cached data.
     * @param bool $force
     * @return array
     */
    public function getPages( $force = false )
    {
        $this->pagesFactory->setFormId( $this->formId );
        $index = 0;

        if ( sizeof( $this->pages ) === 0 || $force === true ) {

            // first page first :)
            $this->pages[$index] = $this->pagesFactory->factoryMethod(
                new FirstPage(),
                $this->getFormDefinition()->getFirstPage(),
                null,
                $this->getPageAttributes( $index )
            );

            // then all of enabled page separators
            foreach( $this->getPageSeparators() as $pageSeparator ) {
                $index++;
                $this->pages[$index] = $this->pagesFactory->factoryMethod(
                    new MiddlePage(),
                    null,
                    $pageSeparator,
                    $this->getPageAttributes( $index )
                );
            }

            // then confirmation page, if enabled
            if ( $this->getFormDefinition()->getSummaryPage() ) {
                $index++;
                $this->pages[$index] = $this->pagesFactory->factoryMethod( new ConfirmationPage(), $this->getFormDefinition()->getSummaryLabel() );
            }

            // last item is always receipt page
            $index++;
            $this->pages[$index] = $this->pagesFactory->factoryMethod( new ReceiptPage(), $this->getFormDefinition()->getReceiptLabel() );
        }

        return $this->pages;
    }

    /**
     * Returns the object of current page. Use force flag to reload cached data.
     * @param bool $force
     * @return Page
     */
    public function getCurrentPage( $force = false )
    {
        $pages = $this->getPages( $force );

        return $pages[$this->getCurrentPageIndex()];
    }

    /**
     * Returns the integer index of current page
     * @return int
     */
    public function getCurrentPageIndex()
    {
        return $this->formSession->getPageIndex();
    }

    /**
     * Increment the page index
     * @return $this
     */
    public function moveToNextPage()
    {
        if ( $this->nextPageExists() ) {
            $this->formSession->increasePageIndex();
        }

        return $this;
    }

    /**
     * Decrement the page index
     * @return $this
     */
    public function moveToPreviousPage()
    {
        if ( $this->previousPageExists() ) {
            $this->formSession->decreasePageIndex();
        }

        return $this;
    }

    /**
     * Returns the attributes for given page index
     * @param int $pageIndex
     * @return array
     */
    private function getPageAttributes( $pageIndex )
    {
        $loopPageIndex = 0;
        $attributes = array();

        foreach( $this->getFormAttributes() as $attribute ) {

            switch( $attribute->getType()->getStringId() ) {

                case FormTypes::SEPARATOR_ID;

                    $loopPageIndex++;
                    break;

                default:

                    if ( $loopPageIndex === $pageIndex ) {
                        $attributes[] = $attribute;
                    }
                    break;
            }
        }

        return $attributes;
    }

    /**
     * Checks whether next page exists
     * @return bool
     */
    public function nextPageExists()
    {
        $nextPageExists = $this->getCurrentPageIndex() < ( sizeof( $this->getPages() ) - 1 );

        return $nextPageExists;
    }

    /**
     * Checks whether previous page exists
     * @return bool
     */
    public function previousPageExists()
    {
        $previousPageExists = $this->getCurrentPageIndex() > 0;

        return $previousPageExists;
    }

    /**
     * @param array $postValues
     * @return $this
     */
    public function setValues( array $postValues )
    {
        $this->formSession->setValues( $postValues );

        return $this;
    }
} 