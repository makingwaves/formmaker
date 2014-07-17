<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;

/**
 * Class RemoveController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class RemoveController extends Controller
{
    /**
     * @param $formId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeAction( $formId )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formDefinition = $entityManager->getRepository( 'FormMakerBundle:FormDefinitions' )->find( $formId );

        if ( !( $formDefinition instanceof FormDefinitions ) ) {
            $translator = $this->get( 'translator' );
            throw $this->createNotFoundException( $translator->trans( 'form.not.found', array(), 'formmaker' ) );
        }

        $entityManager->remove( $formDefinition );
        $entityManager->flush();

        return $this->redirect( $this->generateUrl( 'list_display' ) );
    }
} 