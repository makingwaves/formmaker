<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;
use MakingWaves\FormMakerBundle\Form\FormDefinitionsType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EditController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class EditController extends Controller
{

    /**
     * @param Request $request
     * @param integer $formId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $formId)
    {
        if ( $formId == 0 ) {
            $formDefinitions = new FormDefinitions();
            $formDefinitions->setOwnerUser($this->getUser()->getApiUser()->id);
            $formDefinitions->setCreateDate(new \DateTime());
        } else {
            $entityManager = $this->getDoctrine()->getManager();
            $formDefinitions = $entityManager->getRepository('FormMakerBundle:FormDefinitions')->find($formId);
            if ( !$formDefinitions ) {
                $translator = $this->get('translator');
                throw $this->createNotFoundException($translator->trans('form.not.found', array(), 'formmaker'));
            }
        }

        $formDefForm = $this->createForm(new FormDefinitionsType(), $formDefinitions);

        $formDefForm->handleRequest($request);

        if ( $formDefForm->isValid() ) {
            if ( ! isset($entityManager) ) {
                $entityManager = $this->getDoctrine()->getManager();
            }
            $entityManager->persist($formDefinitions);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('list_display'));
        }

        return $this->render(
            'FormMakerBundle:Edit:create.html.twig',
            array(
                'formDefForm' => $formDefForm->createView()
            )
        );
    } // editAction
} // class EditController