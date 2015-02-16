<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

/**
 * Class AjaxController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class AjaxController extends Controller
{
    /**
     * Action renders the form basing on given parameters
     * @param int $locationId
     * @param string(next|prev) $direction
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormAction( $locationId, $direction )
    {
        $formField = $this->getField( $locationId );
        $postValues = $this->getRequest()->request->get( 'formData', array() );
        $formField->value->getPagesContainer()->setValues( $postValues );

        switch( $direction ) {

            case 'next':
                $formField->value->getPagesContainer()->moveToNextPage();
                break;

            case 'prev':
                $formField->value->getPagesContainer()->moveToPreviousPage();
                break;
        }

        // @TODO: Check the 'Default' part
        return $this->render( 'FormMakerBundle:FormMaker/Views/Default:form.html.twig', array(
            'field' => $formField,
            'locationId' => $locationId
        ) );
    }

    /**
     * Returns the form field object
     * @param int $locationId
     * @return \MakingWaves\FormMakerBundle\eZ\Publish\FieldType\Form\Value
     */
    private function getField( $locationId )
    {
        /** @var $repository \eZ\Publish\API\Repository\Repository */
        $repository = $this->get( 'ezpublish.api.repository' );
        $location = $repository->getLocationService()->loadLocation( $locationId );
        $content = $repository->getContentService()->loadContent( $location->getContentInfo()->id );
        $formField = null;

        foreach ( $content->getFields() as $field ) {

            if ( $field->fieldDefIdentifier === 'form' ) {

                $formField = $field;
                break;
            }
        }

        return $formField;
    }
} 