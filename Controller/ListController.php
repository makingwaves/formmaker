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
        $formDefinitions = $this->getDoctrine()->getRepository('FormMakerBundle:FormDefinitions')->findAll();
        $userNames = array();
        $userService = $this->get( 'formmaker.user' );

        // get users names
        foreach($formDefinitions as $definition) {

            $userId = $definition->getOwnerUser();
            if (isset($userNames[$userId])) {
                continue;
            }

            $userService->loadUser( $userId );
            $userNames[$userId] = $userService->getName();
        }

        return $this->render(
            'FormMakerBundle:List:display.html.twig',
            array(
                'formDefinitions' => $formDefinitions,
                'userNames' => $userNames
            )
        );
    }
}
