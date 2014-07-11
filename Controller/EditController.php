<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

/**
 * Class EditController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class EditController extends Controller
{
    /**
     * Action displays the list of form definitions stored in database
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->render(
            'FormMakerBundle:Edit:create.html.twig',
            array(
                'formDefinitions' => $this->getDoctrine()->getRepository('FormMakerBundle:FormDefinitions')->findAll()
            )
        );
    }
}