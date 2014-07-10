<?php

namespace MakingWaves\FormMakerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;

/**
 * Class ListController
 * @package MakingWaves\FormMakerBundle\Controller
 */
class ListController extends Controller
{
    /**
     * Action displays the list of form definitions stored in database
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction()
    {
        return $this->render(
            'FormMakerBundle:List:display.html.twig',
            array(
                'formDefinitions' => $this->getDoctrine()->getRepository('FormMakerBundle:FormDefinitions')->findAll()
            )
        );
    }
}
