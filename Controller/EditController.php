<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormAttributes;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;
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
        $entityManager = $this->getDoctrine()->getManager();
        $formDefinitions = $entityManager->getRepository('FormMakerBundle:FormDefinitions')->find($formId);
        if ( !$formDefinitions ) {
            $translator = $this->get('translator');
            throw $this->createNotFoundException($translator->trans('form.not.found', array(), 'formmaker'));
        }

        $formDefinitionType = $this->get( 'formmaker.forms.type.definitions' );
        $formDefForm = $this->createForm( $formDefinitionType, $formDefinitions );

        $formDefForm->handleRequest($request);

        if ( $formDefForm->isValid() ) {
            $entityManager->persist($formDefinitions);
//            foreach($formDefinitions->getAttributes() as $attrib) {
//                $entityManager->persist($attrib);
//            }
            $entityManager->flush();
            // redirect to list of forms
            //return $this->redirect($this->generateUrl('list_display'));
            // redirect to edit
            return $this->redirect($this->generateUrl('edit', array('id' => $formDefinitions->getId())));
        } // if isValid

        // load attrib types for select tag
        $allTypes = $this->getDoctrine()->getRepository('FormMakerBundle:FormTypes')->findAll();

        return $this->render(
            'FormMakerBundle:Edit:edit.html.twig',
            array(
                'types' => $allTypes,
                'formDefForm' => $formDefForm->createView()
            )
        );
    } // editAction


    /**
     * Displays creation form, handles the form as well
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $formDefinitions = new FormDefinitions();
        $formDefinitions->setOwnerUser($this->getUser()->getApiUser()->id);
        $formDefinitions->setCreateDate(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $formDefinitionType = $this->get( 'formmaker.forms.type.definitions' );
        $formDefForm = $this->createForm($formDefinitionType, $formDefinitions);
        $formDefForm->handleRequest($request);

        if ( $formDefForm->isValid() ) {
            $entityManager->persist($formDefinitions);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('edit', array('id' => $formDefinitions->getId())));
        } // endif

        return $this->render(
            'FormMakerBundle:Edit:create.html.twig',
            array(
                'formDefForm' => $formDefForm->createView()
            )
        );
    } // createAction


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function newAttribAction(Request $request)
    {
        $formTypeId = $request->get('type_id');

        $entityManager = $this->getDoctrine()->getManager();
        $formType = $entityManager->getRepository('FormMakerBundle:FormTypes')->find($formTypeId);
        if ( ! $formType ) {
            $translator = $this->get('translator');
            throw $this->createNotFoundException($translator->trans('form.type.not.found', array(), 'formmaker'));
        }

        $attribute = new FormAttributes();
        $attribute->setType($formType);
        $attribForm = $this->createForm($this->get('formmaker.forms.type.attributes'), $attribute);

        return $this->render(
            'FormMakerBundle:Edit:newAttrib.html.twig',
            array(
                'attrib' => $attribForm->createView()
            )
        );
    } // newAttribAction
} // class EditController