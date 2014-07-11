<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use MakingWaves\FormMakerBundle\Entity\FormDefinitions;
use MakingWaves\FormMakerBundle\Form\FormDefinitionsType;

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
        $formDefinitions = new FormDefinitions();

        $formDefForm = $this->createForm(new FormDefinitionsType(), $formDefinitions);

        return $this->render(
            'FormMakerBundle:Edit:create.html.twig',
            array(
                'formDefForm' => $formDefForm->createView()
            )
        );
    }
}