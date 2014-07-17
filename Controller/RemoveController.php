<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

/**
 * Class RemoveController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class RemoveController extends Controller
{
    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction( $formId )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formDefinition = $entityManager->getRepository( 'FormMakerBundle:FormDefinitions' )->find( $formId );

        $entityManager->remove( $formDefinition );
        $entityManager->flush();

        return $this->redirect( $this->generateUrl( 'list_display' ) );
    }
} 