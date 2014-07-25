<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

class AjaxController extends Controller
{
    public function renderFormAction( $locationId, $direction )
    {
        $formField = $this->getField( $locationId );
        switch( $direction ) {

            case 'next':
                $formField->value->getPagesContainer()->moveToNextPage();
                break;

            case 'prev':
                $formField->value->getPagesContainer()->moveToPreviousPage();
                break;
        }


        return $this->render( 'FormMakerBundle:FormMaker/Views/Default:form.html.twig', array(
            'field' => $formField,
            'locationId' => $locationId
        ) );
    }

    private function getField( $locationId )
    {
        $repository = $this->get( 'ezpublish.api.repository' );
        $content = $repository->getContentService()->loadContent( $locationId );
        $formField = null;

        foreach ( $content->getFields() as $field ) {

            if ( $field->fieldDefIdentifier === 'form' ) {

                $formField = $field;
                break;
            }

            //file_put_contents( '/var/www/upgrade53/web/debug.log', var_export( $field->value->formId, true ), FILE_APPEND );
        }

        return $formField;
    }
} 